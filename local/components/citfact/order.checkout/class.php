<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Sale\Basket\Storage;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\Order;
use Bitrix\Sale\OrderStatus;
use Bitrix\Sale\Registry;
use Citfact\Sitecore\Location\LocTypeManager;
use Citfact\Sitecore\Order\OrderRepository;
use Citfact\SiteCore\OrderTemplate\OrderTemplateManager;
use Citfact\SiteCore\UserDataManager\UserDataManager;

Loc::loadMessages(__FILE__);

class OrderCheckout extends CBitrixComponent implements Controllerable
{
    const DELIVERY_TRANSPORT_COMPANY = 'TRANSPORT'; // любая транспортная компания

    /** @var $order \Bitrix\Sale\Order **/
    protected $order; // основной заказ

    /** @var \Bitrix\Sale\Basket\Storage $basketStorage */
    protected $basketStorage;

    public $isAjax = false;
    protected $userId = 0;
    protected $currency = 'RUB';
    protected $page = '';
    protected $siteId;
    protected $personTypeId = '1'; // юридическое лицо
    private $needSaveOrder = false; // если сохранение формы заказа
    protected $needUpdateOrder = false; // если обновление формы заказа
    protected $idsPayments = []; // массив П/С по символьным кодам
    protected $idsDeliveries = []; // массив идентификаторов Доставок по символьным кодам
    protected $dataDeliveries = []; // полный массив Доставок

    /**
     * @param $arParams
     * @return array
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams)
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->userId = $USER->GetID();
        }

        return $arParams;
    }


    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'createOrder' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }


    /**
     * @param string $jsonFormData
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function createOrderAction($jsonFormData = '')
    {
        $this->request->set(json_decode($jsonFormData, true));
        $this->isAjax = true;
        return $this->executeComponent();
    }

    /**
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        /**
         * если пользователь не авторизован
         * показываем авторизацию
         */
        if (!$this->userId) {
            $this->page = 'auth';
            $this->includeComponentTemplate($this->page);
            return [];
        }

        /**
         * если в реквесте есть ORDER_ID
         * выводим страницу success
         */
        if ($this->request->get('ORDER_ID')) {
            $this->arResult['ORDER'] = $this->getOrderInfoByAccountNumber($this->request->get('ORDER_ID'));

            if ($this->arResult['ORDER']['ID']) {
                $this->order = \Bitrix\Sale\Order::load($this->arResult['ORDER']['ID']);

                $this->page = 'success';


                $this->includeComponentTemplate($this->page);
                return [];

            }
        }


        /**
         * Если шаг сохранения заказа
         */
        if ($this->request->get('save') && $this->request->get('save') == 'Y') {
            $this->needSaveOrder = true;
        }


        /**
         * Если шаг обновления заказа
         */
        if ($this->request->get('update') && $this->request->get('update') == 'Y') {
            $this->needUpdateOrder = true;
        }

        /**
         * покдлючаем длеовые линии, главдоставку и пэк
         * чтобы восмользоваться функцией DellinDelivery::setCnt
         */
        \Bitrix\Main\Loader::includeModule('dellindev.delivery');
        \Bitrix\Main\Loader::includeModule('glavdostavka.delivery');
        \Bitrix\Main\Loader::includeModule('pecom.ecomm');

        /**
         * подготовка заказа
         */
        $this->prepareOrder();

        /**
         * сохраняем заказ (основной)
         */
        $orderResult = $this->saveOrderMain($this->order);
        $this->arResult['RESULT'] = $orderResult;

        /**
         * если заказ успешно создан, делаем редирект
         */
        if ($orderResult->isSuccess() && $this->order->getId()) {
            //После успешного заказа восстанавливаем сохраненную текущую корзину
            $orderTplManager = new OrderTemplateManager();
            $orderTplManager->restoreLastCurrentCart();

            $this->arResult['REDIRECT_PAGE'] =  $this->arParams['PATH_TO_ORDER'] . '?ORDER_ID='.$this->order->getField('ACCOUNT_NUMBER');
        }


        // AJAX-режим или нет
        if ($this->isAjax === true) {
            ob_start();
            $this->includeComponentTemplate($this->page);
            $this->arResult['RESPONSE']['html'] = ob_get_contents();
            ob_end_clean();
        } else {
            if ($orderResult && $orderResult->isSuccess() && $this->arResult['REDIRECT_PAGE']) {
                LocalRedirect($this->arResult['REDIRECT_PAGE']);
            }

            $this->includeComponentTemplate($this->page);
        }

        
        return $this->arResult;
    }


    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function prepareOrder()
    {
        $this->siteId = \Bitrix\Main\Context::getCurrent()->getSite();

        $this->arResult['CURRENCY'] = $this->currency;

        $this->arResult['PERSON_TYPE_ID'] = $this->personTypeId;

        /**
         * $basketItems список элементов корзины
         */
        $basketLoad = \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            $this->siteId
        );
        $basketItems = $basketLoad->getOrderableItems();

        /**
         * если элементов нет, делаем редирект в корзину
         */
        if (count($basketItems) == 0) {
            if ($this->needSaveOrder) {
                $this->arResult['REDIRECT_PAGE'] = $this->arParams['PATH_TO_BASKET'];
                return;

            } else {
                LocalRedirect($this->arParams['PATH_TO_BASKET']);
            }
        }


        /**
         * сумма корзины
         */
        $this->arResult['BASKET_PRICE'] = $basketItems->getPrice();
        $this->arResult['BASKET_PRICE_FORMAT'] = $this->formatPrice($basketItems->getPrice());


        /**
         * список элементов каталога
         */
        $this->arResult['BASKET_ITEMS'] = \Citfact\Sitecore\Order\Basket::getProductItemsByBasket($basketItems, $this->currency, $this->siteId);

        /**
         * вес и объем
         */
        $this->arResult['VES_BRUTTO'] = 0;
        $this->arResult['OBEM'] = 0;
        foreach ($this->arResult['BASKET_ITEMS'] as $BASKET_ITEM) {
            $this->arResult['VES_BRUTTO'] += $BASKET_ITEM['PROPERTIES']['VES_BRUTTO']['VALUE'] * $BASKET_ITEM['BASKET']['QUANTITY'];
            $this->arResult['OBEM'] += $BASKET_ITEM['PROPERTIES']['OBEM']['VALUE'] * $BASKET_ITEM['BASKET']['QUANTITY'];
        }


        /**
         * список адресов для самовывоза
         */
        $this->getPickupList();

        /**
         * список адресов для собственной курьерской доставки
         */
        $this->getShippingAddressesList();

        /**
         * список грузполучателей
         */
        $this->getConsigneesList();

        /**
         * достаем список платежных систем (полный, включая неактивные) из базы
         */
        $this->getPaySystemsList();


        /**
         * достаем список активных доставок из базы
         */
        $this->getDeliverySystemsList();


        /**
         * создаем виртуальный (предварительный заказ)
         */
        try {
            $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
            /** @var Order $orderClassName */
            $orderClassName = $registry->getOrderClassName();

            $this->order = $orderClassName::create($this->siteId, $this->userId);
            $this->order->isStartField();
            $this->order->setField('STATUS_ID', OrderStatus::getInitialStatus());

            $this->order->setPersonTypeId($this->arResult['PERSON_TYPE_ID']);

            /**
             * Заполняем свойства заказа
             */
            $this->setOrderProps($this->order);

            /**
             * заполняем корзину
             */
            $this->order->appendBasket($basketItems);


            /**
             * подставляем доставку
             */
            $shipment = $this->setOrderShipment($this->order);

            /**
             * вызов событий 'sale', 'OnBefore'.self::$eventClassName.'FinalAction'
             * калькуляция скидки
             * калькуляция налога
             * вызов событий 'sale', 'OnAfter'.self::$eventClassName.'FinalAction'
             */
            $this->order->doFinalAction(true);

            /**
             * устанавливаем платежную систему
             */
            $this->setOrderPayment($this->order);

            /**
             * достаем список доступных доставок + подсчет стоимости, для заказа
             */
            $this->getAvailableDeliveries($this->order, $shipment);


            /**
             * итоговая стоимость заказа
             */
            $this->arResult['ORDER_PRICE'] = $this->order->getPrice();
            $this->arResult['ORDER_PRICE_FORMAT'] = $this->formatPrice($this->order->getPrice());

        } catch (\Exception $e) {
            $this->arResult[] = $e->getMessage();
        }
    }



    /**
     * достаем все платежные системы
     */
    public function getPaySystemsList()
    {
        $dbPayments = \Bitrix\Sale\Internals\PaySystemActionTable::getList([
            'select' => ['CODE', 'ID'],
            'filter' => ['*'],
            'order' => ['SORT' => 'ASC']
        ]);
        while ($payment = $dbPayments->fetch()) {
            $this->idsPayments[$payment['CODE']] = $payment['ID'];
        }
    }


    /**
     * @return Storage|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     */
    protected function getBasketStorage()
    {
        if (!isset($this->basketStorage))
        {
            $this->basketStorage = Storage::getInstance(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
        }

        return $this->basketStorage;
    }



    /**
     * @param BasketBase $basket
     * @return array
     */
    protected function getActualQuantityList(BasketBase $basket)
    {
        $quantityList = array();

        if (!$basket->isEmpty())
        {
            /** @var Sale\BasketItemBase $basketItem */
            foreach ($basket as $basketItem)
            {
                if ($basketItem->canBuy() && !$basketItem->isDelay())
                {
                    $quantityList[$basketItem->getBasketCode()] = $basketItem->getQuantity();
                }
            }
        }

        return $quantityList;
    }


    /**
     * достаем все доставки
     *
     * @throws \Bitrix\Main\ArgumentException
     */
    public function getDeliverySystemsList()
    {
        $this->dataDeliveries = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
        foreach ($this->dataDeliveries as $id => $delivery) {
            if ($delivery['CODE']) {
                $this->idsDeliveries[$delivery['CODE']] = $id;
            }
        }
    }

    /**
     * @param $code
     * @return array|string|null
     */
    public function getRequestByCode($code){
        return $this->request->get($code);
    }

    /**
     * @param \Bitrix\Sale\Order $order
     * @throws ReflectionException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function setOrderProps(\Bitrix\Sale\Order $order)
    {
        $arContragent = UserDataManager::getContrAgentInfo();

        $arUserInfo = \Bitrix\Main\UserTable::getList([
            'filter' => ['ID' => $this->userId],
            'select' => ['PERSONAL_PHONE', 'UF_POSITION', 'ID'],
        ])->fetch();


        /**
         * адрес доставки и код кладр
         */
        $shipAddress = '';
        $kladrAddress = '';
        $idAddress = '';
        $locCityKladr = '';
        if ($valAddressHandle = $this->request->get('SHIP_ADDRESS_LIST_HANDLE')) { // ручной ввод адреса доставки
            $shipAddress = $valAddressHandle;

            if ($valKladr = $this->request->get('LOCATION_KLADR')) { // код кладр из дадаты
                $kladrAddress = $valKladr;
            }

        } else {
            $valAddressShip = $this->request->get('SHIP_ADDRESS_LIST');
            $valAddressShipItem = [];
            foreach ($this->arResult['SHIP_ADDRESS_LIST'] as $item) { // выбор из установленного списка
                if ($valAddressShip) {
                    /**
                     * еслие передали SHIP_ADDRESS_LIST
                     */
                    if ($item['ID'] == $valAddressShip) {
                        $valAddressShipItem = $item;
                    }
                } else {
                    /**
                     * если не передали SHIP_ADDRESS_LIST
                     * берем первый из списка
                     */
                    $valAddressShipItem = $item;
                    break;
                }
            }

            if (!empty($valAddressShipItem)) {
                $shipAddress = $valAddressShipItem['UF_NAME'];
                $kladrAddress = $valAddressShipItem['UF_KLADR'];
                $idAddress = $valAddressShipItem['UF_XML_ID'];

                $locCityKladr = $valAddressShipItem['UF_GOROD'];
                if (!$locCityKladr) {
                    $locCityKladr = $valAddressShipItem['UF_REGION'];
                }
                if ($locCityKladr) {
                    /**
                     * вырезаем из города букву г
                     */
                    if ((strlen($locCityKladr)-2) == strpos($locCityKladr, ' г')) {
                        $locCityKladr = substr($locCityKladr, 0, strpos($locCityKladr, ' г'));
                    }
                }
            }
        }


        /**
         * город доставки
         */
        $locCity = '';
        if ($this->request->get('LOCATION_CITY') && !($locCity = $this->GetCityCodeByName($this->request->get('LOCATION_CITY')))) {

        } elseif ($locCityKladr) {
            $locCity = $this->GetCityCodeByName($locCityKladr);
        }


        /**
         * заполняем свойства
         * иначе заполняем из свойств пользователя
         *
         * @var $prop \Bitrix\Sale\PropertyValue
         */
        foreach ($order->getPropertyCollection() as $prop) {
            $code = $prop->getField('CODE');
                switch ($code) {
                    /** ФИО менеджера */
                    case 'NAME_MANAGER':
                        $userAssistants = \Citfact\SiteCore\User\UserManagers::getAssistantByContragent($arContragent['UF_XML_ID']);
                        $prop->setValue(\Citfact\SiteCore\User\UserHelper::getFullNameByUser($userAssistants));
                        break;

                    /** телефон */
                    case 'PHONE':
                        $prop->setValue($arUserInfo['PERSONAL_PHONE']);
                        break;

                    /** Должность */
                    case 'POSITION':
                        $prop->setValue($arUserInfo['UF_POSITION']);
                        break;

                    /** Контрагент */
                    case 'CONTRAGENT':
                        $prop->setValue($arContragent['UF_XML_ID']);
                        break;

                    /** Название компании */
                    case 'COMPANY_NAME':
                        $prop->setValue($arContragent['UF_NAME']);
                        break;

                    /** ИНН */
                    case 'INN':
                        $prop->setValue($arContragent['UF_INN']);
                        break;

                    /** Фактический адрес компании */
                    case 'COMPANY_ADDRESS':
                        $prop->setValue($arContragent['UF_ADRESFAKT']);
                        break;

                    /** Телефон компании */
                    case 'PHONE_COMPANY':
                        $prop->setValue(implode(', ', $arContragent['UF_TELEFON']));
                        break;

                    /** Адрес доставки (КЛАДР) */
                    case 'DELIVERY_KLADR':
                        if ($kladrAddress) {
                            $prop->setValue($kladrAddress);
                            $this->arResult['LOCATION_KLADR'] = $kladrAddress;
                        }
                        break;

                    /** ID адреса доставки */
                    case 'ID_ADDRESS_DELIVERY':
                        if ($idAddress) {
                            $prop->setValue($idAddress);
                        }
                        break;

                    /** Грузополучатель */
                    case 'CONSIGNEES':
                        if ($this->request->get('CONSIGNEES_HANDLE')) {

                        } else {
                            if ($this->request->get('CONSIGNEES_LIST')) {
                                foreach ($this->arResult['CONSIGNEES_LIST'] as $value) {
                                    if ($this->request->get('CONSIGNEES_LIST') == $value['ID']) {
                                        $prop->setValue($value['UF_NAME']);
                                    }
                                }
                            }
                        }
                        break;

                    /** Грузополучатель ручной ппод */
                    case 'CONSIGNEES_HANDLE':
                        if ($this->request->get('CONSIGNEES_HANDLE')) {
                            $prop->setValue($this->request->get('CONSIGNEES_HANDLE'));
                        }
                        break;

                    /** Город местоположения */
                    case 'LOCATION':
                        /** по имени города из дадаты, достаем идентификатор местоположения */
                        if ($locCity) {
                            $prop->setValue($locCity);
                            $this->arResult['LOCATION_CITY'] = $this->request->get('LOCATION_CITY');
                        }
                        break;

                    /** Пункт самовывоза */
                    case 'PICKUP':
                        foreach ($this->arResult['PICKUP_LIST'] as $item) {
                            if ($item['ID'] == $this->request->get($code)) {
                                $prop->setValue($item['UF_ADDRESS']);
                            }
                        }
                        break;

                    /** Адрес доставки */
                    case 'SHIP_ADDRESS_LIST':
                        if ($shipAddress) {
                            $prop->setValue($shipAddress);
                        }
                        break;

                    /** Не печатать цены */
                    case 'NO_PRINT_PRICE':
                    /** Паллетный борт */
                    case 'PALLET_BOARD':
                    /** Паллетировать обязательно */
                    case 'PALLET_REQUIRED':
                        if ($this->request->get($code)) {
                            $prop->setValue($this->request->get($code));
                        }
                        break;

                    default:
                        $prop->setValue($this->request->get($code));
                }
        }


        /**
         * Если это шаг сохранения заказа и обязательное свойство не заполнено, то отправляем ошибку
         */
        if ($this->needSaveOrder) {
            foreach ($order->getPropertyCollection() as $prop) {
                /** @var \Bitrix\Sale\PropertyValue $prop */
                if ($prop->isRequired() && empty($prop->getValue())) {
                    $this->arResult['ERRORS'][] = 'Не заполнено поле: ' . $prop->getName();
                }
            }
        }
    }


    /**
     * @param $code
     * @return string|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function getPropValueByCode($code)
    {
        if (!$code || !$this->order) {
            return null;
        }

        foreach ($this->order->getPropertyCollection() as $prop) {
            /** @var \Bitrix\Sale\PropertyValue $prop */
            $codeProp = $prop->getField('CODE');
            if ($code == $codeProp) {
                return $prop->getValue();
            }
        }

        return null;
    }


    /**
     * @param $bx_location_to_name
     * @return string
     * @throws ReflectionException
     */
    public static function GetCityCodeByName($bx_location_to_name)
    {
        $kladr_code = '';

        $cache = new CPHPCache();
        $life_time = 24*60*60;
        $cache_id = __CLASS__ . __METHOD__.$bx_location_to_name;

        if ($cache->InitCache($life_time, $cache_id)) {
            $cache_data = $cache->GetVars();
            $kladr_code = $cache_data['VALUE'];

        } else {
            /**
             * Тип местоположения - город
             */
            $LocTypeManager = LocTypeManager::getInstance();
            $locTypeCity = $LocTypeManager->getByCode('CITY');

            $resCountry = \Bitrix\Sale\Location\LocationTable::getList(array(
                'filter' => array(
                    '=NAME.LANGUAGE_ID' => LANGUAGE_ID,
                    '=TYPE_ID' => $locTypeCity,
                    '=NAME.NAME' => $bx_location_to_name
                ),
                'select' => array('NAME_RU' => 'NAME.NAME', 'CODE', 'ID')
            ));
            if ($arCountry = $resCountry->fetch()) {
                $kladr_code = $arCountry['CODE'];
            }

            $cache->StartDataCache($life_time, $cache_id);
            $cache->EndDataCache(array('VALUE' => $kladr_code));
        }

        return $kladr_code;
    }


    /**
     * @param Order $order
     * @return \Bitrix\Sale\Shipment
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\SystemException
     */
    private function setOrderShipment(\Bitrix\Sale\Order $order)
    {
        $taxes = $order->getTax();
        $taxes->setDeliveryCalculate($this->arParams['COUNT_DELIVERY_TAX'] === 'Y');


        /* @var $shipmentCollection \Bitrix\Sale\ShipmentCollection */
        $shipmentCollection = $order->getShipmentCollection();

        $deliveryId = $this->request->get('DELIVERY_ID');
        $deliveryIdTransport = $this->request->get('DELIVERY_ID_TRANSPORT');
        if (!$deliveryId) {
            $deliveryId = OrderRepository::DELIVERY_PICKUP; // default delivery
        }

        $this->arResult['DELIVERY_ID'] = $deliveryId;

        if ($deliveryId == self::DELIVERY_TRANSPORT_COMPANY) {
            if (!$deliveryIdTransport) {
                $deliveryIdTransport = OrderRepository::DELIVERY_PEK;

            }

            $deliveryId = $deliveryIdTransport;
        }


        if ($deliveryIdTransport && $this->request->get('DELIVERY_ID') == self::DELIVERY_TRANSPORT_COMPANY) {
            $this->arResult['DELIVERY_ID_TRANSPORT'] = $deliveryIdTransport;
        }


        /**
         * если есть активная доставка, ставим ее как отгрузку
         */
        if ($deliveryId > 0) {
            $shipment = $shipmentCollection->createItem(
                Bitrix\Sale\Delivery\Services\Manager::getObjectById(
                    intval($deliveryId)
                )
            );
        } else {
            /**
             * если нет, создаем отгрузку
             */
            $shipment = $shipmentCollection->createItem();
        }

        /** @var $shipmentItemCollection \Bitrix\Sale\ShipmentItemCollection */
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $order->getCurrency());

        /**
         * добавляем в отгрузку элементы заказа
         * запрещаем доставкам расчитывать стоимомомость
         * иначе они считают на каждом хите
         */
        \DellinDelivery::setCnt(count($order->getBasket()));
        \GlavDostavka::setCnt(count($order->getBasket()));
        \Ipolh\Pecom\deliveryHandler::setCnt(count($order->getBasket()));
        foreach ($order->getBasket() as $item) {
            /**
             * @var $item \Bitrix\Sale\BasketItem
             * @var $shipmentItem \Bitrix\Sale\ShipmentItem
             */
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }

        return $shipment;
    }


    /**
     * @param \Bitrix\Sale\Order $order
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     */
    private function setOrderPayment(\Bitrix\Sale\Order $order)
    {
        $paySystemId = (int)$this->arParams['DEFAULT_PAYSYSTEM_ID'];

        /**
         * если есть платежная система, добавляем оплату к заказу
         */
        if ($paySystemId > 0) {
            $this->arResult['PAYMENT_ID'] = $paySystemId;
            $paymentCollection = $order->getPaymentCollection();
            $payment = $paymentCollection->createItem(
                Bitrix\Sale\PaySystem\Manager::getObjectById(
                    $paySystemId
                )
            );
            $payment->setField("SUM", ($order->getPrice()));
            $payment->setField("CURRENCY", $order->getCurrency());
        }
    }


    /**
     * @param \Bitrix\Sale\Order $order
     * @param $selectShipment
     */
    public function getAvailableDeliveries(\Bitrix\Sale\Order $order, $selectShipment)
    {
        $this->arResult['DELIVERY_PRICE'] = 0;
        $this->arResult['DELIVERY_PRICE_FORMAT'] = $this->formatPrice($this->arResult['DELIVERY_PRICE']);

        $deliveryId = $this->request->get('DELIVERY_ID');
        $deliveryIdTransport = $this->arResult['DELIVERY_ID_TRANSPORT'];
        if ($this->arResult['DELIVERY_ID'] == self::DELIVERY_TRANSPORT_COMPANY && !$deliveryIdTransport) {
            $deliveryIdTransport = OrderRepository::DELIVERY_PEK;
        }

        if ($deliveryIdTransport) {
            $deliveryId = $deliveryIdTransport;
        }


        /**
         * список доступных доставок отгрузки
         */
        $availableDeliveries = \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($selectShipment);
        foreach ($availableDeliveries as $id => $delivery) {
            /** @var $delivery \Bitrix\Sale\Delivery\Services\Configurable **/
            $arDelivery = [
                'ID' => $delivery->getId(),
                'NAME' => $delivery->getName(),
                'CURRENCY' => $delivery->getCurrency(),
                'SORT' => $delivery->getSort(),
                'CODE' => $delivery->getCode(),
                'DESCRIPTION' => $delivery->getDescription(),
                'SELECTED' => ($deliveryId == $delivery->getId()) ? true : false,
                'PRICE' => 0,
                'PRICE_FORMATTED' => 0,
                'LOGO' => $delivery->getLogotipPath(),
                'PERIOD' => '',
            ];


            /**
             * для выбранной доставки достаем стоимость
             */
            if ($deliveryId == $delivery->getId()) {
                $selectShipment->setField('DELIVERY_ID', $delivery->getId());
                $selectShipment->setField('CURRENCY', $order->getCurrency());
                $calcResult = $delivery->calculate($selectShipment);
                if ($calcResult->isSuccess()) {
                    $arDelivery['PERIOD'] = $calcResult->getPeriodDescription();
                    $arDelivery['PRICE'] = $calcResult->getPrice();
                    $arDelivery['PRICE_FORMATTED'] = $this->formatPrice($calcResult->getPrice(), $order->getCurrency());


                    /**
                     * считаем общую сумму доставки всех подзаказов
                     */
                    $this->arResult['DELIVERY_PRICE'] = $arDelivery['PRICE'];
                    $this->arResult['DELIVERY_PRICE_FORMAT'] = $this->formatPrice($this->arResult['DELIVERY_PRICE']);
                }
            }

            $this->arResult['DELIVERIES'][$id] = $arDelivery;
        }
        unset($id);
        unset($delivery);
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getPickupList()
    {
        $this->arResult['PICKUP_LIST'] = [];

        $core = \Citfact\SiteCore\Core::getInstance();
        $hlid = $core->getHlBlockId($core::HLBLOCK_CODE_PICKUP);

        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['*']
        ]);
        while ($arItem = $res->fetch()) {
            $this->arResult['PICKUP_LIST'][] = $arItem;
        }
    }


    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getShippingAddressesList()
    {
        $this->arResult['SHIP_ADDRESS_LIST'] = [];

        $contragent = UserDataManager::getUserContragentXmlID();
        if (!$contragent) {
            return;
        }

        $core = \Citfact\SiteCore\Core::getInstance();
        $hlid = $core->getHlBlockId($core::HLBLOCK_CODE_SHIPPING_ADDRESSES);

        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['UF_KONTRAGENT' => $contragent]
        ]);
        while ($arItem = $res->fetch()) {
            $this->arResult['SHIP_ADDRESS_LIST'][] = $arItem;
        }
    }


    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getConsigneesList()
    {
        $this->arResult['CONSIGNEES_LIST'] = [];

        $contragent = UserDataManager::getUserContragentXmlID();
        if (!$contragent) {
            return;
        }

        $this->arResult['CONSIGNEES_LIST'][] = UserDataManager::getContrAgentInfo($contragent);

        $core = \Citfact\SiteCore\Core::getInstance();
        $hlid = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);

        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['UF_OSNOVNOYKONTRAGEN' => $contragent]
        ]);
        while ($arItem = $res->fetch()) {
            $this->arResult['CONSIGNEES_LIST'][] = $arItem;
        }
    }

    /**
     * @param $price
     * @param bool $currencyTo
     * @param bool $currencyFrom
     * @return null|string|string[]
     */
    public function formatPrice($price, $currencyTo=false, $currencyFrom=false) {
        if (!$currencyTo) {
            $currencyTo = $this->currency;
        }

        if ($currencyFrom && $currencyFrom != $currencyTo) {
            $price = \CCurrencyRates::ConvertCurrency($price, $currencyFrom, $currencyTo);
        }

        return \SaleFormatCurrency(
            $price,
            $currencyTo
        );
    }

    /**
     * @param \Bitrix\Sale\Order $order
     * @return \Bitrix\Sale\Result
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     */
    private function saveOrderMain(\Bitrix\Sale\Order $order)
    {
        /**
         * возвращаемый результат
         */
        $result = new \Bitrix\Sale\Result();

        /**
         * если не сохранение заказа или есть ошибки, НЕ продолжаем
         */
        if ($this->needSaveOrder === false || !empty($this->errors)) {
            return $result;
        }

        /**
         * корзина
         */
        $basket = $order->getBasket();


        /**
         * Устанавливаем поля комментария покупателя
         */
        if ($this->request->get('COMMENT')) {
            $order->setField('USER_DESCRIPTION', $this->request->get('COMMENT'));
        }


        /**
         * создание заказа
         * ставим стоимость доставки
         */
        if ($basket->count() > 0 && $result->isSuccess()) {

            /**
             * в служебные свойства заказа подставляем стоимость
             */
            $selectShipment = false;
            $shipmentCollection = $order->getShipmentCollection();
            foreach ($shipmentCollection as $shipmentItem) {
                /**
                 * @var \Bitrix\Sale\Shipment $shipmentItem
                 * @var \Bitrix\Sale\Shipment $selectShipment
                 */
                if (!$shipmentItem->isSystem()) {
                    $selectShipment = $shipmentItem;
                }
            }

            if ($selectShipment) {
                $selectShipment->setField('DELIVERY_ID', 0);
                $selectShipment->setField('PRICE_DELIVERY', 0);

                /**
                 * @var $prop \Bitrix\Sale\PropertyValue
                 */
                foreach ($order->getPropertyCollection() as $prop) {
                    $code = $prop->getField('CODE');

                    switch ($code) {
                        case 'DELIVERY':
                            $prop->setValue($selectShipment->getDeliveryName());
                            break;
                        case 'DELIVERY_ID':
                            $prop->setValue($selectShipment->getDeliveryId());
                            break;
                        case 'DELIVERY_PRICE':
                            $prop->setValue($selectShipment->getPrice());
                            break;
                    }
                }
            }

            /**
             * удаляем отгрузки при оформлении заказа.
             * должны проставиться из 1С
             */
            $shipmentCollection->clearCollection();
//            $order->setField('PRICE_DELIVERY', false);
//            $order->setField('DELIVERY_ID', false);


            /**
             * сохраняем главный заказ
             */
            $result = $order->save();
        }

        return $result;
    }



    /**
     * @param int $id
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     */
    private function getOrderInfoByAccountNumber($id = 0)
    {
        return \Bitrix\Sale\Order::getList([
            'filter' => ['ACCOUNT_NUMBER' => $id, 'USER_ID' => $this->userId],
            'select' => ['*'],
        ])->fetch();
    }

}