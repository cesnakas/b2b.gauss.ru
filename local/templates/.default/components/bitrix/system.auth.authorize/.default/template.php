<?
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->SetPageProperty("TITLE", "Авторизация");

$request = Application::getInstance()->getContext()->getRequest();
?>
<?$APPLICATION->IncludeComponent(
    "citfact:authorize.ajax",
    "page",
    Array(
        "FORM_ID" => "need_auth",
        "REDIRECT_TO" => ""
    )
);?>
