<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Citfact\Sitecore\Location\LocHelper;
use Citfact\SiteCore\User\UserRepository;
use Citfact\SiteCore\User\UserSettings;
use Citfact\Sitecore\KindAction\KindActionManager;
use Citfact\Sitecore\SubAccount\SubAccountManager;

class MainProfileComponent extends CBitrixComponent implements Controllerable
{
    /**
     * @var UserSettings
     */
    private $userSettings;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SubAccountManager
     */
    private $subAccManager;

    /**
     * @var KindActionManager
     */
    private $kindActionManager;

    /** Файл шаблона
     * @var string
     */
    private $page = 'personal';

    /**
     * Тип пользователя
     * @var string
     */
    public $userType;

    /**
     * Массив со списком ошибок, полученных в процессе работы компонента
     * @var array of errors.
     */
    private $errors = [];

    /** Был ли вызов ajax запросом
     * @var bool
     */
    public $isAjax = false;

    /**
     * Успешное выполнение обработки данных после ajax запроса
     * @var bool
     */
    private $isSuccess = false;

    /**
     * @var array
     */
    protected $request;

    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

        Loc::loadMessages(__FILE__);
    }

    /**
     * Проверяет и подготавливает все переданные в компонент параметры.
     * Все действия по модификации $arParams здесь
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!(int)$arParams['USER_ID']) {
            $arParams['USER_ID'] = $GLOBALS["USER"]->GetID();
        }

        //$this->userType = UserRepository::getUserType($arParams['USER_ID']);
        //if ($this->userType == UserRepository::USER_TYPE_BUYER_LEGAL_SUBACCOUNT) {
         //   $arParams['USER_ID'] = $this->subAccManager->getParentUserId($arParams['USER_ID']);
        //}

        if ($arParams['PAGE']) {
            $this->page = $arParams["PAGE"];
        }
/*
        if (empty($arParams['REQUIRED_FIELDS'])) {
            $arParams['REQUIRED_FIELDS'] = [
                UserRepository::USER_TYPE_BUYER_INDIVIDUAL => [
                    'NAME',
                    'EMAIL',
                    //'PERSONAL_PHONE'
                ],
                UserRepository::USER_TYPE_MANUFACTURER => [
                    'UF_RESPONSIBLE_NAME',
                    'UF_RESPONSIBLE_PHONE',
                    'UF_RESPONSIBLE_EMAIL',
                    'UF_ACTUAL_ADDRESS'
                ],
                UserRepository::USER_TYPE_BUYER_LEGAL => [
                    'WORK_COMPANY',
                    'EMAIL',
                    'UF_KIND_ACTIVE',
                    'UF_INN',
                    'UF_LEGAL_ADDRESS',
                    'UF_ACTUAL_ADDRESS',
                    'UF_RESPONSIBLE_NAME',
                    'UF_RESPONSIBLE_PHONE',
                    'UF_RESPONSIBLE_EMAIL'
                ],
                UserRepository::USER_TYPE_BUYER_LEGAL_SUBACCOUNT => [
                    'NAME',
                    'PERSONAL_PHONE'
                ]
            ];
        }
*/
        if (empty($arParams['REQUIRED_FIELDS'])) {
            $arParams['REQUIRED_FIELDS'] = [
                    'NAME',
                    'EMAIL',
                    'PERSONAL_PHONE',
            ];
        }
        return $arParams;
    }

    /**
     * Настройки для action
     * @return array
     */
    public function configureActions()
    {
        return [
            'profileChange' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(
                        [
                            ActionFilter\HttpMethod::METHOD_GET,
                            ActionFilter\HttpMethod::METHOD_POST
                        ]
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    /** Изменят данные пользователя
     * Вызывается ajax запросом
     * @return mixed
     */
    public function profileChangeAction()
    {
        $this->isAjax = true;
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        $this->request = $request;

        if (\COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y') {
            //possible encrypted user password
            $sec = new \CRsaSecurity();
            if (($arKeys = $sec->LoadKeys())) {
                $sec->SetKeys($arKeys);
                $errno = $sec->AcceptFromForm(array('NEW_PASSWORD', 'NEW_PASSWORD_CONFIRM'));
                if ($errno == \CRsaSecurity::ERROR_SESS_CHECK)
                    $this->errors[] = Loc::getMessage("main_profile_sess_expired");
                elseif ($errno < 0)
                    $this->errors[] = Loc::getMessage("main_profile_decode_err", array("#ERRCODE#" => $errno));
            }
        }

        if ($this->checkRequiredFields($request) && empty($this->errors)) {

            $this->isSuccess = false;
            $obUser = new \CUser;

            $arEditFields = array(
                //"TITLE",
                "NAME",
                "EMAIL",
                "LOGIN",
                "PERSONAL_PHONE",
                "WORK_COMPANY",
            );

            $arFields = [];
            foreach ($arEditFields as $field) {
                if (isset($request[$field])) {
                    $arFields[$field] = $request[$field];
                }
            }

            if ($request["NEW_PASSWORD"] <> '') {
                $arFields["PASSWORD"] = $request["NEW_PASSWORD"];
                $arFields["CONFIRM_PASSWORD"] = $request["NEW_PASSWORD_CONFIRM"];
            }

            $GLOBALS['USER_FIELD_MANAGER']->EditFormAddFields("USER", $arFields);

            if (!$obUser->Update($this->arParams["USER_ID"], $arFields)) {
                $this->errors[] = $obUser->LAST_ERROR;
            }

        }

        if (empty($this->errors)) {
            $this->isSuccess = true;
        }

        return $this->executeComponent();
    }

    /**
     * @throws Exception
     */
    protected function makeResult()
    {
        $rsUser = \CUser::GetByID($this->arParams["USER_ID"]);
        if (!$arResult["arUser"] = $rsUser->GetNext(false)) {
            $arResult["ID"] = 0;
        }

        if (empty($this->errors)) {
            static $skip = array("PERSONAL_PHOTO" => 1, "WORK_LOGO" => 1, "forum_AVATAR" => 1, "blog_AVATAR" => 1);
            foreach ($this->request as $k => $val) {
                if (!isset($skip[$k])) {
                    if (!is_array($val)) {
                        $val = htmlspecialcharsex($val);
                    }
                    $arResult["arUser"][$k] = $val;
                }
            }
        }

        $arResult["FORM_TARGET"] = $GLOBALS['APPLICATION']->GetCurPage(false);
        $arResult["BX_SESSION_CHECK"] = bitrix_sessid_post();

        $arResult['BONUS_SUM'] = '';
        if ($res = \CSaleUserAccount::GetByUserID($GLOBALS['USER']->GetID(), "RUB")) {
            $arResult['BONUS_SUM'] = SaleFormatCurrency($res["CURRENT_BUDGET"], $res["CURRENCY"]);
        }

        // Если задействована работа с Договором
        if ($arResult["arUser"]['UF_CONTRACT_REQUIRED']) {
            // Если Договор не подписан, получить ссылку на файл шаблона Договора
            if (!$arResult["arUser"]['UF_CONTRACT_SIGNED']) {
                $arContract = $this->userSettings->getByKey($this->arParams["USER_ID"], UserSettings::CONTRACT);

                if ((int)($contractTplFileId = $arContract[UserSettings::CONTRACT_TEMPLATE_FILE_ID])) {
                    $arFile = \CFile::GetFileArray($contractTplFileId);
                    if ($arFile["SRC"]) {
                        $arResult['CONTRACT_TPL_SRC'] = $arFile["SRC"];
                        $arResult['CONTRACT_TPL_FILE_ID'] = $contractTplFileId;
                    }
                }
            }

            // Если Договор загружен, получить ссылку на файл загруженного договора
            $arResult["CONTRACT_IS_UPLOADED"] = false;
            if ((int)$arResult['arUser']['UF_CONTRACT']) {
                $arFile = \CFile::GetFileArray($arResult['arUser']['UF_CONTRACT']);
                if ($arFile["SRC"]) {
                    $arResult["CONTRACT_IS_UPLOADED"] = true;
                    $arResult["CONTRACT_UPLOADED_SRC"] = $arFile["SRC"];
                    $arResult["CONTRACT_UPLOADED_FILE_ID"] = $arFile["ID"];
                }
            }
        }

        // Получить список "Виды деятельности" для select
        /*
        $arAllKindActive = $this->kindActionManager->getAllData();
        $userKindActive = ($arResult["arUser"]['UF_KIND_ACTIVE']) ?: "";
        $arKindOfActive = [];
        foreach ($arAllKindActive as $arActive) {
            $arKindOfActive[$arActive['ID']] = [
                "NAME_RU" => $arActive["UF_NAME"],
                "NAME_EN" => $arActive["UF_NAME_EN"],
                "SELECTED" => ($arActive["ID"] == $userKindActive),
            ];
        }
        $arResult["KINDS_OF_ACTIVE"] = $arKindOfActive;
*/
        // местоположение
        if ($arResult["arUser"]["UF_LOCATION"]) {
            $arResult["arUser"]["UF_LOCATION"] = LocHelper::getNameByCode($arResult["arUser"]["UF_LOCATION"]);
        }

        // страна отправления DHL
        if ($arResult["arUser"]["UF_DHL_COUNTRY"]) {
            // России в базе нету
            if ($arResult["arUser"]["UF_DHL_COUNTRY"] == 'RU') {
                $arResult["arUser"]["UF_DHL_COUNTRY"] = Loc::getMessage('RUSSIA');

            } else {
                Loader::includeModule('softpodkluch.dhl');
                $countries = SoftpodkluchDhlCommon::getCountries();
                foreach ($countries as $country) {
                    if($country['ems_code'] == $arResult["arUser"]["UF_DHL_COUNTRY"]) {
                        $arResult["arUser"]["UF_DHL_COUNTRY"] = $country['ems_name'];
                        break;
                    }
                }
            }
        }

        // Установить тип пользователя
        //$arResult["IS_SUBACC"] = ($this->userType == UserRepository::USER_TYPE_BUYER_LEGAL_SUBACCOUNT);
        //$arResult["IS_LEGAL"] = ($this->userType == UserRepository::USER_TYPE_BUYER_LEGAL);
        //$arResult["IS_MNFCTR"] = ($this->userType == UserRepository::USER_TYPE_MANUFACTURER);

        $this->arResult = $arResult;

        $this->arResult["SUCCESS"] = $this->isSuccess;
    }

    /**
     * Реализует жизненный цикл компонента
     */
    public function executeComponent()
    {
        try {

            $this->setFrameMode(false);
            $this->makeResult();

        } catch (Exception $e) {

            $this->errors[$e->getCode()] = $e->getMessage();
        }

        $this->formatResultErrors();

        // AJAX-режим или нет
        if ($this->isAjax === true) {

            ob_start();
            $this->includeComponentTemplate($this->page);
            $this->arResult['RESPONSE']['html'] = ob_get_contents();
            ob_end_clean();

        } else {
            $this->includeComponentTemplate($this->page);
        }

        return $this->arResult;
    }

    /**
     * Возвращает ключи из $arParams, которые должны участвовать в ajax-запросе к методу компонента
     * @return array|null
     */
    protected function listKeysSignedParameters()
    {
        return [
            'USER_ID',
            'PAGE',
        ];
    }

    /**
     * Проверить наличие значений обязательных полей
     * @param $request
     * @return bool
     */
    private function checkRequiredFields($request)
    {
        // получить список обязательных полей
        $arRequiredFields = $this->arParams['REQUIRED_FIELDS'];

        $flag = true;
        foreach ($arRequiredFields as $field) {
            if (!isset($request[$field]) || empty($request[$field])) {
                $this->errors[] = Loc::getMessage(
                    'REQUIRED_FIELD_ERROR',
                    ["FIELD_NAME" => Loc::getMessage(str_replace('UF_', '', $field))]
                );
                $flag = false;
            }
        }

        return $flag;
    }

    /**
     * Добавляет все ошибки в $arResult
     * @return void
     */
    protected function formatResultErrors()
    {
        if (!empty($this->errors)) {
            $this->arResult['ERRORS'] = $this->errors;
        }
    }
}
