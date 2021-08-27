<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;

Loc::loadMessages(__FILE__);

class webformSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['module' => 'form', 'event' => 'onAfterResultAdd', 'method' => 'onAfterResultAddHandler'],
        ];
    }

    public static function onAfterResultAddHandler($WEB_FORM_ID, &$RESULT_ID)
    {
        $core = Core::getInstance();
        ;
        /*todo костыли заменить Id 656, 657, 228 на коды*/
        if (in_array($WEB_FORM_ID, $core::WEB_FORM_ID) && isset($_REQUEST['CTRLVFILE']) && !empty($_REQUEST['CTRLVFILE']))
        {
            $arFile = \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$_REQUEST['CTRLVFILE']);
            $arValues = array (
                "form_file_656" => $arFile,
                "form_file_657" => $arFile,
                "form_file_228" => $arFile,
            );
            \CFormResult::Update($RESULT_ID, $arValues, "Y");
        }
    }
}
