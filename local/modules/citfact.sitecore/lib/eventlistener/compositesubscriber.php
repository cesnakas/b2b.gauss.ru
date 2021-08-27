<?php

namespace Citfact\SiteCore\EventListener;

use Citfact\SiteCore\CacheProvider\CacheProviderManager;
use Citfact\Tools\Event\SubscriberInterface;

class CompositeSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['module' => 'main', 'event' => 'OnGetStaticCacheProvider', 'method' => 'OnGetStaticCacheProviderHandler'],
        ];
    }


    public static function OnGetStaticCacheProviderHandler() {
        return new CacheProviderManager;
    }
}