<?php
if ($_REQUEST['SITE_ID']) {
    define('SITE_ID', $_REQUEST['SITE_ID']);
}

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//ajax
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    \Bitrix\Iblock\Component\Tools::process404(
        '404 Not Found'
        ,true
        ,"Y"
        ,"Y"
        , ""
    );
}

?>
<div class="b-modal b-modal--basket">
    <?
    CJSCore::Init('currency');
    $APPLICATION->ShowHeadStrings();
    $APPLICATION->ShowHeadScripts();
    ?>
    <div class="plus plus--cross b-modal__close" data-modal-close></div>
    <div class="title-1">
        <span>Товары добавлены в корзину</span>
    </div>
    <?
    if ($_REQUEST['IDS']) {
        $arIds = explode(',', $_REQUEST['IDS']);
    }

    if (!empty($arIds)) { ?>
        <div class="b-modal__content">
            <?$APPLICATION->IncludeComponent(
                "bitrix:sale.basket.basket",
                "modal",
                [
                    "ADD_ITEMS" => $arIds,
                    "ACTION_VARIABLE" => "basketAction",
                    "AUTO_CALCULATION" => "Y",
                    "COLUMNS_LIST" => [
                        0 => "NAME",
                        1 => "DELETE",
                        2 => "DELAY",
                        3 => "PRICE",
                        4 => "QUANTITY",
                        5 => "SUM",
                    ],
                    "CORRECT_RATIO" => "N",
                    "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
                    "GIFTS_CONVERT_CURRENCY" => "N",
                    "GIFTS_HIDE_BLOCK_TITLE" => "N",
                    "GIFTS_HIDE_NOT_AVAILABLE" => "N",
                    "GIFTS_MESS_BTN_BUY" => "Выбрать",
                    "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
                    "GIFTS_PAGE_ELEMENT_COUNT" => "4",
                    "GIFTS_PLACE" => "BOTTOM",
                    "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
                    "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
                    "GIFTS_SHOW_IMAGE" => "Y",
                    "GIFTS_SHOW_NAME" => "Y",
                    "GIFTS_SHOW_OLD_PRICE" => "N",
                    "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
                    "HIDE_COUPON" => "Y",
                    "OFFERS_PROPS" => [
                        0 => "ARTNUMBER",
                    ],
                    "PATH_TO_ORDER" => "/order/",
                    "PATH_TO_BASKET" => "/cart/",
                    "PRICE_VAT_SHOW_VALUE" => "Y",
                    "QUANTITY_FLOAT" => "Y",
                    "SET_TITLE" => "N",
                    "TEMPLATE_THEME" => "",
                    "USE_ENHANCED_ECOMMERCE" => "N",
                    "USE_GIFTS" => "N",
                    "USE_PREPAYMENT" => "N",
                    "COMPONENT_TEMPLATE" => "_template",
                    "DEFERRED_REFRESH" => "Y",
                    "USE_DYNAMIC_SCROLL" => "N",
                    "SHOW_FILTER" => "N",
                    "SHOW_RESTORE" => "Y",
                    "COLUMNS_LIST_EXT" => [
                        0 => "PREVIEW_PICTURE",
                        1 => "DISCOUNT",
                        2 => "DELETE",
                        3 => "DELAY",
                        4 => "TYPE",
                        5 => "SUM",
                    ],
                    "COLUMNS_LIST_MOBILE" => [
                        0 => "PREVIEW_PICTURE",
                        1 => "DISCOUNT",
                        2 => "DELETE",
                        3 => "DELAY",
                        4 => "TYPE",
                        5 => "SUM",
                    ],
                    "TOTAL_BLOCK_DISPLAY" => [
                        0 => "bottom",
                    ],
                    "DISPLAY_MODE" => "extended",
                    "PRICE_DISPLAY_MODE" => "Y",
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                    "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
                    "USE_PRICE_ANIMATION" => "Y",
                    "LABEL_PROP" => [],
                    "COMPATIBLE_MODE" => "Y",
                    "ADDITIONAL_PICT_PROP_1" => "-",
                    "BASKET_IMAGES_SCALING" => "adaptive",
                    "EMPTY_BASKET_HINT_PATH" => "/"
                ],
                false
            );?>
        </div>
    <? } ?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
