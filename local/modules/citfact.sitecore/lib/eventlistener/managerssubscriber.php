<?php


namespace Citfact\SiteCore\EventListener;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;


class ManagersSubscriber implements SubscriberInterface
{

    private static $arrXmlId = [];

    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'MenedzheryOnBeforeAdd', 'sort' => 100, 'method' => 'menedzheryOnBeforeAdd'],
            ['module' => '', 'event' => 'MenedzheryOnAfterAdd', 'sort' => 100, 'method' => 'menedzheryOnAfterAdd'],
            ['module' => '', 'event' => 'MenedzheryOnBeforeUpdate', 'sort' => 100, 'method' => 'menedzheryOnBeforeUpdate'],
            ['module' => '', 'event' => 'MenedzheryOnAfterUpdate', 'sort' => 100, 'method' => 'menedzheryOnAfterUpdate'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'OnSuccessCatalogImportHL'],
            ['module' => '', 'event' => 'MenedzheryOnBeforeDelete', 'sort' => 100, 'method' => 'menedzheryOnAfterDelete'],
        ];
    }

    protected function getIdOfLevelManager($xml_id)
    {
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();
        $id = '';
        $dataClassRes = $hlblockOb->getHlEntityByName($core::HLBLOCK_CODE_LEVEL_MANAGER);
        $rsDataRes = $dataClassRes::getList(array(
            "select" => array("*"),
            "filter" => ['UF_XML_ID' => $xml_id]
        ));
        if ($res = $rsDataRes->Fetch()) {
            $id = $res['ID'];
        }
        return $id;
    }


    public function menedzheryOnBeforeAdd(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        $id = self::getIdOfLevelManager($arFields['UF_LEVELMANAGER']);
        $arFields['UF_LEVEL_MANAGER'] = $id;
        $result->modifyFields($arFields);
        LkCacheManager::clearCache();
        return $result;
    }

    public function menedzheryOnAfterDelete(\Bitrix\Main\Entity\Event $event)
    {
        LkCacheManager::clearCache();
    }

    public function menedzheryOnAfterAdd(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        self::$arrXmlId[] = $arFields['UF_XML_ID'];
    }

    public function menedzheryOnBeforeUpdate(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        $id = self::getIdOfLevelManager($arFields['UF_LEVELMANAGER']);
        $arFields['UF_LEVEL_MANAGER'] = $id;
        $result->modifyFields($arFields);
        LkCacheManager::clearCache();
        return $result;
    }

    public function menedzheryOnAfterUpdate(\Bitrix\Main\Entity\Event $event)
    {

        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        self::$arrXmlId[] = $arFields['UF_XML_ID'];
    }

    public function OnSuccessCatalogImportHL($arParams = [], $ABS_FILE_NAME = '')
    {
        $fileNamesToApply = ['references_managers.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (!in_array($fileName, $fileNamesToApply)) {
            return;
        }
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();

        $entity_data_class = $hlblockOb->getHlEntityByName($core::HLBLOCK_CODE_MANAGERS);
        $rsData = $entity_data_class::getList(array(
            'select' => array('ID', 'UF_NAME', 'UF_XML_ID'),
            'filter' => array('!=UF_XML_ID' => self::$arrXmlId),
        ));
        while ($row = $rsData->fetch()) {
            $arXml[$row['ID']] = $row;
        }

        foreach ($arXml as $id => $val) {
            $entity_data_class::delete($id);
            printLogs(sprintf('Удален менеджер с ID %s, UF_XML_ID %s, %s', $id, $val['UF_XML_ID'], $val['UF_NAME']),
                '/local/var/logs/managersSubscriber/deleted_' . date('Y-m') . '.log');
        }
    }
}