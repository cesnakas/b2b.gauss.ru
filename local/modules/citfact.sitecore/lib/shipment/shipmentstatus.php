<?php
namespace Citfact\SiteCore\Shipment;

class ShipmentStatus
{
    const SHIPPED = 'Отгружен';
    const NOT_SHIPPED = 'Не отгружен';
    const CREATED = 'Создан';
    const NOT_AVAILABLE = 'Нет в наличии';

    const COLOR_SHIPPED = 'green';
    const COLOR_NOT_SHIPPED = 'yellow';
    const COLOR_CREATED = 'grey';

    /**
     * @param $status
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getColor($status)
    {
        switch ($status) {
            case self::SHIPPED:
                $color = self::COLOR_SHIPPED;
                break;
            case self::NOT_SHIPPED:
                $color = self::COLOR_NOT_SHIPPED;
                break;
            case self::CREATED:
                $color = self::COLOR_CREATED;
                break;
            default:
                $color = '';
                break;
        }

        return $color;
    }
}