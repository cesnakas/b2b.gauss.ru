<?php
namespace Citfact\DataCache\PriceData;

use Citfact\DataCache\DataID;

class PriceId extends DataID
{
    protected $codeCache = 'price';

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        if (!\Bitrix\Main\Loader::includeModule('catalog'))
            return array();

        $pricesByCode = array();
        $dbPriceType = \CCatalogGroup::GetList(
            array(),
            array('!XML_ID' => false)
        );
        while ($arPriceType = $dbPriceType->Fetch()) {
            $pricesByCode[$arPriceType["XML_ID"]] = $arPriceType;
        }

        return $pricesByCode;
    }
}
