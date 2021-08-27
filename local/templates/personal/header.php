<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\FrameBuffered;
use Citfact\SiteCore\Core;

$core = Core::getInstance();

/**
 * @var $arLang array
 */

global $USER;

CJSCore::Init('jquery'); /// TODO norm call jquery
Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php', LANGUAGE_ID, false);

$dir = $APPLICATION->GetCurDir();
$userId = $GLOBALS["USER"]->GetID();

$rsUser = \CUser::GetByID($userId);
$arResult["arUser"] = $rsUser->GetNext(false);

$page = $APPLICATION->GetCurPage(true);

$UserGroupID = \Citfact\DataCache\UserData\UserGroupID::getInstance();

if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/?backurl='.$page, false, "301 Moved permanently");
}
elseif ($dir !== '/personal/'
    && !$arResult['arUser']['UF_ACTIVATE_PROFILE']
    && false === CSite::InGroup(array($UserGroupID->getByCode('MANAGER'),$UserGroupID->getByCode('ASSISTANT')))) {
    LocalRedirect('/personal/?backurl='.$page, false, "301 Moved permanently");
}

if (
        strpos($dir, '/personal/companies/') !== false
    || strpos($dir, '/personal/profile-manager/') !== false
    || strpos($dir, '/personal/plan-fact-manager/') !== false
    || strpos($dir, '/personal/register/') !== false
) {
    if (true === $USER->IsAuthorized()) {
        if (false === CSite::InGroup(array($UserGroupID->getByCode('MANAGER'),$UserGroupID->getByCode('ASSISTANT')))) {
            LocalRedirect('/personal/', false, "301 Moved permanently");
        }
    }

} 
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <?php
    if (!isDev()) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/local/include/counters/google.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/local/include/counters/yandex.php';
    } ?>
    <?
    $APPLICATION->ShowMeta("robots", false);
    $APPLICATION->ShowMeta("keywords", false);
    $APPLICATION->ShowMeta("description", false);
    $APPLICATION->ShowLink("canonical", null);
    $APPLICATION->ShowHeadStrings();
    $APPLICATION->ShowHeadScripts();
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8<?/*=LANG_CHARSET*/?>">

    <link rel="stylesheet" href="/local/client/build/c.css?<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/c.css'); ?>">

    <? $APPLICATION->ShowViewContent('canonical'); ?>

    <?
    $asset = Asset::getInstance();
    $asset->addCss('/local/templates/shop/styles.css?' . filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/templates/shop/styles.css'));
    $asset->addCss('/bitrix/panel/main/popup.css?' . filemtime($_SERVER['DOCUMENT_ROOT'] . '/bitrix/panel/main/popup.css'));
    $asset->addJs('/bitrix/js/main/core/core_window.js?' . filemtime($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/main/core/core_window.js'));
    ?>

    <script async>
        window.INLINE_SVG_REVISION = <?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/sprite.svg') ?>
    </script>

    <title><?$APPLICATION->ShowTitle();?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="preload" href="/local/client/app/fonts/EtelkaLightPro.woff" as="font" crossorigin/>
    <link rel="preload" href="/local/client/app/fonts/EtelkaMediumPro.woff" as="font" crossorigin/>
    <script src="/local/client/build/c.js?<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/c.js');; ?>" defer></script>
    <script async>
        <?php
        global $USER;
        if ($USER->IsAdmin() || strpos($USER->GetGroups(), $core::USER_GROUP_ID_CONTENT)) {?>
        window.bitrixLoader = true;
        <?php } ?>
        
        window.isDev = <?php echo isDev() === true ? 'true' : 'false'; ?>;
    </script>
</head>
<body>
<div id="panel">
    <?$APPLICATION->ShowPanel();?>
</div>
<?php
$frame = new FrameBuffered('authorize_back');
$frame->begin();
?>
<?php $APPLICATION->IncludeComponent("citfact:portal.user.authorize_back", ".default", [],false ); ?>
<?php $frame->end(); ?>
<div class="lk-aside">
    <div class="lk-aside-sidebar">
        <?$APPLICATION->IncludeComponent("bitrix:menu", "left_personal", [
            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
            "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
            "DELAY" => "N",	// Откладывать выполнение шаблона меню
            "MAX_LEVEL" => "1",	// Уровень вложенности меню
            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
            "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
            "MENU_CACHE_TYPE" => "A",	// Тип кеширования
            "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
            "MENU_THEME" => "green",	// Тема меню
            "ROOT_MENU_TYPE" => "left_personal",	// Тип меню для первого уровня
            "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
            "COMPONENT_TEMPLATE" => "left_personal",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "STATIC",
        ],
            false
        );?>
    </div>
    <div class="lk-aside__main">
        <header class="h">
            <div class="h__top">
                <div class="container">
                    <div class="h__inner">
                        <div class="btn-nav btn-nav--n" data-m-menu-btn="burger">
                            <span class="btn-nav__line"></span>
                        </div>

                        <a href="<?php echo SITE_DIR; ?>" class="h__logo" title="Главная">
                            <svg xmlns="http://www.w3.org/2000/svg" width="132.271" height="50.164" viewBox="0 0 132.271 50.164"><path id="Path_1" data-name="Path 1" d="M171.077,265.2a12.623,12.623,0,0,0,.212-3.092,6.837,6.837,0,0,0-.616-2.543,4.387,4.387,0,0,0-1.351-1.715,3.264,3.264,0,0,0-1.986-.609,5.385,5.385,0,0,0-3.185,1.04,10.449,10.449,0,0,0-2.6,2.721,17.327,17.327,0,0,0-1.9,3.86,22.424,22.424,0,0,0-1.073,4.5,29.822,29.822,0,0,0-.271,3.827,13.389,13.389,0,0,0,.457,3.8,6.573,6.573,0,0,0,1.6,2.88,4.159,4.159,0,0,0,3.125,1.132,5.016,5.016,0,0,0,3.1-1.437,13.641,13.641,0,0,0,2.966-3.708Zm110.294-6.555c.523-2.2,1.318-2.854,2.629-3.211l-.04-1.973a6.4,6.4,0,0,0-5.251,2.318,8.575,8.575,0,0,0-1.079-.5,10.276,10.276,0,0,0-3.768-.616,11.052,11.052,0,0,0-6.833,2.357,10.1,10.1,0,0,0-2.39,2.695,8.73,8.73,0,0,0-1.192,3.463,5.89,5.89,0,0,0,1.073,4.6,11.945,11.945,0,0,0,4.317,3.245c1.218.583,2.417,1.086,3.556,1.589a7.055,7.055,0,0,1,2.476,1.748,3.67,3.67,0,0,1,.583,3.033,5.885,5.885,0,0,1-1.775,3.582,4.842,4.842,0,0,1-3.43,1.377,4.175,4.175,0,0,1-3.211-1.04,3.414,3.414,0,0,1-.708-2.88,6.82,6.82,0,0,1,.98-2.755l-.3-.735a4.466,4.466,0,0,0-2.2-.06,5.766,5.766,0,0,0-1.867.795,4.745,4.745,0,0,0-1.377,1.41,4.687,4.687,0,0,0-.516,1.073,7.633,7.633,0,0,1-5.615,3.675,9.719,9.719,0,0,0,3.258-6.151,5.689,5.689,0,0,0-1.225-4.748,12.854,12.854,0,0,0-4.35-3.092c-.728-.338-1.543-.642-2.417-.947a10.575,10.575,0,0,1-2.112-.947,4.422,4.422,0,0,1-1.808-1.96,5.371,5.371,0,0,1-.152-2.88,5.123,5.123,0,0,1,1.437-2.814,3.815,3.815,0,0,1,2.847-1.225,3.464,3.464,0,0,1,2.695.887,2.948,2.948,0,0,1,.55,2.483,10.325,10.325,0,0,1-1.1,3.311l.119.609a5.243,5.243,0,0,0,4.317-.4,4.135,4.135,0,0,0,1.867-2.847,4.761,4.761,0,0,0-.245-2.178,5.153,5.153,0,0,0-1.318-2.079,7.253,7.253,0,0,0-2.51-1.563,10.276,10.276,0,0,0-3.768-.616,11.052,11.052,0,0,0-6.827,2.357,10.1,10.1,0,0,0-2.39,2.695,8.731,8.731,0,0,0-1.192,3.463,5.917,5.917,0,0,0,1.073,4.6,11.945,11.945,0,0,0,4.317,3.244c1.218.583,2.417,1.086,3.549,1.589a7.147,7.147,0,0,1,2.483,1.748,3.67,3.67,0,0,1,.583,3.033,5.885,5.885,0,0,1-1.775,3.582,4.842,4.842,0,0,1-3.43,1.377,4.2,4.2,0,0,1-3.218-1.04,3.435,3.435,0,0,1-.7-2.88,6.818,6.818,0,0,1,.98-2.755l-.3-.735a4.466,4.466,0,0,0-2.2-.06,5.766,5.766,0,0,0-1.867.795,4.667,4.667,0,0,0-1.377,1.41,4.246,4.246,0,0,0-.675,1.775,4.836,4.836,0,0,0,.185,2.02l.013.04a6.975,6.975,0,0,1-3.615,1.371l-.106.053a2.6,2.6,0,0,1-.947-.364,1.8,1.8,0,0,1-.675-.947,5.905,5.905,0,0,1-.212-1.808,58.5,58.5,0,0,1,.735-7.476c.106-.735.265-1.642.43-2.728s.338-2.185.523-3.311.364-2.225.55-3.3.344-1.993.457-2.728a12.716,12.716,0,0,0,.152-1.53,3.574,3.574,0,0,0-.185-1.351,2.427,2.427,0,0,0-.828-1.1,2.706,2.706,0,0,0-1.715-.735h-1.96l-.98.92c-.444,3.311-.92,6.635-1.41,9.985s-.96,6.681-1.41,9.985a13.864,13.864,0,0,1-3.092,3.8,5.3,5.3,0,0,1-3.211,1.53,2.612,2.612,0,0,1-2.728-1.748,10.837,10.837,0,0,1-.523-4.258q.06-1.222.338-3.337t.642-4.5q.367-2.394.735-4.655c.245-1.51.437-2.781.616-3.8.066-.411.139-.854.185-1.351a2.638,2.638,0,0,0-.245-1.377,2.415,2.415,0,0,0-1.165-1.073,6.17,6.17,0,0,0-2.635-.43h-.616a6.122,6.122,0,0,0-.92.093c-.4.06-.96.132-1.655.212s-1.635.225-2.821.43l-.185,1.351a13.985,13.985,0,0,1,2.2.523,2.743,2.743,0,0,1,1.192.735,1.91,1.91,0,0,1,.457,1.106,7.236,7.236,0,0,1-.06,1.563q-.487,3.615-1.006,7.409c-.344,2.536-.682,4.979-1.013,7.35a15.039,15.039,0,0,0-.139,2.165c-.411,2.536-4,4.7-6.065,4.7a3.5,3.5,0,0,1-1.258-.457,1.982,1.982,0,0,1-.768-.98,5.451,5.451,0,0,1-.245-1.867c.04-.9.119-2.033.245-3.4s.324-3.052.616-5.052q.487-3.556.828-6.032t.609-4.224c.185-1.165.358-2.112.523-2.847s.324-1.371.49-1.9l-1.715-.861-3.615,2.265a5.676,5.676,0,0,0-2.821-1.841,12.475,12.475,0,0,0-3.245-.43,11.786,11.786,0,0,0-5.363,1.285,16.4,16.4,0,0,0-4.562,3.43,20.069,20.069,0,0,0-3.4,4.959,22.125,22.125,0,0,0-1.927,5.939,23.341,23.341,0,0,0-.152,5.211,13.3,13.3,0,0,0,1.192,4.622,8.276,8.276,0,0,0,2.788,3.3,7.957,7.957,0,0,0,4.628,1.258,12.373,12.373,0,0,0,4.132-.795,10.354,10.354,0,0,0,4.377-3.43h.126a7.111,7.111,0,0,0,.152,1.41,3.879,3.879,0,0,0,.583,1.377,3.231,3.231,0,0,0,1.132,1.04,3.655,3.655,0,0,0,1.808.4h.675c.093,0,.205-.007.338-.013a14.341,14.341,0,0,0,5.357-.722,11.409,11.409,0,0,0,4.913-3.543,7.051,7.051,0,0,0,.622,1.463,5.455,5.455,0,0,0,2.053,2.053,6.552,6.552,0,0,0,3.337.761,12.4,12.4,0,0,0,4.132-.795,10.408,10.408,0,0,0,4.383-3.43h.119a7.111,7.111,0,0,0,.152,1.41,3.879,3.879,0,0,0,.583,1.377,3.231,3.231,0,0,0,1.132,1.04,3.655,3.655,0,0,0,1.808.4h.675c.086,0,.179-.007.285-.013a14.106,14.106,0,0,0,4.933-.669l.291-.053.007-.053a11.488,11.488,0,0,0,2.755-1.536,5.618,5.618,0,0,0,.4.4,7.23,7.23,0,0,0,2.51,1.377,12.4,12.4,0,0,0,3.979.55,18.191,18.191,0,0,0,4.238-.457h.139a17.344,17.344,0,0,0,6.032-.735,11.662,11.662,0,0,0,4.191-2.715c.007.033.02.073.027.106a4.055,4.055,0,0,0,1.225,1.867,7.29,7.29,0,0,0,2.51,1.377,12.43,12.43,0,0,0,3.98.55q5.393,0,8.515-2.45A9.718,9.718,0,0,0,281,275.668a5.689,5.689,0,0,0-1.225-4.748,12.856,12.856,0,0,0-4.35-3.092c-.728-.338-1.543-.642-2.417-.947a10.405,10.405,0,0,1-2.112-.947,4.384,4.384,0,0,1-1.808-1.96,5.372,5.372,0,0,1-.152-2.88,5.09,5.09,0,0,1,1.444-2.814,3.815,3.815,0,0,1,2.847-1.225,3.455,3.455,0,0,1,2.695.887,2.2,2.2,0,0,1,.411.675,5.592,5.592,0,0,0-1.132,2.4c-.245,1.457-.324,3.284,1.649,3.529a4.469,4.469,0,0,0,2.953-.609,4.135,4.135,0,0,0,1.867-2.847,4.761,4.761,0,0,0-.245-2.178,1.9,1.9,0,0,0-.1-.271ZM199.431,264.7c-.424,3.728-1.1,7.456-1.629,11.164a13.634,13.634,0,0,1-2.973,3.708,4.965,4.965,0,0,1-3.092,1.437,4.169,4.169,0,0,1-3.125-1.132,6.64,6.64,0,0,1-1.6-2.88,13.39,13.39,0,0,1-.457-3.8,30.92,30.92,0,0,1,.278-3.827,22.428,22.428,0,0,1,1.073-4.5,17.048,17.048,0,0,1,1.9-3.86,10.246,10.246,0,0,1,2.6-2.721,5.386,5.386,0,0,1,3.185-1.04,3.283,3.283,0,0,1,1.993.609,4.51,4.51,0,0,1,1.351,1.715,6.837,6.837,0,0,1,.609,2.543c.02.291.026.6.02.907-.013.563-.073,1.126-.139,1.682m-30.684,15.759a10.581,10.581,0,0,1-4.291,3.43,11.645,11.645,0,0,1-4.039.795,7.909,7.909,0,0,1-4.628-1.258,8.321,8.321,0,0,1-2.788-3.3,13.053,13.053,0,0,1-1.192-4.622,23.351,23.351,0,0,1,.152-5.211,22.523,22.523,0,0,1,1.927-5.939,19.892,19.892,0,0,1,3.4-4.96,16.4,16.4,0,0,1,4.562-3.43,11.682,11.682,0,0,1,5.363-1.285,12.476,12.476,0,0,1,3.245.43,5.688,5.688,0,0,1,2.821,1.841l.715.159c2.106-2.245,3.152-3.6,6.476-3.615l.04,1.973c-1.311.358-2.112,1.006-2.629,3.211l-.033.172q-.338,1.659-.649,3.675t-.642,4.622q-.338,2.6-.768,6.218-.795,6.247-1.563,10.965a72.934,72.934,0,0,1-1.748,8.178,26.982,26.982,0,0,1-2.265,5.761,11.251,11.251,0,0,1-3.125,3.648,8.662,8.662,0,0,1-2.94,1.351,13.1,13.1,0,0,1-3.066.364,11.872,11.872,0,0,1-2.3-.245,9.72,9.72,0,0,1-2.331-.761,6.429,6.429,0,0,1-.788-.45,4.278,4.278,0,0,1-1.874-6.039c.94-1.536,3.364-1.02,4.344-.834l1.53,1.662a7.909,7.909,0,0,0-.371,2.265,2.777,2.777,0,0,0,.338,1.5,1.389,1.389,0,0,0,.861.642,2.291,2.291,0,0,0,1.165-.06,4.229,4.229,0,0,0,1.318-.675q2.513-1.9,3.8-6.734a85.047,85.047,0,0,0,2.086-13.415h-.119Z" transform="translate(-151.729 -253.461)" fill="#232323"/></svg>
                        </a>

                        <?php
                        require_once ($_SERVER["DOCUMENT_ROOT"] . '/local/include/areas/search/search-header.php');
                        ?>

                        <div class="h-tel">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-h-tel'/>
                            </svg>
                            <div class="h-tel__inner">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "email",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "EDIT_TEMPLATE" => "",
                                        "CLASS" => "h-tel__link h-tel__link--mail",
                                        "PATH" => "/local/include/areas/header/mail.php",
                                        "SCHEMA_ORG" => "Y",
                                        "COMPONENT_TEMPLATE" => "email"
                                    ),
                                    false
                                ); ?>
                                <?php
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "phone",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/include/areas/header/phone.php",
                                        "CLASS" => "h-tel__link"
                                    ),
                                    false
                                ); ?>
                                <a href="/local/include/modals/callback.php"
                                   data-modal="ajax"
                                   title="Заказать звонок"
                                   class="h-tel__modal">
                                    Заказать звонок
                                </a>
                                <a href="/local/include/modals/info.php"
                                   title="Информация" data-modal="ajax"
                                   data-modal-info
                                   class="inline__modal" style="display:none;">
                                </a>
                                <a href="/local/include/modals/client-questionnaire.php"
                                   data-modal-voting-form data-modal="ajax"
                                   title="Опрос клиентов" class="inline__modal" style="display:none;">
                                </a>
                            </div>
                        </div>

                        <?
                        require_once ($_SERVER["DOCUMENT_ROOT"] . '/local/include/areas/header/personal/auth.php');
                        ?>
                    </div>
                </div>
            </div>
            <div class="h__m">
                <div class="container">
                    <div class="h__inner">

                        <?php
                        require_once ($_SERVER["DOCUMENT_ROOT"] . '/local/include/areas/search/search-mobile.php');
                        ?>

                        <?
                        require_once ($_SERVER["DOCUMENT_ROOT"] . '/local/include/areas/header/personal/m-auth.php');
                        ?>

                    </div>
                </div>
            </div>
        </header>
        <main class="main">
            <div class="container">
                <div class="lk">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "top_catalog",
                        Array(
                            "PATH" => "",
                            "SITE_ID" => "s1",
                            "START_FROM" => "0",
                        ),
                        false
                    );?>
                    <div class="title"><?php $APPLICATION->ShowTitle(false); ?></div>