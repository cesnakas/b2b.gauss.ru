<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Sale\Order;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\Order\OrderRepository;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\UserDataManager\UserDataManager;

Loc::loadMessages(__FILE__);

class EmailSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            array('module' => 'main', 'event' => 'OnBeforeEventAdd', 'sort' => 100, 'method' => 'sendManagerAssistantEventAddHandler')
        );
    }

    /**
     * добавляем к письмам email'ы менеджера и ассистента пользователя
     *
     * @param $event
     * @param $lid
     * @param $arFields
     * @param $message_id
     * @param $files
     * @param $languageId
     * @return bool|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function sendManagerAssistantEventAddHandler(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId)
    {

        if (in_array($event, ['NEW_USER', 'SALE_NEW_ORDER_MANAGER', 'SALE_NEW_ORDER_ASSISTANT'])) {
            return;
        }

        $xmlContragent = UserDataManager::getUserContragentXmlID();

        if (!$xmlContragent) {
            return;
        }

        ///printLogs(['$xmlContragent' => $xmlContragent], 'sendManagerAssistantEventAddHandler');

        if ($xmlContragent) {
            $userManager = \Citfact\SiteCore\User\UserManagers::getManagerByContragent($xmlContragent);
            $userAssistants = \Citfact\SiteCore\User\UserManagers::getAssistantsByContragent($xmlContragent);
            $arFields['CONTRAGENT_NAME'] = UserDataManager::getContrAgentInfo($xmlContragent)['UF_NAME'];
        }
        
        $arFields['EMAIL_MANAGER_BY_USER'] = trim($userManager['EMAIL']);

        $assistantsEmails = [];

        if (!empty($userAssistants)) {
            foreach ($userAssistants as $userAssistant) {
                $assistantsEmails[] = trim($userAssistant['EMAIL']);
            }
        }

        if (!empty($assistantsEmails)) {
            $arFields['EMAIL_ASSISTANT_BY_USER'] = implode(', ', $assistantsEmails);
        }

        $arFields['EMAIL_ALL_ASSISTANT'] = implode(', ', \Citfact\SiteCore\User\UserManagers::getAllAssistantsEmails());

        $arFields['EMAIL_MANAGER_ASSISTANT_BY_USER'] = implode(', ', [trim($userManager['EMAIL']), $arFields['EMAIL_ASSISTANT_BY_USER']]);
        ///printLogs(['$arFields' => $arFields], 'sendManagerAssistantEventAddHandler');

        if (empty($arFields['EMAIL_MANAGER_BY_USER']) && empty($arFields['EMAIL_ASSISTANT_BY_USER'])) {
            return;
        }

        if ($userManager['EMAIL']) {
            $arFields['MANAGER_NAME_BY_USER'] = \Citfact\SiteCore\User\UserHelper::getFullNameByUser($userManager);
        }
    }
}
