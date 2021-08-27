<?php

namespace Citfact\SiteCore\Tools;

use Citfact\SiteCore\Core;

class InternetResourcesHelper
{

    const  PREVIEW_IMAGE_PATH = '/upload/ftp_preview_img/';

    public function getResources($filter)
    {
        $hlblockOb = new HLBlock();
        $core = Core::getInstance();

        //Получаем таблицу с интернет-ресурсами
        $dataClassRes = $hlblockOb->getHlEntityByName($core::HL_BLOCK_CODE_INTERNET_RESOURCES);
        $rsDataRes = $dataClassRes::getList(array(
            "select" => array("*"),
            "filter" => $filter
        ));
        return $rsDataRes;
    }

    public function getResourcesOfNomenclature($xml_id)
    {
        $hlblockOb = new HLBlock();
        $core = Core::getInstance();

        //Получаем таблицу с привязкой товаров к интернет-ресурсам
        $dataClassResNom = $hlblockOb->getHlEntityByName($core::HL_BLOCK_CODE_INTERNET_NOMENCLATURE);
        $rsData = $dataClassResNom::getList(array(
            "select" => array("*"),
            "filter" => array("UF_IDNOMENCLATURE" => $xml_id)  // Задаем параметры фильтра выборки
        ));

        while ($arReviews = $rsData->Fetch()) {
            $xmlIDInternetRes [] = $arReviews['UF_IDRESOURCE'];
        }
        $filter = ["UF_XML_ID" => $xmlIDInternetRes];

        $rsDataRes = self::getResources($filter);
        while ($arReviews = $rsDataRes->Fetch()) {
            $internetResources [] = $arReviews;
        }
        return $internetResources;
    }

    public function getDirOfPreviewImg($id)
    {
        $dirFiles = scandir($_SERVER['DOCUMENT_ROOT'] .  self::PREVIEW_IMAGE_PATH . $id . '/');
        if (($key = array_search(".", $dirFiles)) !== false) {
            unset($dirFiles[$key]);
        }
        if (($key = array_search("..", $dirFiles)) !== false) {
            unset($dirFiles[$key]);
            sort($dirFiles);
        }
        return $dirFiles;
    }


    public function getPath($id)
    {
        $dirFiles = self::getDirOfPreviewImg($id);
        $pictureName = $dirFiles[0];
        $path = self::PREVIEW_IMAGE_PATH . $id . '/' . $pictureName;
        return $path;
    }

    public function deletePreviewPicture($id)
    {
        $rsDataRes = $this->getResources(['ID' => $id]);
        while ($arReviews = $rsDataRes->Fetch()) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $this->getPath($arReviews['ID']));
            rmdir($_SERVER['DOCUMENT_ROOT'] . $this->getPath($arReviews['ID']));
        }
    }
}