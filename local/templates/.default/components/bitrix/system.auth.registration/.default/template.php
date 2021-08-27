<?
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->SetPageProperty("TITLE", "Регистрация");

if ($USER->IsAuthorized()){
    LocalRedirect('/personal/profile/');
} else { ?>
    <? $APPLICATION->IncludeComponent("citfact:main.register", "", [
            "USER_PROPERTY_NAME" => "",
            "SEF_MODE" => "Y",
            "SHOW_FIELDS" => [
                'UF_FIO',
                'PERSONAL_PHONE',
                'EMAIL',
                'WORK_POSITION',
                'PASSWORD',
                'CONFIRM_PASSWORD',
                'UF_COMPANY_NAME',
                'UF_REGIONS',
                'UF_TIN',
                'UF_OFFICE_ADDRESS',
                'UF_FORM_ORGANIZATION',
                'UF_COMPANY_PHONE',
            ],
            "REQUIRED_FIELDS" => [
                'UF_FIO',
                'PERSONAL_PHONE',
                'EMAIL',
                'UF_TIN',
                'UF_FORM_ORGANIZATION',
                'PASSWORD',
                'CONFIRM_PASSWORD',
                'UF_REGIONS',
                'UF_OFFICE_ADDRESS',
            ],
            "AUTH" => "Y",
            "USE_BACKURL" => "Y",
            "SUCCESS_PAGE" => "/personal/registration/?success_register=Y",
            "SET_TITLE" => "N",
            "USER_PROPERTY" => [],
            "SEF_FOLDER" => "/",
            "VARIABLE_ALIASES" => []
        ]
    ); ?>
<? }