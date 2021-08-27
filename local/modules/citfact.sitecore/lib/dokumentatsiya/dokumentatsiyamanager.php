<?php

namespace Citfact\SiteCore\Dokumentatsiya;


use Bitrix\Highloadblock\HighloadBlockTable;

class DokumentatsiyaManager
{
    const DIR_FILES = '/upload/tech-doc/';


    /**
     * @return string
     * @throws \Exception
     */
    public static function getHLId() {
        $core = \Citfact\SiteCore\Core::getInstance();
        $hlid = $core->getHlBlockId($core::HLBLOCK_CODE_DOCUMENTATION);

        return $hlid;
    }


    /**
     * @param $xmlId
     * @return array
     * @throws \Exception
     */
    public static function getListByNomenclature($xmlId) {
         if (!$xmlId) {
             return [];
         }

        $Dokumentatsiya = [];

        $hlid = self::getHLId();
        $hlblock = HighloadBlockTable::getById($hlid)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['=UF_NOMENKLATURA' => $xmlId]
        ]);
        while ($arItem = $res->fetch()) {
            if ($arItem['UF_IMYAFAYLA'] && file_exists($_SERVER['DOCUMENT_ROOT'] . self::DIR_FILES . $arItem['UF_IMYAFAYLA'])) {
                $arItem['FILE'] = self::DIR_FILES . $arItem['UF_IMYAFAYLA'];
                $arItem['EXTENSION'] = \Bitrix\Main\IO\Path::getExtension($_SERVER['DOCUMENT_ROOT'] . self::DIR_FILES . $arItem['UF_IMYAFAYLA']);

                $pos = strpos($arItem['UF_IMYAFAYLA'], $arItem['EXTENSION']);
                $arItem['FILE_NAME_NOT_EXT'] = substr($arItem['UF_IMYAFAYLA'], 0, $pos-1);

                $Dokumentatsiya[] = $arItem;
            }
        }

        return $Dokumentatsiya;
    }
}