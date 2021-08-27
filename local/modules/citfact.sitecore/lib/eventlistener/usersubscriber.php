<?php

namespace Citfact\SiteCore\EventListener;

use Bitrix\Highloadblock\HighloadBlockTable;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\User\Service\PortalAuthorizeByUser;
use Citfact\SiteCore\User\UserManagers;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\UserDataManager;
use COption;

Loc::loadMessages(__FILE__);

class UserSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ['module' => 'main', 'event' => 'OnAfterUserLogin', 'method' => 'handleAfterUserLogin'],
            ['module' => 'main', 'event' => 'OnAfterUserLogout', 'method' => 'handleAfterUserLogout'],
            ['module' => 'main', 'event' => 'OnBeforeProlog', 'method' => 'handleOnBeforeProlog'],
            ['module' => 'main', 'event' => 'OnBeforeUserAdd', 'method' => 'handleBeforeUserAdd'],
            ['module' => 'main', 'event' => 'OnBeforeUserUpdate', 'method' => 'handleBeforeUserUpdate'],
            ['module' => 'main', 'event' => 'OnBeforeEventAdd', 'method' => 'sendMailNewUserEventAddHandler'],
            ['module' => 'main', 'event' => 'OnAfterUserUpdate', 'method' => 'resetUserContragentsList'],
            ['module' => 'main', 'event' => 'OnAfterUserRegister', 'method' => 'setNewsletter'],
        );
    }

    public static function setNewsletter(&$arFields)
    {
        global $USER;
        $user = new \CUser;

        $user->Update($arFields['USER_ID'], ['UF_EMAIL_NEWS' => 1, 'UF_EMAIL_PROMOTIONS' => 1]);
    }

    public static function resetUserContragentsList(&$arFields)
    {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag('user_contragent_list');
    }

    public static function sendMailNewUserEventAddHandler(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId)
    {

        if (!empty($arFields['UF_TIN']) && $event === 'NEW_USER') {

            $isRegularUser = UserDataManager\UserDataManager::isRegularUser();

            if (!$isRegularUser) {
                return;
            }

            $core = Core::getInstance();
            $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
            $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
            $entity = HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            $rsData = $entity_data_class::getList(array(
                'select' => array('*'),
                'filter' => ['UF_INN' => $arFields['UF_TIN']],
            ));
            
            $contragent = null;

            if ($el = $rsData->fetch()) {
                $contragent = $el;
            }

            if (null !== $contragent) {

                $manager = UserManagers::getManagerByContragent($contragent['UF_XML_ID']);
                $assistants = UserManagers::getAssistantsByContragent($contragent['UF_XML_ID']);
                $assistantsEmails = [];

                if (!empty($assistants)) {
                    foreach ($assistants as $assistant) {
                        $assistantsEmails[] = trim($assistant['EMAIL']);
                    }
                }

                $assistantsEmailsRow = implode(',', $assistantsEmails);

                $arFields['EMAIL_MANAGER_ASSISTANT_BY_USER'] = $assistantsEmails
                    ? implode(',', [trim($manager['EMAIL']), $assistantsEmailsRow])
                    : trim($manager['EMAIL']);

            } else {

                $regionId = reset($arFields['UF_REGIONS']);
                $managerAssistantGroups = $core->GetGroupByCode($core::USER_GROUP_MANAGER.'|'.$core::USER_GROUP_ASSISTANT);

                $filter = ['GROUPS_ID' => $managerAssistantGroups, 'UF_MAIN_REGION' => $regionId];
                $params['SELECT'] = ['UF_MAIN_REGION'];
                $usersDB = \CUser::GetList($by = '', $order = '', $filter, $params);

                if ($user = $usersDB->GetNext()) {
                    $arFields['EMAIL_MANAGER_ASSISTANT_BY_USER'] = trim($user['EMAIL']);

                } else {

                    $filter = ['GROUPS_ID' => $managerAssistantGroups, 'UF_REGION' => $regionId];
                    $usersDB = \CUser::GetList($by = '', $order = '', $filter, $params);

                    while ($user = $usersDB->GetNext()) {
                        $managersEmails[] = trim($user['EMAIL']);
                    }

                    if (!empty($managersEmails)) {
                        $arFields['EMAIL_MANAGER_ASSISTANT_BY_USER'] = implode(',', $managersEmails);
                    } else {
                        $arFields['EMAIL_MANAGER_ASSISTANT_BY_USER'] = COption::GetOptionString("main", "email_from");
                    }

                }

            }

        }

    }

    public static function handleBeforeUserAdd(&$arFields)
    {

        $core = Core::getInstance();
        $managerGroupId = reset($core->GetGroupByCode($core::USER_GROUP_MANAGER));
        $assistantGroupId = reset($core->GetGroupByCode($core::USER_GROUP_ASSISTANT));

        global $APPLICATION;

        $strException = '';

        foreach ($arFields['GROUP_ID'] as $group) {

            if ($group['GROUP_ID'] == $managerGroupId || $group['GROUP_ID'] == $assistantGroupId) {

                if(empty($arFields['NAME'])) {
                    $strException .= 'Пожалуйста, введите имя.';
                }

                if(empty($arFields['LAST_NAME'])) {
                    $strException .= "\n" . 'Пожалуйста, введите фамилию.';
                }


                if(empty($arFields['PERSONAL_PHONE'])) {
                    $strException .= "\n" . 'Пожалуйста, введите телефон.';
                }

            }

        }

        if (!empty($strException)) {
            $APPLICATION->throwException($strException);
            return false;
        }

    }

    public static function handleBeforeUserUpdate(&$arFields)
    {

        $core = Core::getInstance();
        $managerGroupId = reset($core->GetGroupByCode($core::USER_GROUP_MANAGER));
        $assistantGroupId = reset($core->GetGroupByCode($core::USER_GROUP_ASSISTANT));

        global $APPLICATION;

        $strException = '';

        foreach ($arFields['GROUP_ID'] as $group) {

            if ($group['GROUP_ID'] == $managerGroupId || $group['GROUP_ID'] == $assistantGroupId) {

                if(empty($arFields['NAME'])) {
                    $strException .= 'Пожалуйста, введите имя.';
                }

                if(empty($arFields['LAST_NAME'])) {
                    $strException .= "\n" . 'Пожалуйста, введите фамилию.';
                }
                

                if(empty($arFields['PERSONAL_PHONE'])) {
                    $strException .= "\n" . 'Пожалуйста, введите телефон.';
                }

            }

        }

        if (!empty($strException)) {
            $APPLICATION->throwException($strException);
            return false;
        }
    }



    /**
     * после авторизации пользователя в сессию подставляем его контрагента и тип цены
     * вероятно не нужный обработчик
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function handleAfterUserLogin()
    {
        UserDataManager\UserDataManager::getUserContragentXmlID();
        UserDataManager\UserDataManager::getUserPriceType();
        $_SESSION["auth"] = 'Y';
    }

    /**
     * после разоавторизации пользователя удаляем из сессии его контрагента и тип цены
     * также удаляем данные сессии менеджера, если менеджер заходил из-под пользователя и потом вышел
     */
    public static function handleAfterUserLogout()
    {
        UserDataManager\UserDataManager::clearContrAgent();
        UserDataManager\UserDataManager::clearUserPriceType();
        $_SESSION["auth"] = 'N';

        if (isset($_SESSION[PortalAuthorizeByUser::SESSION_KEY])) {
            $portalAuthorizeByUser = new PortalAuthorizeByUser();
            $portalAuthorizeByUser->unsetSessionData();
        }

    }


    /**
     * если юзер авторизован и контрагент отсутствует в сессии заполняем силом
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function handleOnBeforeProlog()
    {
        global $USER;
        (new \Citfact\SiteCore\Tools\VoteManager())->init();
        if ($USER->IsAuthorized() && UserDataManager\UserDataManager::getUserContragentXmlID() == false) {
            $isRegularUser = UserDataManager\UserDataManager::isRegularUser();
            if ($isRegularUser) {
                UserDataManager\UserDataManager::setDefaultContragentUser($USER->GetID());
            } else {

                UserDataManager\UserDataManager::setDefaultContragentManager($USER->GetID());
            }


            UserDataManager\UserDataManager::getUserPriceType();
            UserDataManager\UserDataManager::getUserContragentXmlID();
        }

        if ($USER->IsAuthorized() == false) {
            UserDataManager\UserDataManager::clearContrAgent();
            UserDataManager\UserDataManager::clearUserPriceType();
            $_SESSION["auth"] = 'N';
        } else {
            $_SESSION["auth"] = 'Y';
        }
    }
}
