<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use \Bitrix\Main\UserTable;
use Citfact\DataCache\UserData\UserGroupID;
use Citfact\SiteCore\User\Service\PortalAuthorizeByUser;
use Citfact\SiteCore\UserDataManager\UserDataManager;
use Citfact\Tools\Component\BaseComponent;
use Citfact\Tools\ElementManager;
use Citfact\Tools\Tools;
use Citfact\SiteCore\User\UserRepository;

Loc::loadMessages(__FILE__);

class UserListComponent extends BaseComponent
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
        $requestData = Tools::requestSpecialChars($_REQUEST);
        $this->arResult['REQUEST_DATA'] = $requestData;

        if ($requestData['AUTHORIZE_AS_USER'] == 'Y') {
            $this->authorizeByUserId($requestData['USER_ID']);
        }

        $this->connectItems();
        $this->IncludeComponentTemplate();
    }

    protected function authorizeByUserId($userId)
    {
        global $USER;
        $canAuthorize = UserRepository::checkUserInGroup($USER->getID(), $this::$groupCodes);
        $portalAuthorizeByUser = new PortalAuthorizeByUser();

        if (!$canAuthorize) {
            $this->arResult['AUTHORIZE_AS_USER'] = 'FAIL';
            $this->arResult['ERROR'] = 'Недостаточно прав для авторизации под пользователем. У вас должны быть права менеджера или асситетнта.';
            return;
        }
        $userData = UserRepository::getUserDataByID($userId);
        if (!$userData) {
            $this->arResult['AUTHORIZE_AS_USER'] = 'FAIL';
            $this->arResult['ERROR'] = 'Авторизация невозможна, т.к. не найден пользователь c ID ' . $userId;
            return;
        }

        global $APPLICATION;

        $userFIO = trim($userData['LAST_NAME'] . ' ' . $userData['NAME'] . ' ' . $userData['SECOND_NAME']);
        $portalAuthorizeByUser->saveSessionData($USER->GetID(), $userFIO, $APPLICATION->GetCurDir());
        $USER->Authorize($userId);

        LocalRedirect('/personal/');
    }

    protected function connectItems()
    {
        if (!$this->arParams['CONTRACTOR_GUID']) {
            return;
        }

        $contragentID = UserDataManager::getContagentIdByXmlId($this->arParams['CONTRACTOR_GUID']);
        $rsUsers = UserTable::getlist(array(
            'filter' => array(
                '=UF_CONTRAGENT_IDS' => $contragentID,
            ),
            'select' => ['*', 'UF_DELETE', 'UF_ACTIVATE_PROFILE'],
        ));

        $notInGroups = ['MANAGER', 'ASSISTANT', 'ADMINISTRATOR'];
        while ($arUser = $rsUsers->fetch()) {
            $userID = $arUser['ID'];
            if (!UserRepository::checkUserInGroup($userID, $notInGroups)) {
                if ($arUser['UF_DELETE'] == false && $arUser['UF_ACTIVATE_PROFILE'] == 1) {
                    $arSpecUser[] = $arUser;
                } elseif ($arUser['UF_DELETE'] != false) {
                    $arDeleteUser[] = $arUser;
                }
            }
        }
        $this->arResult['ITEMS'] = $arSpecUser;
        $this->arResult['ITEMS_FOR_DELETE'] = $arDeleteUser;

    }
}

