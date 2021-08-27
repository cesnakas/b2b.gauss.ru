<?php


namespace Citfact\SiteCore\EventListener;

use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Tools\InternetResourcesHelper;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;

class InternetResourcesSubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'InternetResourcesOnBeforeAdd', 'sort' => 100, 'method' => 'InternetResourcesOnBeforeAdd'],
            ['module' => '', 'event' => 'InternetResourcesOnBeforeUpdate', 'sort' => 100, 'method' => 'internetResourcesOnBeforeUpdate'],
            ['module' => '', 'event' => 'InternetResourcesOnAfterUpdate', 'sort' => 100, 'method' => 'internetResourcesOnAfterUpdate'],
            ['module' => '', 'event' => 'InternetResourcesOnBeforeDelete', 'sort' => 100, 'method' => 'internetResourcesOnBeforeDelete'],
        ];
    }


    protected function deleteNomenclatureByResource($id)
    {
        $core = Core::getInstance();
        $hlblockOb = new HLBlock();

        $entity_data_class_res = $hlblockOb->getHlEntityByName($core::HL_BLOCK_CODE_INTERNET_RESOURCES);
        $rsDataResources = $entity_data_class_res::getList(array(
            'select' => array('UF_XML_ID'),
            'filter' => array('ID' => $id),
        ));
        if($resResources = $rsDataResources->Fetch()){
            $idsNomenclature = [];
            $entity_data_class = $hlblockOb->getHlEntityByName($core::HL_BLOCK_CODE_INTERNET_NOMENCLATURE);
            $rsData = $entity_data_class::getList(array(
                'select' => array('ID'),
                'filter' => array('UF_IDRESOURCE' => $resResources['UF_XML_ID']),
            ));

            while($res = $rsData->Fetch()){
                $idsNomenclature[] = $res['ID'];
            }
            foreach ($idsNomenclature as $id){
                $entity_data_class::delete($id);
            }
        }
    }


    public static function InternetResourcesOnBeforeAdd(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();

        if (empty($arFields['UF_LAST_UPDATE'])) {
            $arFields['UF_LAST_UPDATE'] = date("Y-m-d H:i:s");
            $result->modifyFields($arFields);
            return $result;

        }
    }

    public static function internetResourcesOnBeforeDelete(\Bitrix\Main\Entity\Event $event)
    {
        $id = $event->getParameter("id");
        $internetResourcesHelper = new InternetResourcesHelper();
        $internetResourcesHelper->deletePreviewPicture($id);
        static::deleteNomenclatureByResource($id);
    }


    public static function internetResourcesOnBeforeUpdate(\Bitrix\Main\Entity\Event $event)
    {
        $arFields = $event->getParameter("fields");
        $result = new \Bitrix\Main\Entity\EventResult();
        $arFields['UF_LAST_UPDATE'] = date("Y-m-d H:i:s");
        $result->modifyFields($arFields);
        return $result;
    }

    public static function internetResourcesOnAfterUpdate(\Bitrix\Main\Entity\Event $event)
    {
        $id = $event->getParameter("id");
        static::deleteNomenclatureByResource($id);
    }


}