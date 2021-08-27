<?php

namespace Citfact\SiteCore\RekomendovannyeTovary;


use Bitrix\Highloadblock\HighloadBlockTable;

class RekomendovannyeTovaryManager
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function getHLId() {
        $core = \Citfact\SiteCore\Core::getInstance();
        $hlid = $core->getHlBlockId($core::HLBLOCK_CODE_REKOMENDOVANNYE_TOVARY);

        return $hlid;
    }


    /**
     * @param $xmlId
     * @return array
     * @throws \Exception
     */
    public static function getListByContragent($xmlId) {
        if (!$xmlId) {
            return [];
        }

        $REKOMENDOVANNYE_TOVARY = [];

        $hlid = self::getHLId();
        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['UF_KONTRAGENT' => $xmlId]
        ]);
        while ($arItem = $res->fetch()) {
            $REKOMENDOVANNYE_TOVARY[] = $arItem;
        }

        return $REKOMENDOVANNYE_TOVARY;
    }
}