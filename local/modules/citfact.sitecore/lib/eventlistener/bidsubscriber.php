<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BidSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['module' => '', 'event' => 'BidsOnBeforeUpdate', 'method' => 'setNeedExport'],
        ];
    }

    /**
     * @param Event $event
     * @return EventResult
     */
    public static function setNeedExport(Event $event)
    {
        $result = new EventResult();
        $fields = $event->getParameter("fields");
        /*if (!array_key_exists('UF_NEED_EXPORT', $fields)) {
            $result->modifyFields(['UF_NEED_EXPORT' => 1]);
        }*/

        $dateTime = new \DateTime();
        $dateTimeFormatted = $dateTime->format('d.m.Y H:i:s');
        $result->modifyFields(['UF_DATE_UPDATED' => $dateTimeFormatted]);

        return $result;
    }
}
