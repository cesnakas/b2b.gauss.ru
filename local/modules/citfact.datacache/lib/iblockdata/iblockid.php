<?php

namespace Citfact\DataCache\IBlockData;

use Citfact\DataCache\DataID;

class IBlockID extends DataID
{
    protected $codeCache = 'iblock';

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock'))
            return array();

        $dataByCode = array();
        $rsIBlocks = \CIBlock::GetList(
            array(),
            array(
                "!CODE" => false,
            )
        );
        while ($arIBlock = $rsIBlocks->Fetch()) {
            $dataByCode[$arIBlock['CODE']] = $arIBlock['ID'];
        }
        return $dataByCode;
    }

    protected function setTagCache () {
        parent::setTagCache($this->codeCache."_id_new");
    }
}
