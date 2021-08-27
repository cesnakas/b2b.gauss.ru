<?php
namespace Citfact\Sitecore\Location;

use Bitrix\Main\Loader,
    Citfact\DataCache\DataID;

class LocTypeManager extends DataID
{
    protected $codeCache = 'locationtype';

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        if (!Loader::includeModule('sale'))
            return array();

        $arLocationType = array();
        $resLocType = LocTypeTable::getList([
            'filter' => ['*'],
            'select' => ['CODE', 'ID'],
        ]);
        while ($locType = $resLocType->fetch()) {
            $arLocationType[$locType['CODE']] = $locType['ID'];
        }

        return $arLocationType;
    }
}
