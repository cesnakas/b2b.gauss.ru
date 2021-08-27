<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Sale;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;

class ListWaitComponent extends \CBitrixComponent
{ 
    private $products = []; // id товаров
    private $productsXmlIds = []; // xml_id товаров


    /**
     * Реализует жизненный цикл компонента
     *
     */
    public function executeComponent()
    {

        $this->arResult['CHECKED'] = $this->getCheckedItemsArray();
        $this->arResult['WAIT_LIST'] = $this->getListWaitByUser($this->arParams['USER_ID']);

        if ($this->arResult['WAIT_LIST']) {
            $this->arResult['PRODUCTS'] = $this->getProducts();
            $this->arResult['ARRIVAL_DATES'] = $this->getArrivalDates();
        }

        $this->IncludeComponentTemplate();
    }

    /**
     * Возвращает лист ожидания пользователя
     *
     * @param int $userId - id пользователя
     * 
     * @return array
     */
    public function getListWaitByUser($userId)
    {
        $listWait = [];

        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'ListWait']
        ])->fetch();
        if ($hlblock) {
            global $USER;
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
    
            $rsData = $hlClassName::getList([
                'filter' => ['UF_USER_ID' => $userId]
            ]);
             
            while ($arData = $rsData->Fetch()) {
                $this->products[] = $arData['UF_PRODUCT_ID']; // массив продуктов для запроса
                if (!$arData['UF_VIEWED']) {
                    $hlClassName::update($arData['ID'], ['UF_VIEWED'=> true]); // отмечаем просмотренным, если товар был не просмотрен
                }
                $listWait[$arData['UF_PRODUCT_ID']] = $arData['UF_COUNT'];
            } 
        }

        return $listWait;
    }

    public function getCheckedItemsArray()
    {
        global $USER;
        $currentUser = $USER->GetID();
        $core = Core::getInstance();

        $hlId = $core->getHlBlockId($core::HL_BLOCK_CODE_LIST_WAIT);
        $hlblock = HighloadBlockTable::getById($hlId)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $checkedData = $entity_data_class::getList(array(
            "select" => array("*"),
            'filter' => [
                'UF_EMAIL_PERMISSION' => true,
                'UF_USER_ID' => $currentUser
            ],
        ));
        while ($el = $checkedData->Fetch()) {
            $checked[] = $el['UF_PRODUCT_ID'];
        }
        return $checked;
    }

    /**
     * Получение информации о товарах
     *
     * @return array
     */
    public function getProducts() {
        global $USER;

        $basket = $this->getUserBasket(); // корзина пользователя

        $products = [];
        $ids = $this->products; // id товаров

        $arSelect = ['ID', 'NAME', 'XML_ID', 'PREVIEW_PICTURE', 'PROPERTY_CML2_ARTICLE', 'DETAIL_PAGE_URL', 'PROPERTY_KOLICHESTVO_V_UPAKOVKE', 'QUANTITY'];
        $arFilter = ['ID' => $ids, 'ACTIVE' => 'Y'];
        $res = \CIBlockElement::GetList([], $arFilter, false, [], $arSelect);

        while ($arItem = $res->GetNext()) {
            $this->productsXmlIds[] = $arItem['XML_ID']; // для получения дат прихода товара 

            if ($arItem['PREVIEW_PICTURE']) {
                $picture = CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
                $product['PREVIEW_PICTURE'] = $picture['SRC'];
                $product['PREVIEW_PICTURE_NAME'] = $picture['FILE_NAME'];

            }
            $product['NAME'] = $arItem['NAME'];
            $product['ID'] = $arItem['ID'];
            $product['URL'] = $arItem['DETAIL_PAGE_URL'];
            $product['ARTICLE'] = $arItem['PROPERTY_CML2_ARTICLE_VALUE'];
            $product['XML_ID'] = $arItem['XML_ID'];
            $product['PACKAGED'] = $arItem['PROPERTY_KOLICHESTVO_V_UPAKOVKE_VALUE'];
            $product['QUANTITY'] = $arItem['QUANTITY'];
            $product['QUANTITY_FORMAT'] = $arItem['QUANTITY'] . 'шт';

            // количество такого товара сейчас в коризне
            if ($basket[$arItem['ID']]) {
                $product['COUNT_IN_BASKET'] = $basket[$arItem['ID']];
            } else {
                $product['COUNT_IN_BASKET'] = 0;
            }

            $products[$arItem['ID']] = $product;
            unset($product);
        }

        $products = $this->setPrices($products);

        return $products;
    }

    /**
     * Получение дат прихода товаров
     *
     * @return array
     */
    public function getArrivalDates() {
        $arArrivalDatesResult = [];

        if ($this->productsXmlIds) {
            $arArrivalDates = \Citfact\SiteCore\Rezervy\RezervyManager::getListByNomenclaturers($this->productsXmlIds);
            foreach ($arArrivalDates as $arrivalInfo) {
                if ($arrivalInfo['UF_DATAPRIKHODA']) {
                    $formatDate = date("d.m.Y", strtotime($arrivalInfo['UF_DATAPRIKHODA']));
                    $arArrivalDatesResult[$arrivalInfo['UF_NOMENKLATURA']] = $formatDate;
                }
            }
        }

        return $arArrivalDatesResult;
    }

    /**
     * Возвращает ID и количество товаров в корзине пользователя
     *
     * @return array
     */
    private function getUserBasket() {
        $basketProducts = [];

        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
        $basketItems = $basket->getBasketItems(); 
        foreach ($basketItems as $basketItem) {
            $basketProducts[$basketItem->getProductId()] = $basketItem->getQuantity();
        }

        return $basketProducts;
    }

    /**
     * Устанавливает цены продуктов
     *
     * @param array $products - массив продуктов
     * @return array
     */
    private function setPrices($products) {
        // получение названия типа цены как в каталоге
        $priceCode = Price::getBaseTypePrice();

        $userPriceType = UserDataManager\UserDataManager::getUserPriceType();
        if (!empty($userPriceType)) {
            $priceCode = $userPriceType['NAME'];
        }
        // получение id типа цены
        $dbPriceType = CCatalogGroup::GetList([], ['NAME' => $priceCode]);
        while ($arPriceType = $dbPriceType->Fetch()) {
            $priceGroupId = $arPriceType['ID'];
        }

        // получение значений цен товаров
        $priceRes = CPrice::GetList([],['PRODUCT_ID' => $this->products, 'CATALOG_GROUP_ID' => $priceGroupId]);
        while ($arPrice = $priceRes->Fetch())
        {
            if ($products[$arPrice['PRODUCT_ID']]) {
                $products[$arPrice['PRODUCT_ID']]['PRICE'] = $arPrice['PRICE'] . ' ₽';
            }
        }

        return $products;
    }
}