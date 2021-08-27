<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\ModalManager;
use Citfact\SiteCore\Tools\VoteManager;


$core = Core::getInstance();

$basketHelper = new Citfact\SiteCore\Order\Basket;
$basketHelper->setBasketItemsOnLoad();

$pageProperties = $APPLICATION->GetPagePropertyList();

$APPLICATION->AddViewContent('class_page', $pageProperties['CLASS_PAGE']);
?>
</div><?/* container end */?>

<?php
if('Y' === $pageProperties['SHOW_BOTTOM_FEEDBACK']) { ?>
    <div class="f-form">
        <div class="container">
            <?php
            $APPLICATION->IncludeComponent(
                'citfact.lib:webform.ajax',
                'feedback',
                [
                    "WEB_FORM_CODE" => "SIMPLE_FORM_6",
                    "COMPONENT_TEMPLATE" => "feedback",
                    "PARAM" => "",
                    'RETURN_FORM' => 'Y',
                    'SHOW_FORM_TITLE' => 'Y',
                ],
                false,
                ['HIDE_ICONS' => 'Y']

            ); ?>

            <?php
            $APPLICATION->IncludeComponent("bitrix:main.include", "image",
                Array(
                    "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                    "PATH" => '/local/include/areas/footer/image.php',    // Путь к файлу области
                    "CLASS" => 'lazy lazy--replace f-form__img',
                    "ALT" => 'image_feedback',
                    "IMAGE_PLACEHOLDER" => 'Y',
                ),
                false
            ); ?>
        </div>
    </div>
<?php } ?>

<div class="scroll-top">
    <svg class='i-icon'>
        <use xlink:href='#icon-to-top'></use>
    </svg>
</div>
</main>
<footer class="f">
    <div class="f-subscribe">
        <div class="container">
            <div class="f-subscribe__inner">
                <div class="f-subscribe__logo">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-logo'/>
                    </svg>
                </div>

                <div class="f-subscribe__text">
                    ПОДПИШИТЕСЬ НА НАШИ НОВОСТИ, ЧТОБЫ УЗНАВАТЬ О НАШИХ НОВИНКАХ И СКИДКАХ
                </div>
                <? $APPLICATION->IncludeComponent(
                    "citfact:subscription.marketing",
                    "footer",
                    []
                ); ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="f__inner">
            <div class="f__items">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "catalog_footer",
                    [
                        "ADD_SECTIONS_CHAIN" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "COUNT_ELEMENTS" => "N",
                        "HIDE_EMPTY" => "Y",
                        "HIDE_SECTION_NAME" => "N",
                        "IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
                        "IBLOCK_TYPE" => "1c_catalog",
                        "SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
                        "SHOW_PARENT_NAME" => "N",
                        "TOP_DEPTH" => "2",
                        "VIEW_MODE" => "LIST",
                        "SECTION_MAIN" => true,
                        "SECTIONS_COUNT" => 50,
                        "SUBSECTIONS_COUNT" => 70,
                        "COMPONENT_TEMPLATE" => "catalog_on_main",
                        "SECTION_ID" => "",
                        "SECTION_CODE" => "",
                        "SECTION_FIELDS" => array(
                            0 => "",
                            1 => "",
                        ),
                        "SECTION_USER_FIELDS" => array(
                            0 => "",
                            1 => "",
                        )
                    ],
                    $component,
                    array(
                        "HIDE_ICONS" => "Y"
                    )
                );?>
                <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", [
                    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                    "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                    "DELAY" => "N",	// Откладывать выполнение шаблона меню
                    "MAX_LEVEL" => "1",	// Уровень вложенности меню
                    "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                    "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                    "MENU_CACHE_TYPE" => "N",	// Тип кеширования
                    "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                    "MENU_THEME" => "green",	// Тема меню
                    "ROOT_MENU_TYPE" => "bottom",	// Тип меню для первого уровня
                    "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                    "COMPONENT_TEMPLATE" => "catalog_vertical",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "STATIC",
                ],
                    false
                );?>
            </div>
            <div class="f__contacts" itemtype="http://schema.org/Organization" itemscope>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "email",
                    [
                        "COMPONENT_TEMPLATE" => "email",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/local/include/areas/footer/email.php",
                        "SCHEMA_ORG" => "Y",
                    ],
                    false
                ); ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "phone",
                    [
                        "COMPONENT_TEMPLATE" => "phone",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/local/include/areas/footer/phone.php",
                        "SCHEMA_ORG" => "Y",
                    ],
                    false
                ); ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "address",
                    [
                        "COMPONENT_TEMPLATE" => ".default",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/local/include/areas/footer/address.php",
                        "SCHEMA_ORG" => "Y",
                    ],
                    false
                ); ?>
                <p style="display:none;" itemprop="name">
                    Gauss
                </p>
                <? $APPLICATION->IncludeComponent(
                    "citfact:elements.list",
                    "soc.links",
                    array(
                        "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_FOOTER_SOC_LINKS),
                        "FILTER" => [],
                        "PROPERTY_CODES" => ['LINK', 'ICON'],
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600000",
                        "IS_FOOTER" => true,
                    ),
                    false,
                    array('HIDE_ICON' => 'Y')
                ); ?>
            </div>
        </div>
        <div class="f__bottom">
            © 2008-<?=date('Y')?>&nbsp;
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/local/include/areas/footer/copyright.php"
                ),
                false
            ); ?>

            <a href="https://fact.digital/" rel="nofollow" target="_blank" title="Разработка сайта — компания «Факт»">Разработка сайта — компания «Факт»</a>
        </div>
    </div>
</footer>
<div class="b-filter-mask hidden" data-f-mask></div>
<?$APPLICATION->IncludeComponent("bitrix:menu", "top_hamburger", [
    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
    "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
    "DELAY" => "N",	// Откладывать выполнение шаблона меню
    "MAX_LEVEL" => "1",	// Уровень вложенности меню
    "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
    "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
    "MENU_CACHE_TYPE" => "N",	// Тип кеширования
    "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
    "MENU_THEME" => "green",	// Тема меню
    "ROOT_MENU_TYPE" => "top_hamburger",	// Тип меню для первого уровня
    "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
    "COMPONENT_TEMPLATE" => "catalog_vertical",
    "COMPOSITE_FRAME_MODE" => "A",
    "COMPOSITE_FRAME_TYPE" => "STATIC",
],
    false
);?>
<div class="m-menu" data-m-menu="burger">
    <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom.mobile", [
        "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
        "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
        "DELAY" => "N",	// Откладывать выполнение шаблона меню
        "MAX_LEVEL" => "1",	// Уровень вложенности меню
        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
        "MENU_CACHE_TYPE" => "N",	// Тип кеширования
        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
        "MENU_THEME" => "green",	// Тема меню
        "ROOT_MENU_TYPE" => "bottom",	// Тип меню для первого уровня
        "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
        "COMPONENT_TEMPLATE" => "catalog_vertical",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "STATIC",
    ],
        false
    );?>

    <? $APPLICATION->IncludeComponent(
        "citfact:elements.list",
        "soc.links.mobile",
        array(
            "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_FOOTER_SOC_LINKS),
            "FILTER" => [],
            "PROPERTY_CODES" => ['LINK', 'ICON'],
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600000",
            "IS_FOOTER" => true,
        ),
        false,
        array('HIDE_ICON' => 'Y')
    ); ?>
</div>
<?$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "catalog_footer_mobile",
    [
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COUNT_ELEMENTS" => "N",
        "HIDE_EMPTY" => "Y",
        "HIDE_SECTION_NAME" => "N",
        "IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
        "IBLOCK_TYPE" => "1c_catalog",
        "SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
        "SHOW_PARENT_NAME" => "N",
        "TOP_DEPTH" => "2",
        "VIEW_MODE" => "LIST",
        "SECTION_MAIN" => true,
        "SECTIONS_COUNT" => 50,
        "SUBSECTIONS_COUNT" => 70,
        "COMPONENT_TEMPLATE" => "catalog_on_main",
        "SECTION_ID" => "",
        "SECTION_CODE" => "",
        "SECTION_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "SECTION_USER_FIELDS" => array(
            0 => "",
            1 => "",
        ),
    ],
    false,
    array(
        "HIDE_ICONS" => "Y"
    )
);?>
<div id="cookie-alert" class="cookie-alert animated fadeInUp hidden" data-scrollbar-fix>
    <div>
        Сайт использует файлы Cookie и данные об ip-адресе пользователя. Продолжая пользоваться сайтом, вы автоматически
        с этим соглашаетесь.
        <a href="/policy/" class="link" target="_blank" title="Обработка персональных данных">Обработка персональных данных</a>
    </div>
    <div id="cookie-alert-close" class="plus plus--cross"></div>
</div>

<script type="text/javascript" defer>
    window.jsFileTime = <?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/m.js') ?>;

    var cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = '/local/client/build/m.css?<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/local/client/build/m.css'); ?>';
    cssLink.type = 'text/css';
    var headLink = document.getElementsByTagName('link')[0];
    headLink.parentNode.appendChild(cssLink);
</script>

<?
$modalManager = new ModalManager();
if ($modalManager->canOpenModal()) { ?>
    <script>
        document.addEventListener('App.Ready', function (e) {
           // $('[data-modal-info]')[0].click();
        })
    </script>
<?}?>

<? if (\CModule::IncludeModule("vote")) {
    global $USER;
    $voteManager = new VoteManager();
    if ($voteManager->checkConditionForShowingVote()) {
        ?>
        <script>
            function get_cookie_fact(cookie_name) {
                var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');
                if (results)
                    return (unescape(results[2]));
                else
                    return null;
            }

            function set_cookie_fact(name, value, exp_y, exp_m, exp_d, path, domain, secure) {
                var cookie_string = name + "=" + escape(value);
                if (exp_y) {
                    var expires = new Date(exp_y, exp_m, exp_d);
                    cookie_string += "; expires=" + expires.toGMTString();
                }
                if (path)
                    cookie_string += "; path=" + escape(path);
                if (domain)
                    cookie_string += "; domain=" + escape(domain);
                if (secure)
                    cookie_string += "; secure";
                document.cookie = cookie_string;
            }

            document.addEventListener('App.Ready', function (e) {
                if (get_cookie_fact('IS_MODAL_SHOW') === 'Y') {
                    $('[data-modal-voting-form]')[0].click();
                    set_cookie_fact('IS_MODAL_SHOW', 'N');
                }
            })
        </script>
        <?
    }
} ?>

<? $curDir = $APPLICATION->GetCurDir();
if ($curDir != "/personal/") {?>
    <script>
        localStorage.removeItem("open_manager");
    </script>
<?} ?>

<?php $APPLICATION->ShowCSS(true); ?>
</body>
</html><?

if (!defined('NOT_SHOW_H1') && NOT_SHOW_H1 !== true) {
    $title = ($APPLICATION->GetPageProperty('title')) ? : $APPLICATION->GetProperty('title');
    if ($title) {
        $APPLICATION->AddViewContent('h1', '<h1 class="title">'.$title.'</h1>');
    }
}
