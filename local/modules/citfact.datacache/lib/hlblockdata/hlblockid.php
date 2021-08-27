<?php
namespace Citfact\DataCache\HLBlockData;

use Citfact\DataCache\DataID;

class HLBlockId extends DataID
{
    protected $codeCache = 'hlblock';

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        if (!\Bitrix\Main\Loader::includeModule('highloadblock'))
            return array();

        $hlblocksByCode = array();
        $rsHLBlockData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter'=>array('*'),
            'select' => array('ID', 'NAME', 'TABLE_NAME')
        ));
        while ($arHLBlockData = $rsHLBlockData->fetch()){
            $hlblocksByCode[$arHLBlockData['NAME']] = $arHLBlockData['ID'];
        }
        return $hlblocksByCode;
    }
}
