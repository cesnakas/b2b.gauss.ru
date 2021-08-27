<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\User\UserRepository;
use Citfact\SiteCore\ContragentHelper\ContragentHelper;
use Citfact\SiteCore\UserDataManager\UserDataManager;

Loc::loadMessages(__FILE__);

class RegisterNewUserComponent extends \CBitrixComponent
{
    public static $groupCodes = [
        'MANAGER',
        'ASSISTANT',
    ];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $request = Context::getCurrent()->getRequest();
        $contragentGuid = $this->arParams['contragent_guid'];
        $contragentData = ContragentHelper::getContragentDataByGuid($contragentGuid);
        $this->arResult['contragent'] = $contragentData;

        if (!empty($request->getPost('user_fio'))) {
            $this->registerNewUser($contragentData);
        }

        $this->IncludeComponentTemplate();
    }

    public function registerNewUser($contragentData)
    {
        global $USER;
        $hasAccess = UserRepository::checkUserInGroup($USER->GetID(), $this::$groupCodes);
        if (!$hasAccess) {
            $this->arResult['NEW_USER_REGISTERED'] = 'FAIL';
            $this->arResult['ERROR'] = 'Недостаточно прав для регистрации нового пользователя. У вас должны быть права менеджера или асситента.';
            return;
        }


        $GroupCodeToID = (new Citfact\DataCache\UserData\UserGroupID())->getAllData();

        $request = Context::getCurrent()->getRequest();
        $contragentGuid = $request->getQuery('contragent_guid');
        $contragentId = UserDataManager::getContagentIdByXmlId($contragentGuid);
        $userFio = $request->getPost('user_fio');
        $userPhone = $request->getPost('user_phone');
        $userEmail = $request->getPost('user_email');
        $leaderFio = $request->getPost('leader_fio');
        $userFio = explode(" ", $userFio);
        $userLastName = $userFio[0];
        $userName = $userFio[1];
        $userSecond = $userFio[2];

        $cuser = new \CUser();
        $userPassword = randString(8);
        $userGroups = [
            $GroupCodeToID['ALL'],
            $GroupCodeToID['RATING_VOTE'],
            $GroupCodeToID['RATING_VOTE_AUTHORITY'],
        ];
        $userFields = Array(
            "NAME" => $userName,
            "LAST_NAME" => $userLastName,
            "SECOND_NAME" => $userSecond,
            "EMAIL" => $userEmail,
            "PERSONAL_PHONE" => $userPhone,
            "LOGIN" => $userEmail,
            "LID" => "ru",
            "ACTIVE" => "Y",
            "GROUP_ID" => $userGroups,
            "PASSWORD" => $userPassword,
            "CONFIRM_PASSWORD" => $userPassword,
            "UF_CONTRAGENT_IDS" => array($contragentId),
            "UF_CONTRAGENT_ID" => $contragentId,
            "UF_ACTIVATE_PROFILE" => 'да',
            "UF_LEADER_FIO" => $leaderFio,
        );
        $ID = $cuser->Add($userFields);
        if (intval($ID) > 0) {
            $arEventFields = [
                "USER_ID" => $ID,
                "EMAIL_TO" => $userEmail,
                "COMPANY_NAME" => $contragentData['UF_NAME'],
                "NAME" => $userName,
                "LAST_NAME" => $userLastName,
                "SECOND_NAME" => $userSecond,
                "EMAIL" => $userEmail,
                "PERSONAL_PHONE" => $userPhone,
                "LOGIN" => $userEmail,
                "PASSWORD" => $userPassword,
                "MANAGER_NAME" => $USER->GetLastName() . " " . $USER->GetFirstName() . " " . $USER->GetSecondName()
            ];
            CEvent::Send('NEW_USER_BY_MANAGER', SITE_ID, $arEventFields);
            $this->arResult['NEW_USER_REGISTERED'] = 'SUCCESS';
            return $ID;
        } else {
            $this->arResult['NEW_USER_REGISTERED'] = 'FAIL';
            $this->arResult['ERROR'] = $cuser->LAST_ERROR;
        }
    }
}

