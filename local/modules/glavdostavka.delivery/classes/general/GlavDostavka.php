<?php

// Подключаем языковые файлы.
IncludeModuleLangFile(__FILE__);

/**
 * Class GlavDostavka
 * Определяет работу модуля доставки.
 * Класс реализован по документации 1С-Битрикс.
 * https://dev.1c-bitrix.ru/api_help/sale/delivery.php
 */
Class GlavDostavka
{
    static $cnt = 0;

    /**
     * @param int $cnt
     */
    public static function setCnt($cnt = 0)
    {
        static::$cnt = $cnt;
    }

    /**
     * Описание обработчика.
     * @return array
     */
    function Init()
    {
        return array(
            // Уникальный строковой идентификатор обработчика.
            'SID' => 'glavdostavka.delivery',

            // Название обработчика.
            'NAME' => 'Главдоставка API',

            // Текстовое описание обработчика.
            'DESCRIPTION' => 'Главдоставка API',

            // Внутреннее описание обработчика, отображаемое при конфигурации обработчика в Панели Управления.
            'DESCRIPTION_INNER' => 'Главдоставка API',

            // Идентификатор базовой валюты обработчика.
            'BASE_CURRENCY' => 'RUR',

            // Путь к файлу обработчика. Нужен для корректного автоматического копирования обработчика (ещё не реализовано).
            // В подавляющем большинстве случаев достаточно значения '__FILE__'.
            'HANDLER' => __FILE__,

            // Название метода, возвращающего массив настроек валидатора.
            // В случае реализации обработчика в виде класса, значение представляет собой массив ('имя_класса', 'имя_метода').
            'GETCONFIG' => array('GlavDostavka', 'GetConfig'),

            // Название метода, отвечающего за проверку настроек обработчика и преобразование массива настроек в строку для сохранения.
            // В случае реализации обработчика в виде класса, значение представляет собой массив ('имя_класса', 'имя_метода').
            // В случае отсутствия этого метода массив настроек будет сохранен в базу в сериализованном виде.
            'DBSETSETTINGS' => array('GlavDostavka', 'SetSettings'),

            // Название метода, отвечающего за обратное преобразование строки настроек обработчика в массив.
            // В случае реализации обработчика в виде класса, значение представляет собой массив ('имя_класса', 'имя_метода').
            'DBGETSETTINGS' => array('GlavDostavka', 'GetSettings'),

            // Название метода, отвечающего за дополнительную проверку совместимости профилей обработки с параметрами заказа.
            // Если метод отсутствует, дополнительная проверка не будет проводиться. В случае реализации обработчика в виде класса,
            // значение представляет собой массив ('имя_класса', 'имя_метода').
            'COMPABILITY' => array('GlavDostavka', 'Compability'),

            // Название метода, осуществляющего расчёт стоимости доставки. В случае реализации обработчика в виде класса,
            // значение представляет собой массив ('имя_класса', 'имя_метода').
            'CALCULATOR' => array('GlavDostavka', 'Calculate'),

            // Массив профилей обработки.
            'PROFILES' => array(
                // строковой_идентификато_профиля.
                'glavdostavka_default' => array(
                    // Название профиля.
                    'TITLE' => 'Главдоставка API',

                    // Описание профиля.
                    'DESCRIPTION' => 'Главдоставка API',

                    // Веса указываются в граммах (минимальный_вес, максимальный_вес).
                    'RESTRICTIONS_WEIGHT' => array(0),

                    // Суммы указываются в базовой валюте обработчика (минимальная_сумма_заказа, максимальная_сумма_заказа).
                    'RESTRICTIONS_SUM' => array(0)
                )
            )
        );
    }

    /**
     * Запрос конфигурации службы доставки.
     * @return array
     */
    function GetConfig()
    {
        $arCities = GlavDostavkaAPI::GetCities();

        $arSelect = [];
        foreach ($arCities as $arCity) {
            $arSelect[$arCity['id']] = $arCity['name'];
        }

        return array(
            'CONFIG_GROUPS' => array(
                'glavdostavka_delivery_settings' => 'Настройки доставки',
            ),

            'CONFIG' => array(
                'DEP_POINT' => array(
                    'TYPE' => 'DROPDOWN',
                    'TITLE' => 'Город отправления',
                    'DEFAULT' => '',
                    'GROUP' => 'glavdostavka_delivery_settings',
                    'SIZE' => '50',
                    'VALUES' => $arSelect
                ),
            ),
        );
    }

    /**
     * Установка параметров обработчика.
     * @param array $arSettings
     * @return string
     */
    function SetSettings($arSettings)
    {
        return serialize($arSettings);
    }

    /**
     * Запрос параметров обработчика.
     * @param string $strSettings
     * @return mixed
     */
    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    /**
     * Проверка соответствия профиля доставки заказу.
     *
     * @param $arOrder
     * @param $arConfig
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    function Compability($arOrder, $arConfig)
    {
        $profile_list = array();
        $profile_list[] = 'glavdostavka_default';

        return $profile_list;
    }

    /**
     * Рассчитываем итоговую стоимость и время доставки
     *
     * @param $profile
     * @param $arConfig
     * @param $arOrder
     * @param $STEP
     * @param bool $TEMP
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        /**
         * точно такие же правки внесены в доставки: деловые линии и ПЭК
         */
        if (static::$cnt > count($arOrder['ITEMS'])) {
            return array(
                'RESULT' => 'ERROR',
                'TEXT' => 'Not calculated force'
            );
        }


        $response = glavdostavkaAPI::Calculate($arOrder, $arConfig);

        if ($response['STATUS'] == 'OK') {
            return array(
                'RESULT' => 'OK',
                'VALUE' => $response['PRICE'],
                'TRANSIT' => $response['TIME']
            );
        }

        return array(
            'RESULT' => 'ERROR',
            'TEXT' => $response['TEXT']
        );
    }
}
