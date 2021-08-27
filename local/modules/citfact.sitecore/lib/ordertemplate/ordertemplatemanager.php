<?php

namespace Citfact\SiteCore\OrderTemplate;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Citfact\SiteCore\CatalogHelper\BasketRepository;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\Notification\NotificationManager;
use Citfact\Sitecore\Order\Basket;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\CatalogHelper\ElementRepository;
use Citfact\Sitecore\Manufacturer\HlManufacturerManager;
use Citfact\SiteCore\User\UserRepository;
use const FILTER_SANITIZE_NUMBER_FLOAT;

class OrderTemplateManager
{
    protected $entity;
    public $userId;
    public $cache;
    public $cacheId;
    public $cacheTime = 3600;

    public function __construct($userId = "")
    {
        $this->userId = ((int)$userId) ?:$GLOBALS["USER"]->GetID();
        $HLBlock = new HLBlock();
        $this->entity = $HLBlock->getHlEntityByName(Core::HLBLOCK_CODE_ORDER_TEMPLATES);
        $this->cache = Application::getInstance()->getManagedCache();
        $this->cacheId = 'orderTemplate_'.$this->userId;
    }

    /**
     * Возвращает все шаблоны пользователя
     *
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getTemplates()
    {

        if ($this->cache->read($this->cacheTime, $this->cacheId)) {
            $arData = $this->cache->get($this->cacheId);
        } else {
            $entity = $this->entity;
            $res = $entity::getList([
                'select' => ['*'],
                'filter' => ['UF_USER' => $this->userId],
                'order' => ["ID" => "DESC"],
            ]);

            $arData = [];
            while ($row = $res->Fetch()) {
                $arData[$row['ID']] = $row;
            }

            $this->cache->set($this->cacheId, $arData);
        }

        return $arData;
    }

    /**
     * Приводит массив товаров шаблона к виду ['PRODUCT_ID' => 'quantity']
     *
     * @param array $items
     * @return array
     */
    private function rebuildArrayOfProducts(array $items)
    {
        $arResult = [];
        foreach ($items as $key => $item) {
            $arResult[$item['PRODUCT_ID']] = $item['QUANTITY'];
        }
        return $arResult;
    }

    /**
     * Существует ли хотя бы один шаблон, в котором набор товаров совпадает с переданным в параметры
     *
     * @param $arParams array Массив товаров в json для добавления в шаблон
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function isTemplateExist($arParams)
    {
        // получить все шаблоны данного пользователя
        $arTemplates = $this->getTemplates();

        if (empty($arTemplates)) {
            return false;
        }
        // обработать массив товаров
        $arAddedProducts = json_decode($arParams['UF_PRODUCTS'], $assoc = true);
        $arAddedProducts = $this->rebuildArrayOfProducts($arAddedProducts);

        // если состав товаров совпадает с таковым из какого-либо шаблона - возвращаем true, иначе false
        foreach ($arTemplates as $tplId => $arTemplate) {
            $tplProducts = $this->rebuildArrayOfProducts(json_decode($arTemplate['UF_PRODUCTS'], $assoc = true));
            if ($arAddedProducts == $tplProducts) {
                return true;
            }
        }

        return false;
    }

    /**
     * Добавляет шаблон
     * @param $arParams
     * @throws \Exception
     */
    public function addTemplate($arParams)
    {
        // проверить что присутствуют обязательные ключи
        $arNeedFields = [
            'UF_TIMESTAMP',
            'UF_NAME',
            'UF_PRODUCTS',
            'UF_USER',
        ];

        foreach ($arNeedFields as $needKey) {
            if (!isset($arParams[$needKey])) {
                throw new \Exception('Missing key '.$needKey);
            }
        }

        // проверить что нет шаблонов с таким же набором товаров
//        if ($this->isTemplateExist($arParams)) {
//            throw new \Exception('This template already exist', 400);
//        }


        $entity = $this->entity;
        $res = $entity::add($arParams);

        if ($res->isSuccess()) {
            $this->cache->clean($this->cacheId);
        } else {
            throw new \Exception(implode(' ,', $res->getErrorMessages()));
        }
    }

    /**
     * Возвращает шаблон по ID
     *
     * @param $tplId
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getTemplate($tplId)
    {
        $arData = $this->getTemplates();
        return $arData[$tplId];
    }

    /**
     * Удалаяет шабон по ID
     *
     * @param $tplId
     * @throws \Exception
     */
    public function delete($tplId)
    {
        $entity = $this->entity;
        $res = $entity::delete($tplId);

        if ($res->isSuccess()) {
            $this->cache->clean($this->cacheId);
        } else {
            throw new \Exception(implode(' ,', $res->getErrorMessages()));
        }
    }

    /**
     * Переименовывает шаблон
     *
     * @param $tplId
     * @param $newName
     * @return mixed
     * @throws \Exception
     */
    public function rename($tplId, $newName)
    {
        $name = trim(strip_tags($newName));
        if (!$name) {
            throw new \Exception('Incorrect param newName');
        }

        $entity = $this->entity;
        $res = $entity::update(
            $tplId,
            ["UF_NAME" => $name]
        );

        if ($res->isSuccess()) {
            $this->cache->clean($this->cacheId);
            return $res->getId();
        } else {
            throw new \Exception(implode(' ,', $res->getErrorMessages()));
        }
    }

    /**
     * Update template
     *
     * @param $tplId
     * @param string $products
     * @return mixed
     */
    public function updateTemplate($tplId, $products)
    {
        $entity = $this->entity;
        $res = $entity::update(
            $tplId,
            ["UF_PRODUCTS" => $products]
        );

        if ($res->isSuccess()) {
            $this->cache->clean($this->cacheId);
            return $res->getId();
        }

        return null;
    }

    /**
     * Возвращает массив с датами напоминаний (ключи - timestamp от даты)
     *
     * @param $tplId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getReminders($tplId)
    {
        $arTpl = $this->getTemplate($tplId);

        $arReminders = [];
        if (!empty($arTpl["UF_DATE_NOTIFY"])) {
            foreach ($arTpl["UF_DATE_NOTIFY"] as $arReminder) {
                $arReminders[(string)$arReminder->getTimeStamp()] = $arReminder;
            }

            ksort($arReminders);
        }

        return $arReminders;
    }

    /**
     * Добавляет напоминание
     *
     * @param $tplId
     * @param $newDateStr
     * @return bool
     * @throws \Exception
     */
    public function addReminder($tplId, $newDateStr)
    {
        if (!($newDateTimeStamp = strtotime($newDateStr))) {
            throw new \Exception('Incorrect param newDateStr');
        }

        $newDateTime = DateTime::createFromTimestamp($newDateTimeStamp);
        $arReminders = $this->getReminders($tplId);

        if (isset($arReminders[$newDateTimeStamp])) {
            return false; // уже есть такая дата напоминания
        }

        $arReminders[$newDateTimeStamp] = $newDateTime;
        $entity = $this->entity;
        $res = $entity::update($tplId, [
           'UF_DATE_NOTIFY' => $arReminders
        ]);

        if ($res->isSuccess()) {
            $this->cache->clean($this->cacheId);
            return $newDateTimeStamp;
        } else {
            throw new \Exception(implode(' ,', $res->getErrorMessages()));
        }
    }

    /**
     * Удалаяет напоминание
     *
     * @param $tplId
     * @param $timestamp
     * @return bool
     * @throws \Exception
     */
    public function deleteReminder($tplId, $timestamp)
    {
        $arReminders = $this->getReminders($tplId);

        if (!isset($arReminders[$timestamp])) {
            return false; // нет такой даты напоминания
        }

        unset($arReminders[$timestamp]);

        $entity = $this->entity;
        $res = $entity::update($tplId, [
            'UF_DATE_NOTIFY' => $arReminders
        ]);

        if ($res->isSuccess()) {
            $this->cache->clean($this->cacheId);
            return $timestamp;
        } else {
            throw new \Exception(implode(' ,', $res->getErrorMessages()));
        }
    }

    /**
     * Disable saved current carts
     *
     * @return boolean
     */
    public function disableCurrentCarts()
    {

        $entity = $this->entity;
        $res = $entity::getList([
            'select' => ['ID'],
            'filter' => ['UF_USER' => $this->userId, 'UF_IS_CURRENT' => 1],
            'order' => ["UF_TIMESTAMP" => "DESC"],
        ]);

        while ($row = $res->fetch()) {

            $savedCartObject = $entity::update(
                $row['ID'],
                ['UF_IS_CURRENT' => 0]
            );

            if ($savedCartObject->isSuccess()) {
                $this->cache->clean($this->cacheId);
            }
        }

        return null;

    }

    /**
     * Добавляет товары из шаблона в корзину
     *
     * @param $tplId
     * @throws \Exception
     */
    public function createOrderByTemplate($tplId)
    {
        Loader::includeModule("catalog");

        $arProducts = $this->getProducts($tplId);
        if (!empty($arProducts)) {

            $res = Basket::addProducts($arProducts, ['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom']);

            if (!$res->isSuccess()) {
                throw new \Exception(implode(' ,', $res->getErrorMessages()));
            }
        }
    }

    /**
     * Возвращает массив товаров шаблона
     *
     * @param $tplId
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getProducts($tplId)
    {
        $arTpl = $this->getTemplate($tplId);
        if (!empty($arProducts = $arTpl['UF_PRODUCTS'])) {
            $arProducts = json_decode(str_replace("'", '"', $arTpl['UF_PRODUCTS']), $assoc = true);
            return $arProducts;
        }
        return [];
    }

    /**
     * Возвращает массив с дополнительными данными о товарах
     *
     * @param $tplId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \ReflectionException
     */
    public function getProductsData($tplId)
    {
        $arProducts = $this->getProducts($tplId);
        $arProductsData = [];

        if (!empty($arProducts)) {

            foreach ($arProducts as $arProduct) {
                $arProductsData[$arProduct['PRODUCT_ID']] = $arProduct;
            }

            $arProductIds = array_keys($arProductsData);
            $core = \Citfact\SiteCore\Core::getInstance();
            $iblockId =  $core->getIblockId($core::IBLOCK_CODE_CATALOG);

            $arSelect = [
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
                'DETAIL_PAGE_URL',
                'NAME',
                'PROPERTY_CML2_ARTICLE',
                'PROPERTY_KOLICHESTVO_V_UPAKOVKE',
                'XML_ID',
                'CATALOG_QUANTITY'
            ];
            $arFilter = [
                "IBLOCK_ID" => $iblockId,
                "ID" => $arProductIds
            ];

            $ob = \CIBlockElement::GetList(
                [],
                $arFilter,
                false,
                false,
                $arSelect
            );

            while ($res = $ob->GetNext()) {
                $arProductsData[$res["ID"]]["PREVIEW_PICTURE"] = $res['PREVIEW_PICTURE'];
                $arProductsData[$res["ID"]]["DETAIL_PICTURE"] = $res['DETAIL_PICTURE'];
                $arProductsData[$res["ID"]]["DETAIL_PAGE_URL"] = $res['DETAIL_PAGE_URL'];
                $arProductsData[$res["ID"]]["NAME"] = $res['NAME'];
                $arProductsData[$res["ID"]]["XML_ID"] = $res['XML_ID'];
                $arProductsData[$res["ID"]]["CATALOG_QUANTITY"] = $res['CATALOG_QUANTITY'];
                $arProductsData[$res["ID"]]["ART_NUMBER"] = $res['PROPERTY_CML2_ARTICLE_VALUE'];
                $arProductsData[$res["ID"]]["KOLICHESTVO_V_UPAKOVKE"] = $res['PROPERTY_KOLICHESTVO_V_UPAKOVKE_VALUE'];
                $arProductsData[$res["ID"]]["SHOW_MEASURE_TEXT"] = $core::IBLOCK_CODE_UPAKOVKA_MEASURE_ID != $res['CATALOG_MEASURE'];
                if (!empty($arPrice = Price::getWithoutDiscountPrices($res["ID"]))) {
                    $arProductsData[$res["ID"]]["PRICE"] = ElementRepository::formatFullPrice(
                        $arPrice['PRICE'],
                        $arPrice['CURRENCY']
                    );
                }

                $arProductsData[$res["ID"]]['SUM'] = ElementRepository::formatFullPrice(
                    ($arPrice['PRICE'] * $arProductsData[$res["ID"]]['QUANTITY']),
                    $arPrice['CURRENCY']
                );

                $arProductsData[$res['ID']]['ORIGIN_PRICE'] = $arPrice['PRICE'];
                $arProductsData[$res['ID']]['ORIGIN_SUM'] = $arPrice['PRICE'] * $arProductsData[$res["ID"]]['QUANTITY'];
            }
        }

        return $arProductsData;
    }

    /**
     * Формирует массив для поиска по товарам (названию и артикулу)
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \ReflectionException
     */
    public function getProductDataToSearch()
    {
        $arTemplates = $this->getTemplates();
        $arTpls = [];
        foreach ($arTemplates as $arTemplate) {
            $arTpls[$arTemplate["ID"]] = $this->getProductsData($arTemplate["ID"]);
        }
        $arResult = [];
        if (!empty($arTpls)) {
            foreach ($arTpls as $tplId => $arTpl) {
                foreach($arTpl as $arItem) {
                    $arResult['NAMES'][$arItem['NAME']][$tplId] = $tplId;
                    $arResult['ART_NUMBERS'][$arItem['ART_NUMBER']][$tplId] = $tplId;
                }
            }
        }
        return $arResult;
    }

    /**
     * Возвращает ID шаблонов, в которых есть товары с названием или артикулом
     *
     * @param $searchStr
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \ReflectionException
     */
    public function searchTemplates($searchStr)
    {
        $arDataToSearch = $this->getProductDataToSearch();
        $arNames = array_keys($arDataToSearch["NAMES"]);
        $arArtNumbers = array_keys($arDataToSearch["ART_NUMBERS"]);
        $arResult = [];
        foreach ($arNames as $name) {
            if (stripos($name, $searchStr) === 0) {
                $arResult = array_unique(array_merge($arResult, $arDataToSearch["NAMES"][$name]));
            }
        }

        foreach ($arArtNumbers as $artNumber) {
            if (stripos($artNumber, $searchStr) === 0) {
                $arResult = array_unique(array_merge($arResult, $arDataToSearch["ART_NUMBERS"][$artNumber]));
            }
        }

        return $arResult;
    }


    /**
     * Рассылка напоминаний, установленных на текущую дату
     */
    public function SendReminder()
    {
        $entity = $this->entity;
        $dateNow = new \DateTime(date('Y-m-d'));
        $res = $entity::getList([
            'select' => ['*'],
            'filter' => [
                'UF_REMINDER_SENT' => false,
                '<=UF_DATE_NOTIFY' => DateTime::createFromPhp($dateNow),
                //'=UF_USER' => 229,
            ],
            'order' => ["ID" => "DESC"],
        ]);

        $arData = [];
        $arUsers = [];
        while ($row = $res->Fetch()) {
            $arData[$row['ID']] = $row;
            $arUsers[] = $row['UF_USER'];
        }

        if (empty($arData)){
            return;
        }

        $arUsers = array_unique($arUsers);

        // Получаем способы уведомлений для пользователей
        // Если способ не заполнен, считаем, что это email
        $arMethods = [];
        $notificationManager = new NotificationManager();
        foreach($arUsers as $userId){
            $arMethods[$userId] = $notificationManager->getOrderTemplatesReminderMethod($userId);
        }

        global $SMS4B;
        foreach ($arData as $arTemplate){
            $userId = $arTemplate['UF_USER'];
            $method = $arMethods[$userId];

            if ($method == 'sms'){
                $phone = $notificationManager->getSubPhone($userId);
                if ($phone) {
                    if ($SMS4B) {
                        $SMS4B->SendSMS(Loc::getMessage('ORDER_TEMPLATE_REMINDER_SMS', [
                            'TEMPLATE_NAME' => $arTemplate['UF_NAME'],
                        ]), $phone);
                    }
                }
            }

            if ($method == 'email'){
                $email = UserRepository::getUserEmail($userId);
                $arEventFields = array(
                    'TEMPLATE_NAME' => $arTemplate['UF_NAME'],
                    'EMAIL' => $email,
                );
                \CEvent::Send('ORDER_TEMPLATE_REMINDER', Core::DEFAULT_SITE_ID, $arEventFields);
            }


            // Удаляем напоминание, которое сработало
            $arReminders = $arTemplate['UF_DATE_NOTIFY'];

            /** @var \Bitrix\Main\Type\DateTime $reminder */
            foreach ($arReminders as $key => $reminder) {
                if ($reminder->getTimestamp() <= $dateNow->getTimestamp()){
                    unset($arReminders[$key]);
                }
            }

            $entity = $this->entity;
            $res = $entity::update($arTemplate['ID'], [
                'UF_DATE_NOTIFY' => $arReminders
            ]);

            if ($res->isSuccess()) {
                // Сбрасываем тегированный кэш напоминаний для данного пользователя
                $this->cache->clean('orderTemplate_'.$userId);
            } else {
                throw new \Exception(implode(' ,', $res->getErrorMessages()));
            }
        }

    }

    private function getLastCurrentCartID()
    {
        $entity = $this->entity;
        $res = $entity::getList([
            'select' => ['ID'],
            'filter' => ['UF_USER' => $this->userId, 'UF_IS_CURRENT' => 1],
            'order' => ["UF_TIMESTAMP" => "DESC"],
        ]);

        if ($row = $res->Fetch()) {
            return $row['ID'];
        }
        else return false;
    }

    public function restoreLastCurrentCart()
    {
        $basket = new BasketRepository();
        $basket->clearBasket();
        $lastCurrentCartID = $this->getLastCurrentCartID();
        if ($lastCurrentCartID) {
            $this->createOrderByTemplate($lastCurrentCartID);
            $this->delete($lastCurrentCartID);
        }
    }
}
