<?php

use \Bitrix\Main\Web\Uri;
use \Bitrix\Main\Web\HttpClient;

// Подключаем языковые файлы.
IncludeModuleLangFile(__FILE__);

/**
 * Class DellinAPI
 * Класс предназначен для расчета стоимости доставки.
 */
class GlavDostavkaAPI
{
    /**
     * URL API для расчета стоимости перевозки.
     * @var string
     */
    protected static $calculator_url = 'https://glav-dostavka.ru/api/calc/';


    /**
     * URL API для списка городов
     * @var string
     */
    protected static $city_url = 'https://glav-dostavka.ru/api/calc/?responseFormat=json&method=api_city';


    /**
     * список городов
     * @return mixed
     */
    public static function GetCities()
    {
        $cache = new CPHPCache();
        $life_time = 60*60*24*30;
        $cache_id = __CLASS__ . __METHOD__;

        if ($cache->InitCache($life_time, $cache_id)) {
            $cache_data = $cache->GetVars();
            $result = $cache_data['VALUE'];

        } else {
            $http_client = new HttpClient();
            $http_client->setHeader('Content-Type', 'application/json', true);
            $result = json_decode($http_client->get(self::$city_url), true);

            $cache->StartDataCache($life_time, $cache_id);
            $cache->EndDataCache(array('VALUE' => $result));
        }

        return $result;
    }


    /**
     * @param $bx_location_to_id
     * @return string
     */
    public static function GetCityByCode($bx_location_to_id)
    {
        $kladr_code = '';

        $cache = new CPHPCache();
        $life_time = 24*60*60;
        $cache_id = __CLASS__ . __METHOD__.$bx_location_to_id;

        if ($cache->InitCache($life_time, $cache_id)) {
            $cache_data = $cache->GetVars();
            $kladr_code = $cache_data['VALUE'];

        } else {
            $db_vars = CSaleLocation::GetList(false, array("CODE" => $bx_location_to_id, "LID" => "ru"));
            $bx_location = $db_vars->Fetch();

            $arCities = self::GetCities();
            foreach ($arCities as $arCity) {
                if ($arCity['name'] == $bx_location['CITY_NAME']) {
                    $kladr_code = $arCity['id'];
                }
            }

            $cache->StartDataCache($life_time, $cache_id);
            $cache->EndDataCache(array('VALUE' => $kladr_code));
        }

        return $kladr_code;
    }


    /**
     * Расчет стоимости доставки.
     *
     * @param $arOrder
     * @param $arConfig
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function Calculate($arOrder, $arConfig)
    {
        $result = array('STATUS' => 'ERROR', 'TEXT' => 'При расчете произошла ошибка', 'PRICE' => 0, 'TIME' => '');

        $cityId = self::GetCityByCode($arOrder['LOCATION_TO']);
        if (!$cityId) {
            return $result;
//            $cityId = 35; // мск
        }


        $cntItems = 0;
        foreach ($arOrder['ITEMS'] as $ITEM) {
            $cntItems += $ITEM['QUANTITY'];
        }


        /**
         * $basket список элементов корзины
         */
        $basketLoad = \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            SITE_ID
        );
        $basket = $basketLoad->getOrderableItems();
        $basketItems = $basket->getBasketItems();

        /**
         * метод Calculate запускается для каждого элемнета корзины
         * считаем что в arOrder прилетело столько же элемнетов, сколько и в корзине и тогда запускаем расчет
         */
        if (count($arOrder['ITEMS']) < count($basketItems)) {
            return $result;
        }


        $VYSOTA = 0;
        $SHIRINA = 0;
        $DLINA = 0;
        $VES_BRUTTO = 0;
        foreach ($basketItems as $item) {
            /** @var $item \Bitrix\Sale\BasketItem */
            $VES_BRUTTO += $item->getWeight() * $item->getQuantity();
            $DIMENSIONS = unserialize($item->getField('DIMENSIONS'));

            $DLINA += $DIMENSIONS['LENGTH'] * $item->getQuantity();
            $SHIRINA += $DIMENSIONS['WIDTH'] * $item->getQuantity();
//            $VYSOTA += $DIMENSIONS['HEIGHT'] * $item->getQuantity();

            /**
             * возьмем масую высокую высоту. не будем их складывать
             */
            if ($VYSOTA < $DIMENSIONS['HEIGHT']) {
                $VYSOTA = $DIMENSIONS['HEIGHT'];
            }
        }


        $VYSOTA = $VYSOTA / 1000; // измеряется в мм, нужно в метрах
        $SHIRINA = $SHIRINA / 1000; // измеряется в мм, нужно в метрах
        $DLINA = $DLINA / 1000; // измеряется в мм, нужно в метрах
        $VES_BRUTTO = $VES_BRUTTO / 1000; // кг

        $arData = [
            // метод
            'method' => 'api_calc',

            // формат возвращаемых данных
            'responseFormat' => 'json',

            // Город отправления
            'depPoint' => $arConfig['DEP_POINT']['VALUE'],

            // Город назначения
            'arrPoint' => $cityId,

            // Вес (кг)
            'cargoKg' => [
                $VES_BRUTTO
            ],

            // Кол-во мест
            'cargoMest' => [
                $cntItems
            ],

            // Длина груза (м)
            'cargoL' => [
                $DLINA
            ],

            // Ширина груза (м)
            'cargoW' => [
                $SHIRINA
            ],

            // Высота груза (м)
            'cargoH' => [
                $VYSOTA
            ],

            // 0 — вес, длина, ширина и высота указаны для всех мест.
            // 1 — вес, длина, ширина и высота указаны для одного места, общие габариты рассчитываются перемножением количества мест на габариты одного места.
            'cargoCalculation' => [
                '0'
            ],
        ];


        $cache = new CPHPCache();
        $life_time = 10*60;
        $cache_id = __CLASS__ . __METHOD__ . md5(serialize($arData));

        if ($cache->InitCache($life_time, $cache_id)) {
            $cache_data = $cache->GetVars();
            $result = $cache_data['VALUE'];

        } else {
            $urlCalculate = self::$calculator_url . '?' . http_build_query($arData);

            $http_client = new HttpClient();
            $http_client->setHeader('Content-Type', 'application/json', true);
            $response = json_decode($http_client->get($urlCalculate),true);
            if ($response['status'] == 'OK') {
                $result['STATUS'] = 'OK';
                $result['TEXT'] = '';
                $result['PRICE'] = $response['price'];
            }

            $cache->StartDataCache($life_time, $cache_id);
            $cache->EndDataCache(array('VALUE' => $result));
        }

        return $result;
    }
}
