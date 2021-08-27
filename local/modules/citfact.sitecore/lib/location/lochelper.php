<?php
namespace Citfact\Sitecore\Location;


class LocHelper
{

    /**
     * @param $code
     * @param $lang
     * @return string
     */
    public static function getNameByCode($code, $lang = false)
    {
        if (!$code) {
            return '';
        }

        if (!$lang) {
            $lang = LANGUAGE_ID;
        }

        $res = \Bitrix\Sale\Location\LocationTable::getList(array(
            'filter' => array(
                '=NAME.LANGUAGE_ID' => $lang,
                '=CODE' => $code
            ),
            'select' => array('NAME_LANG' => 'NAME.NAME', 'CODE', 'ID')
        ));
        if ($ar = $res->fetch()) {
           return $ar['NAME_LANG'];
        }

        return '';
    }

    /**
     * @param $locationValue
     * @return bool
     * @throws \ReflectionException
     */
    public static function isTypeCity($locationValue)
    {
        $LocTypeManager = LocTypeManager::getInstance();
        $locTypeCity = $LocTypeManager->getByCode('CITY');
        return self::checkLocationValueByType($locationValue, $locTypeCity);
    }

    /**
     * @param $locationValue
     * @return bool
     * @throws \ReflectionException
     */
    public static function isTypeCountry($locationValue)
    {
        $LocTypeManager = LocTypeManager::getInstance();
        $locTypeCity = $LocTypeManager->getByCode('COUNTRY');
        return self::checkLocationValueByType($locationValue, $locTypeCity);
    }

    /***
     * @param $locationValue
     * @param $type
     * @return bool
     */
    public static function checkLocationValueByType($locationValue, $type)
    {
        $resCity = \Bitrix\Sale\Location\LocationTable::getList(array(
            'filter' => array(
                '=TYPE_ID' => $type,
                '=CODE' => $locationValue
            ),
            'select' => array('CODE', 'ID')
        ));
        if ($arCity = $resCity->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * @param $code
     * @return array
     */
    public static function getLocationByCode($code)
    {
        if (!$code) {
            return [];
        }

        $resCountry = \Bitrix\Sale\Location\LocationTable::getList(array(
            'filter' => array(
                '=NAME.LANGUAGE_ID' => LANGUAGE_ID,
                '=CODE' => $code,
            ),
            'select' => array('NAME_RU' => 'NAME.NAME', 'CODE', 'ID')
        ));
        if ($arCountry = $resCountry->fetch()) {
            return $arCountry;
        }

        return [];
    }

}
