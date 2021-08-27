<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Citfact\SiteCore\ContragentHelper\ContragentHelper;
use Citfact\SiteCore\User\UserRepository;
use Citfact\Tools\Component\BaseComponent;
use Bitrix\Main\UserTable;
use Citfact\Tools\Tools;
use Bitrix\Main\Mail\Event;

Loc::loadMessages(__FILE__);

class RequestedUserListComponent extends BaseComponent
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
        $contragentGuid = $this->arParams['CONTRAGENT_GUID'];
        $contragentData = ContragentHelper::getContragentDataByGuid($contragentGuid);

        if ($requestData['ACCEPT_USER'] == 'Y') {
            $this->acceptUser($requestData['USER_ID'], $contragentData);
        }

        $managerData = $this->getManagerData();
        if ($requestData['DECLINE_USER'] == 'Y') {
            $this->declineUser($requestData['USER_ID'], $contragentData, $managerData);
        }

        if ($requestData['DELETE_USER'] == 'Y') {
            $this->deleteUser($requestData['USER_ID']);
        }

        $this->connectItems($contragentData, $managerData);
        $this->IncludeComponentTemplate();
    }

    public function acceptUser($userID, $contragentData)
    {
        global $USER;
        $canAccept = UserRepository::checkUserInGroup($USER->getID(), $this::$groupCodes);
        if (!$canAccept) {
            $this->arResult['ACCEPT_USER'] = 'FAIL';
            $this->arResult['ERROR'] = 'Недостаточно прав, чтобы принять заявку (восстановить) пользователя. У вас должны быть права менеджера или ассистента.';
            return;
        }


        $arUser = \Citfact\SiteCore\User\UserRepository::getUserById($userID);
        $listContragents = [$contragentData['ID']];
        if (!empty($arUser['UF_CONTRAGENT_IDS'])) {
            $listContragents = array_merge($listContragents, $arUser['UF_CONTRAGENT_IDS']);
        }

        $user = new \CUser;
        $userFields = Array(
            "UF_ACTIVATE_PROFILE" => '1',
            "UF_DELETE" => '0',
            "UF_CONTRAGENT_ID" => $contragentData['ID'],
            "UF_CONTRAGENT_IDS" => $listContragents,
        );
        $isSuccess = $user->Update($userID, $userFields);
        if ($isSuccess) {
            $userData = UserRepository::getUserDataByID($userID);
            $userEmail = $userData["EMAIL"];
            $arEventFields = [
                "USER_ID" => $userID,
                "EMAIL_TO" => $userEmail,
                "COMPANY_NAME" => $contragentData['UF_NAME'],
            ];
            CEvent::Send('USER_ADDED_TO_COMPANY_BY_MANAGER', SITE_ID, $arEventFields);

            $this->arResult['ACCEPT_USER'] = 'SUCCESS';
        } else {
            $this->arResult['ACCEPT_USER'] = 'FAIL';
            $this->arResult['ERROR'] = $user->LAST_ERROR;
        }
    }

    public function deleteUser($userID)
    {
        $arFilter = ["USER_ID" => $userID,];

        $ordersUserDb = \CSaleOrder::GetList(["DATE_INSERT" => "ASC"], $arFilter);
        if ($ordersUserDb = $ordersUserDb->Fetch())
        {
            $this->arResult['ERROR'] = 'Пользователь имеет заказы в модуле Интернет-магазина и не может быть удален.';
            $this->arResult['DELETE'] = 'FAIL';
            return;
        }
        $user = new CUser;
        $user->Delete($userID);
        $this->arResult['DELETE'] = 'SUCCESS';
    }

    public function declineUser($userID, $contragentData, $managerData)
    {
        global $USER;
        $canAccept = UserRepository::checkUserInGroup($USER->getID(), $this::$groupCodes);
        if (!$canAccept) {
            $this->arResult['DECLINE_USER'] = 'FAIL';
            $this->arResult['ERROR'] = 'Недостаточно прав, чтобы отклонить заявку пользователя. У вас должны быть права менеджера или асситетнта.';
            return;
        }
        $user = new CUser;
        $isSuccess = $user->Update($userID, ['UF_DELETE' => 'Y']);
        if ($isSuccess) {
            $userData = UserRepository::getUserDataByID($userID);
            $userEmail = $userData["EMAIL"];
            $arEventFields = [
                "USER_ID" => $userID,
                "EMAIL_TO" => $userEmail,
                "DECLINED_BY_USER" => $managerData['LAST_NAME'] . ' ' . $managerData['NAME'],
                "COMPANY_NAME" => $contragentData['UF_NAME'],
            ];
            CEvent::Send('DECLINED_ADDING_USER_TO_COMPANY', SITE_ID, $arEventFields);

            $this->arResult['DECLINE_USER'] = 'SUCCESS';
        } else {
            $this->arResult['DECLINE_USER'] = 'FAIL';
            $this->arResult['ERROR'] = $user->LAST_ERROR;
        }
    }

    public function connectItems($contragentData, $managerData)
    {
        $res = UserTable::getList([
            'filter' => [
                [
                    '=UF_TIN' => $contragentData['UF_INN']
                ],
                '=UF_ACTIVATE_PROFILE' => 0,
                '=UF_DELETE' => 0
            ],
            'select' => ['*', 'UF_REGIONS', 'UF_CONTRAGENT_IDS', 'UF_ACTIVATE_PROFILE']
        ]);
        $this->arResult['ITEMS'] = $res->fetchAll();
    }

    public function getManagerData()
    {
        global $USER;
        $res = UserTable::getList([
            'filter' => [
                '=ID' => $USER->GetID()
            ],
            'select' => ['*', 'UF_REGIONS', 'UF_CONTRAGENT_IDS', ]
        ]);
        $row = $res->fetch();
        return $row;
    }
}

