<?php


namespace Citfact\SiteCore\EventListener;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\Tools\Event\SubscriberInterface;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;


class DebitorskayaZadolzhennostSubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'DebitorskayaZadolzhennostOnAfterAdd', 'sort' => 100, 'method' => 'onAfterAdd'],
            ['module' => '', 'event' => 'DebitorskayaZadolzhennostOnAfterUpdate', 'sort' => 100, 'method' => 'onAfterUpdate'],
            ['module' => '', 'event' => 'DebitorskayaZadolzhennostOnAfterDelete', 'sort' => 100, 'method' => 'onAfterDelete'],
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