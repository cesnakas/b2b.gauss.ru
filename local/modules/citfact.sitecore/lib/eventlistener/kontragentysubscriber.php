<?php


namespace Citfact\SiteCore\EventListener;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\SiteCore\ContragentHelper\ContragentHelper;


class KontragentySubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'KontragentyOnAfterAdd', 'sort' => 100, 'method' => 'onAfterAdd'],
            ['module' => '', 'event' => 'KontragentyOnAfterUpdate', 'sort' => 100, 'method' => 'onAfterUpdate'],
            ['module' => '', 'event' => 'KontragentyOnAfterDelete', 'sort' => 100, 'method' => 'onAfterDelete'],
            ['module' => 'catalog', 'event' => 'OnSuccessCatalogImportHL', 'method' => 'OnSuccessCatalogImportHL'],
        ];
    }

    public function onAfterAdd(\Bitrix\Main\Entity\Event $event)
    {
        LkCacheManager::clearCache();
    }

    public function onAfterUpdate(\Bitrix\Main\Entity\Event $event)
    {
         LkCacheManager::clearCache();
    }

    public function onAfterDelete(\Bitrix\Main\Entity\Event $event)
    {
        LkCacheManager::clearCache();
    }

    public function OnSuccessCatalogImportHL($arParams = [], $ABS_FILE_NAME = '')
    {
        $fileNamesToApply = ['references_contragents.xml'];
        $fileName = basename($ABS_FILE_NAME);
        if (!in_array($fileName, $fileNamesToApply)) {
            return;
        }
        $contragentHelper = new ContragentHelper();
        $contragentHelper->deactivateContragents();
        
    } 
}