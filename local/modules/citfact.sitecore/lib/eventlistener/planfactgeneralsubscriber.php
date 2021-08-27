<?php


namespace Citfact\SiteCore\EventListener;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;


class PlanFactGeneralSubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'PlanFactGeneralOnAfterAdd', 'sort' => 100, 'method' => 'onAfterAdd'],
            ['module' => '', 'event' => 'PlanFactGeneralOnAfterUpdate', 'sort' => 100, 'method' => 'onAfterUpdate'],
            ['module' => '', 'event' => 'PlanFactGeneralOnAfterDelete', 'sort' => 100, 'method' => 'onAfterDelete'],
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
}