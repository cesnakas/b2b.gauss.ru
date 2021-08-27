<?php

namespace Citfact\Tools;

use Bitrix\Highloadblock\HighloadBlockTable;

class HLBlock
{
    const HL_NAME_BIDS = "Bids";
    const HL_NAME_SMS = "SmsLog";

    public function __construct()
    {
        \CModule::IncludeModule('highloadblock');
    }

    /**
     * @param $name
     * @param string $field
     * @return \Bitrix\Main\Entity\DataManager
     */
    public function getHlEntityByName($name, $field = 'NAME')
    {
        $hlBlock = $this->getHlDataByName($name, $field);
        $obEntity = HighloadBlockTable::compileEntity($hlBlock);
        return $obEntity->getDataClass();
    }

    /**
     * @param $name
     * @param string $field
     * @return array
     */
    public function getHlDataByName($name, $field = 'NAME')
    {
        $filter = array($field => $name);
        return HighloadBlockTable::getList(array('filter' => $filter))->fetch();
    }
}