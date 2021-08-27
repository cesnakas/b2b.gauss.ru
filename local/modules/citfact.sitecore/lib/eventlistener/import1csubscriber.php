<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Internals\OrderPropsValueTable;
use Bitrix\Sale\Internals\OrderTable;
use Bitrix\Sale\Order;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Core;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Shipment\ShipmentStatus;
use Citfact\Sitecore\Order\OrderRepository;
use Citfact\SiteCore\UserDataManager\UserDataManager;
use Bitrix\Main\Entity\Event as EntityEvent;
use Bitrix\Main\Entity\EventResult;

Loc::loadMessages(__FILE__);

class Import1CSubscriber implements SubscriberInterface
{

    public static $isSuccessStatusesImport = false;
    public static $isSuccessShipmentsImport = false;
    public static $isSuccessFilesImport = false;
    public static $isSuccessReserveImport = false;
    const EVENT_OTGRUZKA_INFO = 'OTGRUZKA_INFO';
    const EVENT_PAYMENT_INFO = 'PAYMENT_INFO';

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImport1C', 'method' => 'upDateWeight'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImport1C', 'method' => 'disableEmptySections'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImport1C', 'method' => 'disableElementsWithoutSections'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImport1C', 'method' => 'disableElementsWithoutPrices'],
            ['module' => '', 'event' => 'StatusyTovarovOnBeforeUpdate', 'method' => 'cleanUpdateDates'],
            ['module' => '', 'event' => 'StatusyTovarovOnBeforeUpdate', 'method' => 'checkChangeStatuses'],
            ['module' => '', 'event' => 'OtgruzkiOnBeforeUpdate', 'method' => 'checkChangeStatuses'],
            ['module' => '', 'event' => 'OtgruzkiOnBeforeUpdate', 'method' => 'cleanUpdateDates'],
            ['module' => '', 'event' => 'SvyazannyeFaylyOnBeforeUpdate', 'method' => 'cleanUpdateDates'],
            ['module' => '', 'event' => 'RezervyOnBeforeUpdate', 'method' => 'cleanUpdateDates'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'checkOrderDates'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'checkUpdateDates'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'updateOrderStatus'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'sendEventByChangeStatus'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'sendEventByOtgruzka'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'sendEventByPayment'],
            ['module' => 'catalog', 'event' => 'OnBeforeProductUpdate', 'method' => 'checkNotificationsListWait'],
        ];
    }

    /**
     * Обработчик события перед обновлением записи в HL
     *
     * @param EntityEvent $event
     * @return EventResult
     */
    public static function checkChangeStatuses(EntityEvent $event)
    {

        $entityCode = $event->getEntity()->getName();
        $result = new EventResult();

        $params = $event->getParameters();

        $arFields = $params['fields'];
        $entityId = $params['id']['ID'];


        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($entityCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList([
            'select' => ['ID', 'UF_STATUS'],
            'filter' => ['ID' => $entityId]
        ]);

        $exStatus = null;

        while ($row = $rsData->fetch()) {
            $exStatus = $row['UF_STATUS'];
        }

        if ($exStatus === $arFields['UF_STATUS'] || empty($arFields['UF_STATUS'])) {
            return $result;
        } else {

            $arFields['UF_IS_SEND'] = null;
            $params['fields']['UF_IS_SEND'] = null;

            $event->setParameters($params);
            $result->modifyFields($arFields);
        }

        return $result;

    }

    /**
     * Обработчик события перед обновлением записи в HL
     *
     * @param EntityEvent $event
     * @return EventResult
     */
    public static function cleanUpdateDates(EntityEvent $event)
    {
        $entityCode = $event->getEntity()->getCode();
        $result = new EventResult();

        if ($entityCode === 'STATUSY_TOVAROV' && self::$isSuccessStatusesImport === true) {
            return $result;
        }

        if ($entityCode === 'OTGRUZKI' && self::$isSuccessShipmentsImport === true) {
            return $result;
        }

        if ($entityCode === 'SVYAZANNYE_FAYLY' && self::$isSuccessFilesImport === true) {
            return $result;
        }

        if ($entityCode === 'REZERVY' && self::$isSuccessReserveImport === true) {
            return $result;
        }

        $params = $event->getParameters();

        $arFields = $params['fields'];

        if (!empty($arFields['UF_DATE_UPDATE'])) {
            $arFields['UF_DATE_UPDATE'] = null;
        }

        $result->modifyFields($arFields);

        return $result;
    }


    /**
     * подставляем параметры товара из свойств
     * @throws \Exception
     */
    function upDateWeight($arParams, $ABS_FILE_NAME)
    {
        $fileNamesToApply = ['import_items.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (in_array($fileName, $fileNamesToApply)) {
            $core = Core::getInstance();
            $db_res = \CCatalogProduct::GetList(array(), array(), false, false);
            while ($ar_res = $db_res->GetNext()) {
                $arSelect = ['ID', 'IBLOCK_ID', 'PROPERTY_DLINA', 'PROPERTY_SHIRINA', 'PROPERTY_VYSOTA', 'PROPERTY_VES_BRUTTO']; //получаем вес товара из свойства
                $arFilter = ['IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ID' => $ar_res["ID"]];
                $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                while ($ob = $res->GetNext()) {
                    $arFields = [];
                    $arFields['LENGTH'] = $ob['PROPERTY_DLINA_VALUE'];
                    $arFields['WIDTH'] = $ob['PROPERTY_SHIRINA_VALUE'];
                    $arFields['HEIGHT'] = $ob['PROPERTY_VYSOTA_VALUE'];
                    $arFields['WEIGHT'] = $ob['PROPERTY_VES_BRUTTO_VALUE'] * 1000;
                    \CCatalogProduct::Update($ar_res["ID"], $arFields);
                }
            }
        }
    }

    /**
     * после выгрузки деактивируем пустые разделы
     * @throws \Exception
     */
    function disableEmptySections($arParams, $ABS_FILE_NAME)
    {
        $fileNamesToApply = ['import_items.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (in_array($fileName, $fileNamesToApply)) {
            $core = Core::getInstance();
            $arFilter = array('IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ACTIVE' => 'Y');
            $rsSect = \CIBlockSection::GetList(array(), $arFilter, ['ELEMENT_SUBSECTIONS' => 'Y', 'CNT_ACTIVE' => 'Y'], ['ID']);
            while ($arSect = $rsSect->GetNext()) {
                if ($arSect['ELEMENT_CNT'] == '0') {
                    $CIBlockSection = new \CIBlockSection();
                    $CIBlockSection->Update($arSect['ID'], ['ACTIVE' => 'N']);
                    static::log($arSect, 'disableSectionsWithoutElements');
                }
            }
        }
    }

    /**
     * после выгрузки элементов деактивируем элементы без разделов
     * @throws \Exception
     */
    function disableElementsWithoutSections($arParams, $ABS_FILE_NAME)
    {
        $fileNamesToApply = ['import_items.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (in_array($fileName, $fileNamesToApply)) {
            $core = Core::getInstance();
            $arFilter = ['IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ACTIVE' => 'Y', 'IBLOCK_SECTION_ID' => false];
            $dbElements = \CIBlockElement::GetList([], $arFilter, ['IBLOCK_ID', 'ID', 'NAME']);
            while ($element = $dbElements->Fetch()) {
                $CIBlockElement = new \CIBlockElement();
                $result = $CIBlockElement->Update($element['ID'], ['ACTIVE' => 'N']);

                if (true === $result) {
                    static::log($element, 'updateItemsWithoutSections');
                } else {
                    global $APPLICATION;

                    static::log([$element['ID'] . '---' . $APPLICATION->GetException()->GetString()], 'updateItemsWithoutSectionsErrors');
                }
            }
        }
    }

    /**
     * после выгрузки цен деактивируем элементы без базовой цены
     * @throws \Exception
     */
    function disableElementsWithoutPrices($arParams, $ABS_FILE_NAME)
    {
        $fileNamesToApply = ['prices.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (in_array($fileName, $fileNamesToApply)) {
            $core = Core::getInstance();
            $priceId = Price::PRICE_ID_MAIN;
            $arFilter = ['IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ACTIVE' => 'Y', ['LOGIC' => 'OR', ['=CATALOG_PRICE_' . $priceId => false], ['=CATALOG_PRICE_' . $priceId => 0]]];
            $dbElements = \CIBlockElement::GetList([], $arFilter, ['IBLOCK_ID', 'ID', 'NAME']);
            while ($element = $dbElements->Fetch()) {
                $CIBlockElement = new \CIBlockElement();
                $result = $CIBlockElement->Update($element['ID'], ['ACTIVE' => 'N']);

                if (true === $result) {
                    static::log($element, 'updateItemsWithoutPrices');
                } else {
                    global $APPLICATION;

                    static::log([$element['ID'] . '---' . $APPLICATION->GetException()->GetString()], 'updateItemsWithoutPricesErrors');
                }
            }
        }
    }


    /**
     * Устанавливает у элементов справочника значения в поле UF_DATE_UPDATE,
     * если это поле у элемента было пустое.
     * Если нет, то удаляет его.
     * $hlBlockCode - код справочника
     * $uniquePropertyField - поле, для отбора уникальных элементов (что-то по типу ID)
     *
     * @param string $hlBlockCode
     * @param string $uniquePropertyField
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function setUpdateDates(string $hlBlockCode, string $uniquePropertyField)
    {

        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($hlBlockCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList([
            'select' => ['ID', $uniquePropertyField],
            'filter' => ['UF_DATE_UPDATE' => false]
        ]);

        $orderIds = [];
        $idsToUpdate = [];

        while ($row = $rsData->fetch()) {
            $orderIds[] = $row[$uniquePropertyField];
            $idsToUpdate[] = $row['ID'];
        }

        if (!empty($orderIds)) {
            $rsData = $entity_data_class::getList([
                'select' => ['ID', $uniquePropertyField],
                'filter' => [$uniquePropertyField => $orderIds, '!=UF_DATE_UPDATE' => false]
            ]);

            while ($row = $rsData->fetch()) {
                $entity_data_class::delete($row['ID']);
            }
        }

        if (!empty($idsToUpdate)) {
            foreach ($idsToUpdate as $id) {
                $entity_data_class::update($id, ['UF_DATE_UPDATE' => date('d-m-Y H:i:s')]);
            }
        }

    }

    /**
     * HL-блок DebitorskayaZadolzhennost
     * Проставляет дату в поле UF_DATA, если оно не заполнено.
     */
    public static function checkOrderDates($arParams, $ABS_FILE_NAME)
    {
        $fileName = basename($ABS_FILE_NAME);
        $core = Core::getInstance();

        switch ($fileName) {
            case 'references_receivables.xml':
                $hlBlockCode = $core::HLBLOCK_CODE_DEBITORSKAYA_ZADOLZHENNOST;
                $core = Core::getInstance();
                $hl_id = $core->getHlBlockId($hlBlockCode);
                $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
                $entity = HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();
                $rsData = $entity_data_class::getList([
                    'select' => ['ID', 'UF_ZAKAZ', 'UF_DATA'],
                    //'filter' => ['UF_DATA' => false]
                ]);

                $idsToUpdate = [];
                $orders = [];

                $db_orders = OrderTable::getList(
                    [
                        'select' => ['ID', 'ID_1C', 'DATE_INSERT'],
                    ]
                );

                while ($order = $db_orders->fetch()) {
                    $orders[$order['ID_1C']] = $order;
                }

                while ($row = $rsData->fetch()) {
                    $orderID = $row['UF_ZAKAZ'];
                    $date = '';
                    if (isset($orders[$orderID])) {
                        $date = date("d.m.Y", strtotime($orders[$orderID]['DATE_INSERT']));
                    } else {
                        $date = $row['UF_DATA'] ?: '';
                    }

                    if ($date) {
                        $idsToUpdate[$row['ID']] = $date;
                    }
                }

                if (!empty($idsToUpdate)) {
                    foreach ($idsToUpdate as $key => $id) {
                        $res = $entity_data_class::update($key, ['UF_DATA' => $id, 'UF_TIME' => new \Bitrix\Main\Type\Date($id, 'd.m.Y')]);
                    }
                }
                break;
        }
    }

    /**
     * HL-блоки StatusyTovarov и Otgruzki:
     * Проставляет дату в поле UF_DATE_UPDATE, если оно не заполнено.
     * Удаляет записи для выгружаемого заказа, если поле UF_DATE_UPDATE заполнено
     */
    public static function checkUpdateDates($arParams, $ABS_FILE_NAME)
    {

        $fileName = basename($ABS_FILE_NAME);
        $core = Core::getInstance();

        switch ($fileName) {
            case 'references_productsStatuses.xml':
                $hlBlockCode = $core::HLBLOCK_CODE_STATUSY_TOVAROV;
                $uniquePropertyField = 'UF_ZAKAZID';
                self::$isSuccessStatusesImport = true;
                break;
            case 'references_shipments.xml':
                $hlBlockCode = $core::HLBLOCK_CODE_OTGRUZKI;
                $uniquePropertyField = 'UF_ZAKAZID';
                self::$isSuccessShipmentsImport = true;
                break;
            /*case 'references_files.xml':
            case 'references_filesShipments.xml':
            case 'references_filesOrders.xml':
                $hlBlockCode = $core::HLBLOCK_CODE_ORDER_FILES;
                $uniquePropertyField = 'UF_ID';
                self::$isSuccessFilesImport = true;
                break;*/
            case 'references_reserve.xml':
                $hlBlockCode = $core::HLBLOCK_CODE_REZERVY;
                $uniquePropertyField = 'UF_NOMENKLATURA';
                self::$isSuccessReserveImport = true;
                break;

        }

        if (empty($hlBlockCode) || empty($uniquePropertyField)) {
            return;
        }


        self::setUpdateDates($hlBlockCode, $uniquePropertyField);
    }

    /**
     * Устанавливет статусы заказов в "выполнен" если у всех элементов ХЛ-блока стоит статус "Отгружен".
     * Алгоритм: выбираем все элементы из ХЛ-блока, если у всех товаров заказа стоит статус "Отгружен",
     * сохраняем в $shippedOrders, для дальнейшей проверки на то, что в ХЛ-блоке были данные по всем товарам заказа.
     * Получаем все товары и корзины для заказов из ХЛ-блока и проверяем что все товары в корзине присутсвуют в заказе из ХЛ-блока.
     * Если не всем товары, то означает, что по отсутствующим товарам статусы не известны и заказ не выполнен.
     * @param $arParams
     * @param $ABS_FILE_NAME
     * @throws \Exception
     */
    function updateOrderStatus($arParams, $ABS_FILE_NAME)
    {
        $fileNamesToApply = ['references_productsStatuses.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (in_array($fileName, $fileNamesToApply)) {
            $core = Core::getInstance();
            $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_STATUSY_TOVAROV);
            $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
            $entity = HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $rsData = $entity_data_class::getList(array(
                'select' => array('UF_ZAKAZID', 'UF_STATUS', 'UF_NOMENKLATURAID'),
            ));

            $statusyTovarov = []; //Все товары их хайлоадблока
            while ($row = $rsData->fetch()) {
                $orderID_1C = $row['UF_ZAKAZID'];
                $statusyTovarov[$orderID_1C][] = $row;
            }

            $shippedOrders = [];    //заказы, у товаров которого статус 'Отгружен'
            $shippedOrderID_1C = []; //массив ID, чтобы разом получить данные из запроса
            foreach ($statusyTovarov as $orderID_1C => $items) {
                $areAllItemsShipped = true; //флаг - все товары отгружены
                foreach ($items as $item) {
                    if ($item['UF_STATUS'] != ShipmentStatus::SHIPPED) {
                        $areAllItemsShipped = false;
                        break;
                    }
                }
                if ($areAllItemsShipped) {
                    $shippedOrderID_1C[] = $orderID_1C;
                    $shippedOrders[$orderID_1C] = $items;
                }
            }
            //массив чтобы по $orderID_1C и по $producXmlID быстро проверить наличие товара в ХЛ StatusyTovarov
            $presentItems = [];
            foreach ($shippedOrders as $orderID_1C => $items) {
                foreach ($items as $item) {
                    $producID = $item['UF_NOMENKLATURAID'];
                    $presentItems[$orderID_1C][$producID] = 1;
                }
            }

            //из массива ID 1С отгруженных заказов получаем ID заказов из SalesOrder
            $mapOrderIDto1C_ID = []; //массив связывает ORDER_ID => ID_1C
            $arFilter = array('ID_1C' => $shippedOrderID_1C, '!=STATUS_ID' => OrderRepository::STATUS_ID_PAYED);
            $arSelect = array('ID', 'ID_1C');
            $rsSales = \CSaleOrder::GetList(array(), $arFilter, $arSelect);
            $shippedOrderIDs = [];
            while ($arSales = $rsSales->Fetch()) {
                $orderID = $arSales['ID'];
                $shippedOrderIDs[] = $orderID;
                $mapOrderIDto1C_ID[$orderID] = $arSales['ID_1C'];
            }

            //получаем список товаров из корзины каждого отгруженного заказа
            $arFilter = array("ORDER_ID" => $shippedOrderIDs);
            $arSelect = array("ORDER_ID", "ID", "PRODUCT_XML_ID");
            $rsBasketItems = \CSaleBasket::GetList(array(), $arFilter,
                false,
                false,
                $arSelect
            );
            $arBasketItems = [];
            while ($arItems = $rsBasketItems->Fetch()) {
                $orderID = $arItems['ORDER_ID'];
                $orderID_1C = $mapOrderIDto1C_ID[$orderID];
                $producXmlID = $arItems['PRODUCT_XML_ID'];
                $arBasketItems[$orderID_1C][$producXmlID] = $arItems;
            }

            //Проверяем, что товары из корзины есть в заказе ХЛ блока
            foreach ($arBasketItems as $orderID_1C => $items) {
                foreach ($items as $producXmlID => $item) {
                    if (empty($presentItems[$orderID_1C][$producXmlID])) {
                        $orderID = $arItems['ORDER_ID'];
                        unset($shippedOrderIDs[$orderID]);
                    }
                }
            }

            //устанавливаем статус отгрузки
            foreach ($shippedOrderIDs as $orderId) {
                $result = \CSaleOrder::StatusOrder($orderId, OrderRepository::STATUS_ID_PAYED);
            }
        }
    }

    /**
     * уведомляем пользователя об изменении статуса товаров в заказах
     *
     * @param $arParams
     * @param $ABS_FILE_NAME
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function sendEventByChangeStatus($arParams = [], $ABS_FILE_NAME = '')
    {
        /**
         * если нам выгрузили файлы статусов товаров в заказах
         */
        $fileNamesToApply = ['references_productsStatuses.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (!in_array($fileName, $fileNamesToApply)) {
            return;
        }

        /**
         * Достаем все статусы товаров, статус которых не равен "Отгружен" и по которым не отправлено уведомление
         */
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_STATUSY_TOVAROV);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            'select' => array('ID', 'UF_ZAKAZID', 'UF_NOMENKLATURAID', 'UF_IS_SEND', 'UF_STATUS', 'UF_SROKPOSTAVKI', 'UF_KOLICHESTVO'),
            'filter' => array('!=UF_IS_SEND' => 'Y', '!=UF_ZAKAZID' => null, '!=UF_STATUS' => ShipmentStatus::SHIPPED),
        ));

        $arXmlIdsOrders = []; // xml id order
        $shipments = []; // отгрузки
        while ($row = $rsData->fetch()) {
            $status = (trim($row['UF_STATUS']) === ShipmentStatus::NOT_AVAILABLE) ? trim($row['UF_STATUS']) : trim($row['UF_STATUS'] . ' ' . $row['UF_SROKPOSTAVKI']);
            $shipments[$row['UF_ZAKAZID']][] = [
                'SHIPMENT_ID' => $row['ID'],
                'UF_NOMENKLATURAID' => $row['UF_NOMENKLATURAID'],
                'STATUS' => $status,
                'AMOUNT' => $row['UF_KOLICHESTVO']
            ];
            $arXmlIdsOrders[$row['UF_ZAKAZID']] = $row['UF_ZAKAZID'];
        }

        /**
         * достаем список заказов по выгруженным статусам
         */
        $arOrders = [];
        $arOrderIds = [];
        $arUsersIds = [];
        $resOrders = OrderTable::getList([
            'filter' => ['ID_1C' => $arXmlIdsOrders],
            'select' => ['ID_1C', 'ID', 'USER_ID'],
        ]);

        while ($arOrder = $resOrders->fetch()) {
            $arOrders[$arOrder['ID_1C']] = $arOrder;
            $arOrderIds[] = $arOrder['ID'];
            $arUsersIds[] = $arOrder['USER_ID'];
        }

        if (empty($arOrders)) {
            return;
        }

        /**
         * Достаем контрагентов из заказов
         */
        $arContragentOrders = [];
        $resPropertiesOrders = OrderPropsValueTable::getList([
            'filter' => ['ORDER_ID' => $arOrderIds, 'CODE' => 'CONTRAGENT'],
            'select' => ['ID', 'ORDER_ID', 'CODE', 'VALUE'],
        ]);

        while ($arContragentOrder = $resPropertiesOrders->fetch()) {
            $arContragentOrders[$arContragentOrder['ORDER_ID']] = $arContragentOrder['VALUE'];
        }

        /**
         * Проставляем контрагентов в заказы и добавляем массив со статусами товаров(далее отгрузки)
         */
        foreach ($arOrders as $xmlId => $arOrder) {
            $arOrders[$xmlId]['CONTRAGENT_ID'] = $arContragentOrders[$arOrder['ID']];
            $arOrders[$xmlId]['SHIPMENTS'] = $shipments[$xmlId];
        }

        /**
         * Устанавливаем имя у каждого товара отгрузки из корзины заказа
         */
        $resBaskets = BasketTable::getList([
            'filter' => ['ORDER_ID' => $arOrderIds],
            'select' => ['ORDER_ID', 'ID', 'PRODUCT_ID', 'PRODUCT_XML_ID', 'NAME', 'QUANTITY'],
        ]);

        while ($arBasket = $resBaskets->fetch()) {
            foreach ($arOrders as $xmlId => &$arOrder) {
                foreach ($arOrder['SHIPMENTS'] as &$shipment) {
                    if ($shipment['UF_NOMENKLATURAID'] === $arBasket['PRODUCT_XML_ID']) {
                        $shipment['NAME'] = $arBasket['NAME'];
                    }
                }
                unset($shipment);
            }
            unset($arOrder);
        }

        /**
         * список пользователей заказов
         */
        $arUsers = [];
        $resUsers = UserTable::getList([
            'filter' => ['ID' => $arUsersIds, 'ACTIVE' => 'Y', 'UF_ACTIVATE_PROFILE' => '1'],
            'select' => ['ID', 'ACTIVE', 'UF_ACTIVATE_PROFILE', 'EMAIL', 'XML_ID'],
        ]);
        while ($arUser = $resUsers->fetch()) {
            $arUser['IS_CONTRAGENT'] = (UserDataManager::isUserContragentByXmlId($arUser['XML_ID'])) ? 'Y' : 'N';
            $arUsers[$arUser['ID']] = $arUser;
        }

        foreach ($arOrders as $orderXmlId => $order) {

            /**
             * если пользователь заказа контрагент, ему письмо не отправляем
             * рассылаем письма всем пользователям привязанным к контрагенту
             */
            $emailsTo = [];
            $arUser = $arUsers[$order['USER_ID']];

            if (!$arUser || $arUser['IS_CONTRAGENT'] == 'Y') {
                $arUsersContragent = UserDataManager::getUsersContragentByXmlId($order['CONTRAGENT_ID']);
                foreach ($arUsersContragent as $user) {
                    $emailsTo[$user['EMAIL']] = $user['EMAIL'];
                }

                /**
                 * если пользователь не коотрагент
                 * письма посылаем ему
                 */
            } else {
                $emailsTo[$arUser['EMAIL']] = $arUser['EMAIL'];
            }

            if (empty($emailsTo)) {
                continue;
            }

            /**
             * шаблон списка товаров отгрузок
             */
            global $APPLICATION;
            $templateOtgruzka = $APPLICATION->IncludeComponent(
                "citfact:mail.buffer.template",
                "statuses",
                Array(
                    "ITEMS" => $order['SHIPMENTS'],
                    "SITE_ID" => Core::DEFAULT_SITE_ID,
                )
            );

            /**
             * отправляем письмо с отгрузкой
             */
            $arEventFields = [
                'BASKET' => $templateOtgruzka,
                'EMAIL_TO' => implode(',', $emailsTo),
                'SALE_EMAIL' => \COption::GetOptionString("sale", "order_email"),
                'ORDER_ID' => $order['ID']
            ];

            /** @var $resEvent \Bitrix\Main\Entity\AddResult */
            $resEvent = \Bitrix\Main\Mail\Event::send([
                'EVENT_NAME' => self::EVENT_OTGRUZKA_INFO,
                'LID' => Core::DEFAULT_SITE_ID,
                'C_FIELDS' => $arEventFields
            ]);

            if ($resEvent->isSuccess()) {
                /**
                 * если успешно отправлено, у всей отгрузки ставим статус - отправлено
                 */
                foreach ($order['SHIPMENTS'] as $shipment) {
                    $entity_data_class::update($shipment['SHIPMENT_ID'], [
                        'UF_IS_SEND' => 'Y'
                    ]);
                }
            }
        }
    }


    /**
     * уведомлякм пользователя о новых отгрузках
     *
     * @param $arParams
     * @param $ABS_FILE_NAME
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function sendEventByOtgruzka($arParams = [], $ABS_FILE_NAME = '')
    {
        /**
         * если нам выгрузили файлы отгрузки
         */
        $fileNamesToApply = ['references_shipments.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (!in_array($fileName, $fileNamesToApply)) {
            return;
        }


        /**
         * достаем весь список не отправленных отгрузок
         */
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_OTGRUZKI);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            'select' => array('ID', 'UF_KONTRAGENTID', 'UF_IS_SEND', 'UF_NOMER1S', 'UF_STATUS',
                'UF_ID', 'UF_ADRESDOSTAVKI', 'UF_GRUZOPOLUCHATEL', 'UF_TOVARID', 'UF_KOLICHESTVO',
                'UF_REALIZATSIYANOMER', 'UF_REALIZATSIYADATA', 'UF_STATUS', 'UF_ZAKAZID'),
            'filter' => array('!=UF_IS_SEND' => 'Y'),
        ));

        $arXmlIdsOrders = []; // xml id order
        $arOtgruzka = []; // отгрузки
        while ($row = $rsData->fetch()) {
            $arOtgruzka[$row['UF_STATUS']][$row['UF_ZAKAZID']][$row['UF_ID']][] = $row;
            $arXmlIdsOrders[$row['UF_ZAKAZID']] = $row['UF_ZAKAZID'];
        }


        /**
         * достаем список заказов по отгрузкам
         */
        $arOrders = [];
        $arOrderIds = [];
        $arUsersIds = [];
        $resOrders = OrderTable::getList([
            'filter' => ['ID_1C' => $arXmlIdsOrders],
            'select' => ['ID_1C', 'ID', 'USER_ID'],
        ]);
        while ($arOrder = $resOrders->fetch()) {
            $arOrders[$arOrder['ID_1C']] = $arOrder;
            $arOrderIds[] = $arOrder['ID'];
            $arUsersIds[] = $arOrder['USER_ID'];
        }

        /**
         * корзины заказов
         */
        $resBaskets = BasketTable::getList([
            'filter' => ['ORDER_ID' => $arOrderIds],
            'select' => ['ORDER_ID', 'ID', 'PRODUCT_ID', 'PRODUCT_XML_ID', 'NAME', 'QUANTITY'],
        ]);
        while ($arBasket = $resBaskets->fetch()) {
            foreach ($arOrders as $k => $arOrder) {
                $arOrders[$k]['BASKET'][$arBasket['PRODUCT_XML_ID']] = $arBasket;
            }
        }


        /**
         * список пользователей заказов
         */
        $arUsers = [];
        $resUsers = UserTable::getList([
            'filter' => ['ID' => $arUsersIds, 'ACTIVE' => 'Y', 'UF_ACTIVATE_PROFILE' => '1'],
            'select' => ['ID', 'ACTIVE', 'UF_ACTIVATE_PROFILE', 'EMAIL', 'XML_ID'],
        ]);
        while ($arUser = $resUsers->fetch()) {
            $arUser['IS_CONTRAGENT'] = (UserDataManager::isUserContragentByXmlId($arUser['XML_ID'])) ? 'Y' : 'N';
            $arUsers[$arUser['ID']] = $arUser;
        }


        /**
         * перебираем отгрузки в рамках заказов
         */
        foreach ($arOtgruzka as $status => $arOrdersOtgruzkaList) {
            foreach ($arOrdersOtgruzkaList as $idOrder => $otrguzkaList) {
                $order = $arOrders[$idOrder];
                if (empty($order)) {
                    continue;
                }
                foreach ($otrguzkaList as $idOtgruzka => $itemsOtgruzka) {
                    $firstOtgruzka = $itemsOtgruzka[0];


                    /**
                     * если пользователь заказа контрагент, ему письмо не отправляем
                     * рассылаем письма всем пользователям привязанным к контрагенту
                     */
                    $emailsTo = [];
                    $arUser = $arUsers[$order['USER_ID']];
                    if (!$arUser || $arUser['IS_CONTRAGENT'] == 'Y') {
                        $arUsersContragent = UserDataManager::getUsersContragentByXmlId($firstOtgruzka['UF_KONTRAGENTID']);
                        foreach ($arUsersContragent as $user) {
                            $emailsTo[$user['EMAIL']] = $user['EMAIL'];
                        }

                        /**
                         * если пользователь не коотрагент
                         * письма посылаем ему
                         */
                    } else {
                        $emailsTo[$arUser['EMAIL']] = $arUser['EMAIL'];
                    }


                    /**
                     * соотносим элементы отгрузки и элементы корзины в заказе
                     */
                    foreach ($itemsOtgruzka as $k => $item) {
                        $itemsOtgruzka[$k]['BASKET'] = $order['BASKET'][$item['UF_TOVARID']];
                    }


                    /**
                     * шаблон списка товаров отгрузок
                     */
                    global $APPLICATION;
                    $templateOtgruzka = $APPLICATION->IncludeComponent(
                        "citfact:mail.buffer.template",
                        "otgruzka",
                        Array(
                            "OTGRUZKA" => $firstOtgruzka,
                            "ITEMS" => $itemsOtgruzka,
                            "SITE_ID" => Core::DEFAULT_SITE_ID,
                        )
                    );

                    /**
                     * отправляем письмо с отгрузкой
                     */
                    $arEventFields = [
                        'BASKET' => $templateOtgruzka,
                        'EMAIL_TO' => implode(',', $emailsTo),
                        "SALE_EMAIL" => \COption::GetOptionString("sale", "order_email"),
                    ];
                    /** @var $resEvent \Bitrix\Main\Entity\AddResult */
                    $resEvent = \Bitrix\Main\Mail\Event::send([
                        'EVENT_NAME' => self::EVENT_OTGRUZKA_INFO,
                        'LID' => Core::DEFAULT_SITE_ID,
                        'C_FIELDS' => $arEventFields
                    ]);
                    if ($resEvent->isSuccess()) {
                        /**
                         * если успешно отправлено, у всей отгрузки ставим статус - отправлено
                         */
                        foreach ($itemsOtgruzka as $item) {
                            $entity_data_class::update($item['ID'], [
                                'UF_IS_SEND' => 'Y'
                            ]);
                        }
                    }
                }


                /**
                 * когда все отгрузки по заказу пришли - меняем статус заказа на выполнен
                 */
                if ($arOrder['STATUS_ID'] != OrderRepository::STATUS_ID_PAYED) {
                    $arOtgruzkaByOrder = [];
                    $rsData = $entity_data_class::getList(array(
                        'select' => array('ID', 'UF_KONTRAGENTID', 'UF_IS_SEND', 'UF_NOMER1S', 'UF_STATUS',
                            'UF_ID', 'UF_ADRESDOSTAVKI', 'UF_GRUZOPOLUCHATEL', 'UF_TOVARID', 'UF_KOLICHESTVO',
                            'UF_REALIZATSIYANOMER', 'UF_REALIZATSIYADATA', 'UF_STATUS', 'UF_ZAKAZID'),
                        'filter' => array('UF_IS_SEND' => 'Y', 'UF_ZAKAZID' => $order['ID_1C'], 'UF_STATUS' => ShipmentStatus::SHIPPED),
                    ));
                    while ($row = $rsData->fetch()) {
                        $arOtgruzkaByOrder[$row['UF_TOVARID']] = $row;
                    }


                    $isFullOtrguzki = true;
                    $arBasketQuant = [];
                    foreach ($order['BASKET'] as $k => $basketItem) {
                        $arBasketQuant[$basketItem['PRODUCT_XML_ID']] = $basketItem['QUANTITY'];
                    }

                    foreach ($arBasketQuant as $xmlId => $qnt) {
                        $itemOtgruzka = $arOtgruzkaByOrder[$xmlId];
                        $qnt = (int)$qnt - (int)$itemOtgruzka['UF_KOLICHESTVO'];
                        $arBasketQuant[$xmlId] = $qnt;

                        if ($qnt <= 0) {
                            unset($arBasketQuant[$xmlId]);
                        }
                    }

                    if (!empty($arBasketQuant)) {
                        $isFullOtrguzki = false;
                    }

                    if ($isFullOtrguzki) {
                        $obOrder = Order::load($arOrder['ID']);
                        $obOrder->setField('STATUS_ID', OrderRepository::STATUS_ID_PAYED);
                        $obOrder->save();
                    }
                }
            }
        }
    }


    function sendEventByPayment($arParams = [], $ABS_FILE_NAME = '')
    {
        /**
         * если нам выгрузили файлы заказов
         */
        $fileNamesToApply = ['references_filesOrders.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (!in_array($fileName, $fileNamesToApply)) {
            return;
        }

        /**
         * список файлов оплат
         */
        $arOrderFiles = [];
        $files = \Citfact\Sitecore\Order\OrderFiles::getListFilter($filter = array(
            'UF_TIP' => 'Счет',
            '!=UF_SEND' => 'Y',
        ));
        foreach ($files as $file) {
            $arOrderFiles[$file['UF_ID']][] = $file;
        }

        if (empty($arOrderFiles)) {
            return;
        }

        /**
         * список заказов
         */
        $arOrders = [];
        $arUsersIds = [];
        $resOrders = OrderTable::getList([
            'filter' => ['ID_1C' => array_keys($arOrderFiles)],
            'select' => ['ID_1C', 'ID', 'USER_ID', 'CONTRAGENT', 'STATUS_ID'],
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField('CONTRAGENT',
                    '\Bitrix\Sale\Internals\OrderPropsValueTable',
                    array(
                        '=this.ID' => 'ref.ORDER_ID',
                        '=ref.CODE' => new \Bitrix\Main\DB\SqlExpression('?', "CONTRAGENT"),
                    ),
                    array(
                        "join_type" => 'inner'
                    )
                )
            ],
        ]);
        while ($arOrder = $resOrders->fetch()) {
            $arOrders[$arOrder['ID_1C']] = $arOrder;
            $arUsersIds[] = $arOrder['USER_ID'];
        }

        /**
         * список пользователей заказов
         */
        $arUsers = [];
        $resUsers = UserTable::getList([
            'filter' => ['ID' => $arUsersIds, 'ACTIVE' => 'Y', 'UF_ACTIVATE_PROFILE' => '1'],
            'select' => ['ID', 'ACTIVE', 'UF_ACTIVATE_PROFILE', 'EMAIL'],
        ]);
        while ($arUser = $resUsers->fetch()) {
            $arUser['IS_CONTRAGENT'] = (UserDataManager::isUserContragentByXmlId($arUser['ID'])) ? 'Y' : 'N';
            $arUsers[$arUser['ID']] = $arUser;
        }


        /**
         * тправляем письма о поступлении счета на оплату
         */
        foreach ($arOrders as $arOrder) {
            $arUser = $arUsers[$arOrder['USER_ID']];
            $files = $arOrderFiles[$arOrder['ID_1C']];


            /**
             * если пользователь заказа контрагент, ему письмо не отправляем
             * рассылаем письма всем пользователям привязанным к контрагенту
             */
            $emailsTo = [];
            if (!$arUser || $arUser['IS_CONTRAGENT'] == 'Y') {
                $arUsersContragent = UserDataManager::getUsersContragentByXmlId($arOrder['SALE_ORDER_CONTRAGENT_VALUE']);
                foreach ($arUsersContragent as $user) {
                    $emailsTo[$user['EMAIL']] = $user['EMAIL'];
                }

                /**
                 * если пользователь не коотрагент
                 * письма посылаем ему
                 */
            } else {
                $emailsTo[$arUser['EMAIL']] = $arUser['EMAIL'];
            }


            /**
             * отправляем письмо с оплатой
             */
            $arEventFields = [
                'ORDER_ID' => $arOrder['ID'],
                'EMAIL_TO' => implode(',', $emailsTo),
                "SALE_EMAIL" => \COption::GetOptionString("sale", "order_email"),
            ];
            /** @var $resEvent \Bitrix\Main\Entity\AddResult */
            $resEvent = \Bitrix\Main\Mail\Event::send([
                'EVENT_NAME' => self::EVENT_PAYMENT_INFO,
                'LID' => Core::DEFAULT_SITE_ID,
                'C_FIELDS' => $arEventFields
            ]);

            if ($resEvent->isSuccess()) {
                /**
                 * если успешно отправлено, у всех оплат ставим статус - отправлено
                 */
                foreach ($files as $item) {
                    $entity_data_class = \Citfact\Sitecore\Order\OrderFiles::getEntityClass();
                    $entity_data_class::update($item['ID'], [
                        'UF_SEND' => 'Y'
                    ]);
                }
            }


            if ($arOrder['STATUS_ID'] == OrderRepository::STATUS_ID_WAITING_CONFIRM) {
                $obOrder = Order::load($arOrder['ID']);
                $obOrder->setField('STATUS_ID', OrderRepository::STATUS_ID_ACCEPTED);
                $obOrder->save();
            }
        }
    }


    /**
     * Обработка уведомлений о появлении на складе товара из листа ожидания
     *
     * @param int $ID - id товара
     * @param array $arFields - обновленные поля товара
     * @return void
     */
    public static function checkNotificationsListWait($ID, &$arFields)
    {
        $res = \CIBlockElement::GetList([], ['ID' => $ID], false, [], ['ID', 'QUANTITY']);
        if ($arItem = $res->Fetch()) {
            $oldCount = $arItem['QUANTITY']; // старое кол-во товаров
        }

        // если товаров стало больше
        if ($oldCount < $arFields['QUANTITY']) {
            // находим записи с этим товаром в листе ожидания
            $hlblock = HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'ListWait']
            ])->fetch();
            if ($hlblock) {
                $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
                $rsData = $hlClassName::getList([
                    'filter' => ['UF_PRODUCT_ID' => $ID]
                ]);
                while ($arData = $rsData->Fetch()) {
                    // если только сейчас товара стало больше, чем в листе ожидания
                    if (($arData['UF_COUNT'] <= $arFields['QUANTITY']) && ($arData['UF_COUNT'] > $oldCount)) {
                        $hlClassName::update($arData['ID'], ['UF_VIEWED' => false]); // отметить как непросмотренное уведомление
                    }
                }
            }
        }
    }

    public static function log($arFields, $namePrintFileLog = "printLogs.log", $isInfo = true)
    {
        if (static::isLogEvent1CImport()) {
            printLogs($arFields, $namePrintFileLog, $isInfo);
        }
    }
 
    public static function isLogEvent1CImport()
    {
        $result = \COption::GetOptionString('citfact.sitecore', 'isLogEvent1CImport', 'N');
        if ($result == 'Y') {
            return true;
        }
        return false;

    }
}