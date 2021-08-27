<?php

namespace Citfact\SiteCore\Rezervy;


use Bitrix\Highloadblock\HighloadBlockTable;

class RezervyManager
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function getHLId() {
        $core = \Citfact\SiteCore\Core::getInstance();
        $hlid = $core->getHlBlockId($core::HLBLOCK_CODE_REZERVY);

        return $hlid;
    }


    /**
     * @param $xmlId
     * @return array
     * @throws \Exception
     */
    public static function getByNomenclature($xmlId) {
         if (!$xmlId) {
             return [];
         }

        $RESERV_BALANCE = [];

        $hlid = self::getHLId();
        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['UF_NOMENKLATURA' => $xmlId]
        ]);
        if ($arItem = $res->fetch()) {
            $RESERV_BALANCE = $arItem;
        }

        return $RESERV_BALANCE;
    }


    /**
     * @param array $xmlId
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getListByNomenclaturers(Array $xmlId) {
        if (!$xmlId) {
            return [];
        }

        $RESERV_BALANCE = [];

        $hlid = self::getHLId();
        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['UF_NOMENKLATURA' => $xmlId]
        ]);
        while ($arItem = $res->fetch()) {
            $RESERV_BALANCE[] = $arItem;
        }

        return $RESERV_BALANCE;
    }
}