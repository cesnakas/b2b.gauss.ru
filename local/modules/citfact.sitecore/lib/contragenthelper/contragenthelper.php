<?php

namespace Citfact\SiteCore\ContragentHelper;

session_start();

use Bitrix\Highloadblock\HighloadBlockTable;
use Citfact\SiteCore\Core;
use Citfact\DataCache\PriceData\PriceId;
use Citfact\SiteCore\Tools\HLBlock;

global $USER;

class ContragentHelper
{
    public static function getContragentDataByGuid($contragentGuid)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_XML_ID' => $contragentGuid],
        ));
        if ($el = $rsData->fetch()) {
            $arContragent = $el;
        }

        if ($arContragent['UF_TELEFON']) {
            $arContragent['UF_TELEFON'] = explode(',', $arContragent['UF_TELEFON']);
        }

        return $arContragent;
    }


    public function getContragentsForDeactivation()
    {
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();
        $entity_data_class = $hlblockOb->getHlEntityByName($core::HLBLOCK_CODE_KONTRAGENTY);
        $contragentsForDeactivation = [];
        $rsData = $entity_data_class::getList(array(
            'select' => array('ID'),
            'filter' => ['UF_DEACTIVATION' => 'Y'],
        ));
        while($arContragents = $rsData->Fetch()){
            $contragentsForDeactivation[]= $arContragents['ID'];
        }
        return $contragentsForDeactivation;
    }

    public function deactivateContragents()
    {
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();

        $entity_data_class = $hlblockOb->getHlEntityByName($core::HLBLOCK_CODE_KONTRAGENTY);
        $contragentsIds = $this->getContragentsForDeactivation();

        foreach($contragentsIds as $id){
            $entity_data_class::delete($id);
        }
    }
}