<?php

namespace Citfact\Sitecore\CatalogHelper;

use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;

class ItemAvailability
{
    const STATUS_ID_UNAVAILABLE = 2;

    /**
     * @var array
     */
    protected $regionStatuses;

    protected $statusNames = array(
        0 => 'Много',
        1 => 'Достаточно',
        3 => 'Под заказ',
        2 => 'Выведен из ассортимента',
    );

    protected $statusClasses = array(
        0 => 'b-product__avail',
        1 => 'b-product__avail b-product__avail--less',
        3 => 'b-product__avail b-product__avail--unavail',
        2 => 'hidden',
    );

    /**
     * @param $data
     * @return array
     */
    public function getQuantityInfo($data)
    {
        $userPriceType = UserDataManager\UserDataManager::getUserPriceType();

        $cCore = Core::getInstance();
        $price = $data['CATALOG_PRICE_ID_' . $userPriceType['ID']];
        if ($price <= 0) {
            return array(
                'NAME' => $this->statusNames[2],
                'CLASS' => $this->statusClasses[2],
                'CODE' => 2,
            );
        }

        $statusByRegions = $data['PROPERTIES']['STATUS_BY_REGIONS']['VALUE'];
        if (!$this->regionStatuses) {
            $this->regionStatuses = $cCore->Region->getFilter()->getFilterValues();
        }

        foreach ($this->regionStatuses as $code) {
            if (in_array($code, $statusByRegions)) {
                $explodeCode = explode('-', $code);
                $code = array_pop($explodeCode);
                return array(
                    'NAME' => $this->statusNames[$code],
                    'CLASS' => $this->statusClasses[$code],
                    'CODE' => $code,
                );
            }
        }

        return array(
            'NAME' => $this->statusNames[2],
            'CLASS' => $this->statusClasses[2],
            'CODE' => 2,
        );
    }

    public function getStatusByElementId($id)
    {
        $cCore = Core::getInstance();
        ///$arPriceTypes = $cCore->Region->getPrices()->priceTypes; /// TODO PRICES

        $arP = array();
        $res = \CCatalogGroup::GetListEx(array(), array('=NAME' => 'BASE'), false, false, array('*'));
        if ($group = $res->Fetch()) {
            $priceID = $group['ID'];
            $priceRes = \CPrice::GetList(array(), array('PRODUCT_ID' => $id, 'CATALOG_GROUP_ID' => $priceID));
            if ($priceResId = $priceRes->Fetch()) {
                $arP = $priceResId;
            }
        }

        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "CATALOG_GROUP_" . $arP["CATALOG_GROUP_ID"],
            "PROPERTY_STATUS_BY_REGIONS",
        );
        $arFilter = array(
            "IBLOCK_LID" => SITE_ID,
            "IBLOCK_ACTIVE" => "Y",
            "ACTIVE_DATE" => "Y",
            "ACTIVE" => "Y",
            "CATALOG_PRICE_" . $arP["CATALOG_GROUP_ID"],
        );

        $result = array();
        $resProp = array();
        $arFilter["=ID"] = $id;
        $rsElements = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($arElement = $rsElements->Fetch()) {
            $result = $arElement;
            $resProp[] = $arElement["PROPERTY_STATUS_BY_REGIONS_VALUE"];
        }
        $result['PROPERTIES']['STATUS_BY_REGIONS']['VALUE'] = $resProp;

        $cItemAvailability = new ItemAvailability();
        $arResult["CAN_BUY"] = true;

        return $arResult["CAN_BUY"];
    }

}
