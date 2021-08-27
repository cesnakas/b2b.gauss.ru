<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Sale\Order;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\Order\OrderRepository;
use Citfact\Sitecore\Order\OrderManager;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\UserDataManager\UserDataManager;

Loc::loadMessages(__FILE__);

class OrderSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            array('module' => 'main', 'event' => 'OnBeforeEventAdd', 'sort' => 100, 'method' => 'offEventOrderByContragent'),
            array('module' => 'sale', 'event' => 'OnSaleOrderSaved', 'sort' => 100, 'method' => 'newMailOnOrderUpdate'),
            array('module' => 'main', 'event' => 'OnBeforeEventAdd', 'sort' => 100, 'method' => 'disableEventSendHandler'),
            array('module' => 'main', 'event' => 'OnBeforeEventSend', 'sort' => 100, 'method' => 'cancelOrderEventAddHandler'),
            array('module' => 'main', 'event' => 'OnBeforeEventSend', 'sort' => 100, 'method' => 'newOrderEventSendHandler'),
            array('module' => 'main', 'event' => 'OnBeforeEventAdd', 'sort' => 100, 'method' => 'orderChangeNumberHandler'),
        );
    }


    /**
     * @param $event
     * @param $lid
     * @param $arFields
     * @param $message_id
     * @param $files
     * @param $languageId
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function offEventOrderByContragent(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId)
    {
        /**
         * когда заказ создан из 1С
         * пользователь является контрагентов
         * ему не надо отправлять письмо
         */
        if (
            UserDataManager::isUserContragentByEmail($arFields['EMAIL'])
            && in_array($event, [
                OrderRepository::ORDER_EVENT_NEW_ORDER,
                OrderRepository::ORDER_EVENT_SALE_ORDER_CANCEL,
                OrderRepository::ORDER_EVENT_SALE_ORDER_DELIVERY,
                OrderRepository::ORDER_EVENT_SALE_ORDER_PAID,
                OrderRepository::ORDER_EVENT_SALE_STATUS_CHANGED_F,
                OrderRepository::ORDER_EVENT_SALE_STATUS_CHANGED_N,
            ])
        ) {
            return false;
        }
    }

    function orderChangeNumberHandler(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId){
        /**
            Заменяет ID заказа на номер из 1с
         */
        if (in_array($event, [
                OrderRepository::ORDER_EVENT_SALE_STATUS_CHANGED_P,
                OrderRepository::ORDER_EVENT_SALE_ORDER_CANCEL,
                OrderRepository::ORDER_EVENT_SALE_ORDER_DELIVERY,
                OrderRepository::ORDER_EVENT_SALE_ORDER_PAID,
                OrderRepository::ORDER_EVENT_SALE_STATUS_CHANGED_F,
                OrderRepository::ORDER_EVENT_SALE_STATUS_CHANGED_N,
            ])) {
            $orderManager = new OrderManager();
            $number1c = $orderManager->get1CNumber($arFields['ORDER_ID']);
            $arFields['ORDER_ID'] = $number1c;
        }
        return $arFields;
    }
    /**
     * менеджерам и ассистентам отправляем письма о новом заказе, если произошел первый обмен с 1С на портал
     *
     * @param $id
     * @param $arFields
     * @return bool|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */

    function newMailOnOrderUpdate(\Bitrix\Main\Event $event)
    {
		 /** @var Order $order */
    	$order = $event->getParameter("ENTITY");
        $orderDB = $order->getFields();
        if(empty($orderDB['ID'])){
            return;
        }
        $propertyCollection = $order->getPropertyCollection();
        $arProperties = $propertyCollection->getArray();
        $orderManager = new OrderManager();
        $number1C = $orderManager->get1CNumber($orderDB['ID']);

        if (!empty($orderDB['ID_1C']) && !empty($number1C)) {
            foreach ($arProperties['properties'] as $property) {

                if ($property['CODE'] == 'UNLOADING') {
                    if ($property['VALUE'][0] == 'Y') {
                        return;
                    } else {
                        $propertyCollection->getItemByOrderPropertyId($property['ID'])->setField('VALUE', 'Y');
                        $order->save();
                        break;
                    }
                }
            }
            $arFieldsMail = [];

            $arFieldsMail['1C_NUMBER'] = $number1C;
            $userInfo = \CUser::GetByID($orderDB['USER_ID'])->fetch();
            $arFieldsMail['ORDER_USER'] = $userInfo['LAST_NAME'] . ' ' . $userInfo['NAME'] . $userInfo['LAST'] . ' ' . $userInfo['SECOND_NAME'];

            $arFieldsMail['ORDER_DATE'] = $orderDB['DATE_INSERT'];

            $arFieldsMail['PRICE'] = $orderDB['PRICE'] . '₽';

            $arFieldsMail['ORDER_REAL_ID'] = $orderDB['ID'];
            $arFieldsMail['ORDER_ID'] = $orderDB['ID'];

            $arFieldsMail['ID_1C'] = $orderDB['ID_1C'];

            $xmlContragent = '';
            foreach ($arProperties['properties'] as $property) {
                $value = (is_array($property['VALUE'])) ? implode(', ', $property['VALUE']) : $property['VALUE'];
                if ($property['CODE'] == 'CONTRAGENT') {
                    $xmlContragent = $value;
                }
            }

            if (!$xmlContragent) {
                return;
            }


            $arFieldsMail['CONTRAGENT_NAME'] = UserDataManager::getContrAgentInfo($xmlContragent)['UF_NAME'];

            $userManager = \Citfact\SiteCore\User\UserManagers::getManagerByContragent($xmlContragent);
            $userAssistants = \Citfact\SiteCore\User\UserManagers::getAssistantsByContragent($xmlContragent);

            $emails = [];
            foreach ($userAssistants as $userAssistant) {
                if (!empty($userAssistant['EMAIL'])) {
                    $emails[] = trim($userAssistant['EMAIL']);
                }
            }

            $arFieldsMail['EMAIL_MANAGER'] = implode(',', $emails);
            if (empty($arFieldsMail['EMAIL_MANAGER'])) {
                return;
            }

            if ($userManager['EMAIL']) {
                $arFieldsMail['MANAGER_NAME'] = \Citfact\SiteCore\User\UserHelper::getFullNameByUser($userManager);
            }

            \CEvent::Send(OrderRepository::ORDER_EVENT_NEW_ORDER_MANAGER, Core::DEFAULT_SITE_ID, $arFieldsMail, "Y", [], 'ru');

        }

    }

    /**
     * Отменяет отправку писем по заказу, если заказ формировался на стороне 1с
     *
     * @param $event
     * @param $lid
     * @param $arFields
     * @param $message_id
     * @param $files
     * @param $languageId
     * @return bool|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function disableEventSendHandler(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId)
    {
        if (
            $event == 'SALE_NEW_ORDER' ||
            strpos($event, 'SALE_STATUS_CHANGED_') !== false
        ) {
            $order = Order::load($arFields['ORDER_REAL_ID']);
            $propertyCollection = $order->getPropertyCollection();
            $arProperties = $propertyCollection->getArray();
            foreach ($arProperties['properties'] as $property) {
                if ($property['CODE'] == 'OFFLINE' && $property['VALUE'] == 'Y') {
                    unset($event);
                    unset($arFields);
                    unset($lid);
                    return false;
                }
            }
        }
    }

    /**
     * об отмене заказа отправляем письмо менеджеру/асистенту
     *
     * @param $arFields
     * @param $arTemplate
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function cancelOrderEventAddHandler(&$arFields, &$arTemplate)
    {
        if ($arTemplate['EVENT_NAME'] != OrderRepository::ORDER_EVENT_SALE_ORDER_CANCEL || !$arFields['ORDER_REAL_ID']) {
            return;
        }

        $order = Order::load($arFields['ORDER_REAL_ID']);
        $propertyCollection = $order->getPropertyCollection();
        $arProperties = $propertyCollection->getArray();

        $xmlContragent = '';
        foreach ($arProperties['properties'] as $property) {
            $value = (is_array($property['VALUE'])) ? implode(', ', $property['VALUE']) : $property['VALUE'];
            if ($property['CODE'] == 'CONTRAGENT') {
                $xmlContragent = $value;
            }
        }

        if (!$xmlContragent) {
            return;
        }

        $userAssistants = \Citfact\SiteCore\User\UserManagers::getAssistantsByContragent($xmlContragent);

        $emails = [];
        foreach ($userAssistants as $userAssistant) {
            if (!empty($userAssistant['EMAIL'])) {
                $emails[] = $userAssistant['EMAIL'];
            }
        }

        $arFields['EMAIL_MANAGER'] = implode(',', $emails);
        $arTemplate["MESSAGE"] = str_replace('#EMAIL_MANAGER#', $arFields['EMAIL_MANAGER'], $arTemplate["MESSAGE"]);
    }

    /**
     * @param $arFields
     * @param $arTemplate
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function newOrderEventSendHandler(&$arFields, &$arTemplate)
    {
        if (
            $arTemplate['EVENT_NAME'] != OrderRepository::ORDER_EVENT_NEW_ORDER
            && $arTemplate['EVENT_NAME'] != OrderRepository::ORDER_EVENT_NEW_ORDER_MANAGER
            || !$arFields['ORDER_REAL_ID']
        ) {
            return;
        }
        $order = Order::load($arFields['ORDER_REAL_ID']);

        /**
         * форматированные оплаты, свойства и корзины для письма
         */
        $arFields['PROPERTIES'] = self::getFormatPropertiesByOrder($order);
        $arFields['BASKET'] = self::getFormatBasketByOrder($order);

        $arTemplate["MESSAGE"] = str_replace('#PROPERTIES#', $arFields['PROPERTIES'], $arTemplate["MESSAGE"]); //подставляем значения в шаблон;
        $arTemplate["MESSAGE"] = str_replace('#BASKET#', $arFields['BASKET'], $arTemplate["MESSAGE"]); //подставляем значения в шаблон;
    }


    /**
     * достаем отформатированную корзину заказа
     *
     * @param Order $order
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    protected static function getFormatBasketByOrder(Order $order)
    {
        $basket = $order->getBasket();
        $sid = $order->getSiteId();
        $productItems = \Citfact\Sitecore\Order\Basket::getProductItemsByBasket($basket, Core::DEFAULT_CURRENCY, $sid);

        global $APPLICATION;
        $templateBasket = $APPLICATION->IncludeComponent(
            "citfact:mail.buffer.template",
            "basket",
            array(
                "ITEMS" => $productItems,
                "SITE_ID" => $sid,
            )
        );

        return $templateBasket;
    }


    /**
     * Из заказа достаем свойства для письма (включая комментарий)
     *
     * @param Order $order
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected static function getFormatPropertiesByOrder(Order $order)
    {
        /**
         * order properties
         */
        $propertyCollection = $order->getPropertyCollection();
        $arPropertiesCollection = $propertyCollection->getArray();

        /**
         * @var $strProperties // строка свойств заказа для письма
         * @var $arProperties // массив свойств заказа для письма
         */
        $arProperties = [];
        $propDelivTime1 = '';
        $propDelivTime2 = '';
        $key = 0;
        $keyDeliveryDate = 0;
        $idDelivery = 0;
        foreach ($arPropertiesCollection['properties'] as $property) {
            $value = (is_array($property['VALUE'])) ? implode(', ', $property['VALUE']) : $property['VALUE'];

            $key++;
            switch ($property['CODE']) {
                case 'DELIVERY_TIME_1':
                    $propDelivTime1 = $value;
                    break;
                case 'DELIVERY_TIME_2':
                    $propDelivTime2 = $value;
                    break;

                case 'DATE_DELIVERY':
                    $keyDeliveryDate = $key;
                    break;

                case 'DELIVERY_ID':
                    $idDelivery = $value;
                    break;
            }
        }


        $key = 0;
        foreach ($arPropertiesCollection['properties'] as $property) {
            $key++;
            $name = $property['NAME'];
            $value = (is_array($property['VALUE'])) ? implode(', ', $property['VALUE']) : $property['VALUE'];

            if ($property['PROPS_GROUP_ID'] != OrderRepository::PROPERTIES_GROUP_VIEW) {
                continue;
            }

            if ($property['TYPE'] == 'Y/N') {
                if ($value == 'Y') {
                    $value = 'да';
                } else {
                    $value = 'нет';
                }
            }

            /**
             * свойства в зависимости от выбранной службы доставки
             */
            if (OrderRepository::isShowPropertyByDelivery($idDelivery, $property['CODE'])) {
                if ($value) {
                    $arProperties[$key] = $name . ': ' . $value;
                }
            }
        }

        /**
         * время С / ПО
         */
        $strPropTime = '';
        if ($propDelivTime1) {
            $strPropTime .= 'с ' . $propDelivTime1 . ' ';
        }
        if ($propDelivTime2) {
            $strPropTime .= 'по ' . $propDelivTime2;
        }
        if ($strPropTime) {
            if ($idDelivery == OrderRepository::DELIVERY_PEK) {
                $arProperties[$keyDeliveryDate + 1] = 'Время доставки: ' . $strPropTime;
            }
        }
        ksort($arProperties);

        /**
         * комментарий
         */
        $comment = $order->getField('USER_DESCRIPTION');
        if ($comment) {
            $arProperties[] = 'Комментарий: ' . $comment;
        }
        $strProperties = implode('<br>', $arProperties);

        return $strProperties;
    }


    /**
     * @param Order $order
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     */
    protected static function getFormatDeliveriesByOrder(Order $order)
    {
        /**
         * @var $shipmentCollection \Bitrix\Sale\ShipmentCollection
         */
        $arDeliveries = [];
        $shipmentCollection = $order->getShipmentCollection();
        foreach ($shipmentCollection as $shipment) {
            /**
             * @var \Bitrix\Sale\Shipment $shipment
             */
            if ($shipment->isSystem()) {
                continue;
            }

            $delivery = $shipment->getDelivery();
            $arDeliveries[] = $delivery->getName();
        }

        return implode(", ", $arDeliveries);
    }
}
