<?php


namespace Citfact\SiteCore\EventListener;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;


class ManagersStructureSubscriber implements SubscriberInterface
{
    private static $arrXmlId = [];

    public static function getSubscribedEvents()
    {
        return [
           ['module' => '', 'event' => 'StructureManagersOnBeforeAdd', 'sort' => 100, 'method' => 'structureManagersOnBeforeAdd'],
           ['module' => '', 'event' => 'StructureManagersOnAfterAdd', 'sort' => 100, 'method' => 'structureManagersOnAfterAdd'],
           ['module' => '', 'event' => 'StructureManagersOnBeforeUpdate', 'sort' => 100, 'method' => 'structureManagersOnBeforeUpdate'],
           ['module' => '', 'event' => 'StructureManagersOnAfterUpdate', 'sort' => 100, 'method' => 'structureManagersOnAfterUpdate'],
           ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'onSuccessCatalogImportHL'],
            ['module' => '', 'event' => 'StructureManagersOnBeforeDelete', 'sort' => 100, 'method' => 'structureManagersOnAfterDelete'],
        ];
    }

    protected function getManagerId($xml_id)
    {
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();
        $id = '';
        $dataClassRes = $hlblockOb->getHlEntityByName($core::HLBLOCK_CODE_MANAGERS);
        $rsDataRes = $dataClassRes::getList(array(
            "select" => array("*"),
            "filter" => ['UF_XML_ID'=>$xml_id]
        ));
        if($res = $rsDataRes->Fetch()){
            $id = $res['ID'];
        }
        return $id;
    }


    public function structureManagersOnBeforeAdd(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        $idManager = self::getManagerId($arFields['UF_IDMANAGER']);
        $idSubManager = self::getManagerId($arFields['UF_IDSUBMANAGER']);
        $arFields['UF_ID_SUB_MANAGER'] = $idSubManager;
        $arFields['UF_ID_MANAGER'] = $idManager;
        $result->modifyFields($arFields);
        LkCacheManager::clearCache();
        return $result;
    }

    public function structureManagersOnAfterAdd(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        self::$arrXmlId[]= $arFields['UF_XML_ID'];
    }

    public function structureManagersOnBeforeUpdate(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        $idManager = self::getManagerId($arFields['UF_IDMANAGER']);
        $idSubManager = self::getManagerId($arFields['UF_IDSUBMANAGER']);
        $arFields['UF_ID_SUB_MANAGER'] = $idSubManager;
        $arFields['UF_ID_MANAGER'] = $idManager;
        $result->modifyFields($arFields);
        LkCacheManager::clearCache();
        return $result;
    }

    public function structureManagersOnAfterDelete(\Bitrix\Main\Entity\Event $event)
    {
        LkCacheManager::clearCache();
    }

    public function structureManagersOnAfterUpdate(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        self::$arrXmlId[]= $arFields['UF_XML_ID'];
    }

    public function onSuccessCatalogImportHL($arParams = [], $ABS_FILE_NAME = '')
    {
        $fileNamesToApply = ['references_structureManagers.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (!in_array($fileName, $fileNamesToApply)) {
            return;
        }
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();

        $entity_data_class = $hlblockOb->getHlEntityByName($core::HLBLOCK_CODE_STRUCTURE_MANAGERS);

        $rsData = $entity_data_class::getList(array(
            'select' => array('ID'),
            'filter' => array('!=UF_XML_ID' => self::$arrXmlId),
        ));
        while ($row = $rsData->fetch()) {
            $arXml[] = $row['ID'];
        }

        foreach($arXml as $id){
            $entity_data_class::delete($id);
        }
    }
}