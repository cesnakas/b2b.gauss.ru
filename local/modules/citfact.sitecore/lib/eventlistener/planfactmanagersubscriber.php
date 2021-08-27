<?php


namespace Citfact\SiteCore\EventListener;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;


class PlanFactManagerSubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'PlanFactManagerOnAfterAdd', 'sort' => 100, 'method' => 'onAfterAdd'],
            ['module' => '', 'event' => 'PlanFactManagerOnAfterUpdate', 'sort' => 100, 'method' => 'onAfterUpdate'],
            ['module' => '', 'event' => 'PlanFactManagerOnAfterDelete', 'sort' => 100, 'method' => 'onAfterDelete'],
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