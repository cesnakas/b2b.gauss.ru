<?
define('NEED_AUTH', true);
//require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/composite_first_start_cookie_fix.php');
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Список компаний");
?>
<? global $USER; ?>
<? if ($USER->IsAuthorized()): ?>

    <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => SITE_DIR . "local/include/areas/kp/menu.php",
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "",
            "AREA_FILE_RECURSIVE" => "Y",
            "EDIT_TEMPLATE" => "standard.php",
        ),
        false
    ); ?>
    <? $APPLICATION->IncludeComponent("citfact:lk.companies", ".default",
        array(
            'SEF_FOLDER' => '/personal/companies/',
        ),
        false
    ); ?>
<? endif ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>