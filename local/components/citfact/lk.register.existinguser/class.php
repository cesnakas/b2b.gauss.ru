<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\ContragentHelper\ContragentHelper;
use Citfact\SiteCore\User\UserRepository;
use Citfact\SiteCore\UserDataManager\UserDataManager;

Loc::loadMessages(__FILE__);

class RegisterExistingUserComponent extends \CBitrixComponent
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

        if (!empty($request->getPost('register_existing_user'))) {
            $this->registerExistingUser($contragentData);
        }

        $this->IncludeComponentTemplate($contragentData);
    }

    public function registerExistingUser($contragentData)
    {
        global $USER;
        $hasAccess = UserRepository::checkUserInGroup($USER->GetID(), $this::$groupCodes);
        if (!$hasAccess) {
            $this->arResult['EXISTING_USER_REGISTERED'] = 'FAIL';
            $this->arResult['ERROR'] = 'Недостаточно прав для регистрации указанного пользователя. У вас должны быть права менеджера или асситента.';
            return;
        }

        $request = Context::getCurrent()->getRequest();
        $userId = $request->getPost('user_id');
        $userContragent = UserRepository::getUserById($userId);
        if (!$userContragent) {
            $this->arResult['EXISTING_USER_REGISTERED'] = 'FAIL';
            $this->arResult['ERROR'] = 'Пользователь не найден';
            return;
        }

        $userContragents = $userContragent['UF_CONTRAGENT_IDS'];

        $contragentId = UserDataManager::getContagentIdByXmlId($contragentData['UF_XML_ID']);

        if (in_array($contragentId, $userContragents)) {
            $this->arResult['EXISTING_USER_REGISTERED'] = 'FAIL';
            $this->arResult['ERROR'] = 'Указанный пользователь уже входит в список пользователей данного юридического лица';
            return;
        }
        $userContragents[] = $contragentId;

        $cuser = new \CUser();
        $userFields = Array(
            "UF_CONTRAGENT_IDS" => $userContragents,
            "UF_ACTIVATE_PROFILE" => 'да',
        );
        if (!$userContragent['UF_CONTRAGENT_ID']) {
            $userFields['UF_CONTRAGENT_ID'] = $contragentId;
        }
        $isSuccess = $cuser->Update($userContragent['ID'], $userFields);
        if ($isSuccess) {
            $eventID = CEvent::Send('USER_ADDED_TO_COMPANY_BY_MANAGER', Bitrix\Main\Context::getCurrent()->getSite(), array(
                "USER_ID" => $userContragent['ID'],
                "EMAIL_TO" => $userContragent["EMAIL"],
                "COMPANY_NAME" => $contragentData['UF_NAME'],
            ));

            $this->arResult['EXISTING_USER_REGISTERED'] = 'SUCCESS';
        } else {
            $this->arResult['EXISTING_USER_REGISTERED'] = 'FAIL';
            $this->arResult['ERROR'] = $cuser->LAST_ERROR;
        }
    }
}


