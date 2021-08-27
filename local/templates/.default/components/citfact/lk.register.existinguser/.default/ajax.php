<?php

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Citfact\SiteCore\User\UserRepository;
use \Citfact\SiteCore\UserDataManager\UserDataManager;
use \Citfact\SiteCore\User\UserHelper;

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = [
    'SUCCESS' => false,
    'MESSAGE' => '',
];

try {
    global $USER;
    if ($USER->IsAuthorized() == false) {
        throw new \Exception('Not auth');
    }

    $hasAccess = UserRepository::checkUserInGroup($USER->GetID(), ['MANAGER', 'ASSISTANT']);
    if ($hasAccess == false) {
        throw new \Exception('Not access');
    }

    $request = Context::getCurrent()->getRequest();
    $userSearchString = $request->get('user');
    $xmlContragent = $request->get('contragent');

    $contragentId = 0;
    if ($xmlContragent) {
       $contragentId = UserDataManager::getContagentIdByXmlId($xmlContragent);
    }

    $arUsers = [];
    if (strlen($userSearchString) >= 3 && $contragentId) {
        $userSearchString = $userSearchString.'%';
        $resUsers = UserTable::getlist([
            'filter' => [
                [
                    'LOGIC' => 'OR',
                    ['NAME' => $userSearchString],
                    ['LAST_NAME' => $userSearchString],
                    ['SECOND_NAME' => $userSearchString],
                    ['EMAIL' => $userSearchString],
                ],
                'ACTIVE' => 'Y',
                ],
            'select' => ['NAME', 'LAST_NAME', 'SECOND_NAME', 'EMAIL', 'ID', 'UF_CONTRAGENT_IDS', 'UF_CONTRAGENT_ID']
        ]);
        while ($arUser = $resUsers->fetch()) {
            if (!$arUser['UF_CONTRAGENT_IDS'] || !in_array($contragentId, $arUser['UF_CONTRAGENT_IDS'])) {
                $arUser['FULL_NAME'] = UserHelper::getFullNameByUser($arUser);
                $arUsers[] = $arUser;
            }
        }
    }

    $result['SUCCESS'] = true;
    $result['MESSAGE'] = $arUsers;

} catch (\Exception $exception) {
    $result['MESSAGE'] = $exception->getMessage();
}

echo json_encode($result);