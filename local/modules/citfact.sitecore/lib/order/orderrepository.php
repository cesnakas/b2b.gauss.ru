<?php

namespace Citfact\Sitecore\Order;

class OrderRepository
{
    const STATUS_ID_WAITING_CONFIRM = 'N'; // В обработке
    const STATUS_ID_ACCEPTED = 'P'; // Принят, ожидается оплата
    const STATUS_ID_PAYED = 'F'; // Выполнен

    const STATUS_ID_WAITING_PAYMENT = 'WP'; // not use real this project
    const STATUS_ID_EXPIRED = 'EX'; // not use real this project


    const DELIVERY_NOT_DELIVERY = 1; // без доставки
    const DELIVERY_PICKUP = 2; // самовывоз
    const DELIVERY_COURIER = 3; // курьер
    const DELIVERY_DELINDEV = 5; // деловые линии
    const DELIVERY_PEK = 20; // ПЭК
    const DELIVERY_MAIN_DELIVERY = 18; // Главдоставка
    const DELIVERY_OTHER = 10; // Другая транспортная компания

    const PROPERTIES_GROUP_VIEW = 1; // Свойства заказа для вывода
    const PROPERTIES_GROUP_NOT_VIEW = 2; // Скрытые свойства заказа

    const ORDER_EVENT_NEW_ORDER = 'SALE_NEW_ORDER'; // новый заказ
    const ORDER_EVENT_NEW_ORDER_MANAGER = 'SALE_NEW_ORDER_MANAGER'; // новый заказ менеджеру / асистенту
    const ORDER_EVENT_SALE_ORDER_CANCEL = 'SALE_ORDER_CANCEL'; // Отмена заказа
    const ORDER_EVENT_SALE_ORDER_CANCEL_MANAGER = 'SALE_ORDER_CANCEL_MANAGER'; // Отмена заказа
    const ORDER_EVENT_SALE_ORDER_DELIVERY = 'SALE_ORDER_DELIVERY'; // Доставка заказа разрешена
    const ORDER_EVENT_SALE_ORDER_PAID = 'SALE_ORDER_PAID'; // Заказ оплачен
    const ORDER_EVENT_SALE_ORDER_REMIND_PAYMENT = 'SALE_ORDER_REMIND_PAYMENT'; // Напоминание об оплате заказа

    const ORDER_EVENT_SALE_STATUS_CHANGED_F = 'SALE_STATUS_CHANGED_F'; // Изменение статуса заказа на "Выполнен
    const ORDER_EVENT_SALE_STATUS_CHANGED_N = 'SALE_STATUS_CHANGED_N'; // Изменение статуса заказа на "В обработке"
    const ORDER_EVENT_SALE_STATUS_CHANGED_P = 'SALE_STATUS_CHANGED_P'; // Изменение статуса заказа на "Принят, ожидается оплата"


    /**
     * @param $idDelivery
     * @param $codeProperty
     * @return bool
     */
    public static function isShowPropertyByDelivery($idDelivery, $codeProperty)
    {
        if (!$idDelivery || !$codeProperty) {
            return false;
        }

        if (in_array($idDelivery, [
            OrderRepository::DELIVERY_PEK,
            OrderRepository::DELIVERY_MAIN_DELIVERY,
            OrderRepository::DELIVERY_DELINDEV,
            OrderRepository::DELIVERY_OTHER,
        ])) {
            switch ($codeProperty) {
                case 'DATE_DELIVERY':
                case 'SHIP_ADDRESS_LIST':
                case 'NO_PRINT_PRICE':
                case 'PALLET_BOARD':
                case 'PALLET_REQUIRED':
                case 'COMPANY_NAME':
                case 'PHONE':
                case 'INN':
                case 'COMPANY_ADDRESS':
                case 'PHONE_COMPANY':
                case 'DELIVERY_PRICE':
                case 'CONSIGNEES':
                case 'CRATE':
                case 'UNLOADING':
                case 'DELIVERY_TO_DOOR':
                case 'DELIVERY':
                    return true;
                    break;
            }

        } elseif($idDelivery == OrderRepository::DELIVERY_PICKUP) {
            switch ($codeProperty) {
                case 'DATE_DELIVERY':
                case 'SHIP_ADDRESS_LIST':
                case 'COMPANY_NAME':
                case 'PHONE':
                case 'INN':
                case 'COMPANY_ADDRESS':
                case 'PHONE_COMPANY':
                case 'DELIVERY':
                return true;
                    break;
            }
        } elseif($idDelivery == OrderRepository::DELIVERY_COURIER) {
            switch ($codeProperty) {
                case 'DATE_DELIVERY':
                case 'SHIP_ADDRESS_LIST':
                case 'NO_PRINT_PRICE':
                case 'PALLET_BOARD':
                case 'PALLET_REQUIRED':
                case 'COMPANY_NAME':
                case 'PHONE':
                case 'INN':
                case 'COMPANY_ADDRESS':
                case 'PHONE_COMPANY':
                case 'DELIVERY_PRICE':
                case 'DELIVERY_TO_DOOR':
                case 'DELIVERY':
                return true;
                    break;
            }
        }

        return false;
    }
}