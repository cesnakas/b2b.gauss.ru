<?php

namespace Citfact\Sitecore\CatalogHelper;

use Citfact\SiteCore\UserDataManager;


class Price
{
    const PRICE_ID_MAIN = '3';

    const PRICE_CODE_MAIN = 'Оптовая цена';
//    const PRICE_CODE_ACTION = 'Акционные цены ИМ';
    /**
     * gauss МИЦ
     */
    const PRICE_CODE_MIC = '9a348c48-9fb3-11e4-d990-f8d111067d46';

    const EXTRA_PRICES = [
        '9a348c48-9fb3-11e4-d990-f8d111067d46',
        'b325e810-16f5-11e4-af94-f8d111067d46',
        '1084121b-db7d-11df-9656-00215e67d85c'
    ];

    public static function getMapLabelExtraPrices()
    {
        return [
            '9a348c48-9fb3-11e4-d990-f8d111067d46' => 'МИЦ',
            'b325e810-16f5-11e4-af94-f8d111067d46' => 'РРЦ',
            '1084121b-db7d-11df-9656-00215e67d85c' => 'ОПТ'
        ];
    }

    public static function getLabelExtraPrice($xmlId)
    {
        return (static::getMapLabelExtraPrices()[$xmlId] ? static::getMapLabelExtraPrices()[$xmlId] : '');
    }

    protected static function getUserPriceType(): array
    {
        $priceType = [
            'ID' => static::PRICE_ID_MAIN,
            'CODE' => static::getBaseTypePrice(),
        ];

        $userPriceType = UserDataManager\UserDataManager::getUserPriceType();


        if (!empty($userPriceType['ID'])) {
            $priceType['ID'] = $userPriceType['ID'];
            $priceType['CODE'] = $userPriceType['NAME'];
        }

        return $priceType;
    }


    public static function getWithoutDiscountPrices($productId)
    {

        $priceCode = static::getUserPriceType()['CODE'];

        $cGroup = new \CCatalogGroup();
        $cPrice = new \CPrice();
        $result = array();

        if (!$productId) {
            return array();
        }

        $dbPriceType = $cGroup->GetList(
            array('SORT' => 'ASC'),
            array('NAME' => $priceCode),
            false,
            false,
            array('ID', 'NAME')
        );

        $catalogGroups = array();

        if ($priceTypeData = $dbPriceType->Fetch()) {
            $catalogGroups[$priceTypeData['ID']] = $priceTypeData;
        }

        $catalogGroupIds = array_keys($catalogGroups);

        $dbProductPrice = $cPrice->GetList(
            array(),
            array('PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => $catalogGroupIds),
            false,
            false,
            array('ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID')
        );

        while ($item = $dbProductPrice->fetch()) {
            $result = $item;
        }
        return $result;
    }

    /**
     * @param $productId
     * @return array
     */
    public static function getDiscountPrices($productId)
    {

        $priceCode = static::getUserPriceType()['CODE'];

        $cGroup = new \CCatalogGroup();
        $cPrice = new \CPrice();
        $result = array();

        if (!$productId) {
            return array();
        }

        $dbPriceType = $cGroup->GetList(
            array('SORT' => 'ASC'),
            array('NAME' => $priceCode),
            false,
            false,
            array('ID', 'NAME')
        );
        $catalogGroups = array();
        if ($priceTypeData = $dbPriceType->Fetch()) {
            $catalogGroups[$priceTypeData['ID']] = $priceTypeData;
        }

        $catalogGroupIds = array_keys($catalogGroups);

        $dbProductPrice = $cPrice->GetList(
            array(),
            array('PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => $catalogGroupIds),
            false,
            false,
            array('ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID')
        );

        while ($item = $dbProductPrice->fetch()) {
            $result[$catalogGroups[$item['CATALOG_GROUP_ID']]['NAME']] = $item;
        }
        return $result;

    }

    public static function getBaseTypePrice()
    {
        $result = 'Оптовая цена';
        $tag_cache = 'baseTypePrice';
        $obCache = new \CPHPCache();
        if ($obCache->InitCache(86400, $tag_cache, '/' . $tag_cache)) {
            $vars = $obCache->GetVars();
            $result = $vars['result'];
        } elseif ($obCache->StartDataCache()) {
            $res = \CCatalogGroup::GetListEx(array(), array('BASE' => 'Y'), false, false, array('*'));
            if ($group = $res->Fetch()) {
                $result = $group['NAME'];
            }
            $obCache->EndDataCache(array('result' => $result));
        }
        return $result;
    }

    /**
     * Получение описания цены по XML_ID
     * XML_ID
     * @param $xmlId
     *
     * @return array
     */
    public static function getPriceByCode($xmlId)
    {
        if (empty($xmlId)) {
            return [];
        }
        $result = [];

        $tag_cache = 'price-' . $xmlId;
        $obCache = new \CPHPCache();
        if ($obCache->InitCache(86400, $tag_cache, '/' . $tag_cache)) {
            $result = $obCache->GetVars();
        } elseif($obCache->StartDataCache()) {
            $dbPriceType = \CCatalogGroup::GetList(
                array("SORT" => "ASC"),
                ['XML_ID' => $xmlId]
            );
            if ($arPriceType = $dbPriceType->Fetch()) {
                $result = $arPriceType;
                $obCache->EndDataCache($arPriceType);
            }
        }

        return $result;
    }

    public static function getExtraPricesInfo()
    {
        $arPrices = [];
        $dbPriceType = \CCatalogGroup::GetList(
            array("SORT" => "ASC"),
            ['XML_ID' => static::EXTRA_PRICES]
        );
        while ($arPriceType = $dbPriceType->Fetch()) {
            $arPrices[$arPriceType['ID']] = $arPriceType['NAME'];
        }
        return $arPrices;
    }

    public static function getExtraPricesXmlInfo()
    {
        $arPrices = [];
        $dbPriceType = \CCatalogGroup::GetList(
            array("SORT" => "ASC"),
            ['XML_ID' => static::EXTRA_PRICES]
        );
        while ($arPriceType = $dbPriceType->Fetch()) {
            $arPrices[$arPriceType['ID']] = $arPriceType['XML_ID'];
        }
        return $arPrices;
    }
}