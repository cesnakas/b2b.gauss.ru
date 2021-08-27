<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Main\Application;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\Ajax;

Loc::loadMessages(__FILE__);

class BufferSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['module' => 'main', 'event' => 'OnEndBufferContent', 'method' => 'clearHTML'],
        ];
    }


    /**
     * очищаем html от проебелов и  type="text/javascript"
     * @param $content
     */
    public static function clearHTML(&$content)
    {
        $content = str_replace("type=\"text/javascript\"", "", $content);
    }
}