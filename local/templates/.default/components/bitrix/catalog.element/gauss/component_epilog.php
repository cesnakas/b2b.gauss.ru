<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Citfact\Tools\ElementManager;
use Citfact\Sitecore\CatalogHelper\ListWaitHelper;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */
$APPLICATION->SetPageProperty('show_bottom_feedback', 'Y');
$APPLICATION->SetPageProperty('class_page', 'main--nopb main--gradient-s');

global $APPLICATION;
if (!empty($templateData['TEMPLATE_LIBRARY']))
{
    $loadCurrency = false;

    if (!empty($templateData['CURRENCIES']))
    {
        $loadCurrency = Loader::includeModule('currency');
    }

    CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
    if ($loadCurrency)
    {
        ?>
        <script>
          BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
        </script>
        <?
    }
}

global $USER;
if (!empty($arResult['RECOMENDATION'])) {
    $GLOBALS['recommend'] = ['XML_ID' => $arResult['RECOMENDATION']];
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "detail_products",
        array(
            "IS_USER_AUTHORIZED" => $USER->IsAuthorized(),
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arResult["IBLOCK_ID"],
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
                2 => "",
            ),
            "ELEMENT_SORT_FIELD" => "RAND",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_FIELD2" => "RAND",
            "ELEMENT_SORT_ORDER2" => "asc",
            "FILTER_NAME" => "recommend",
            "INCLUDE_SUBSECTIONS" => "N",
            "SHOW_ALL_WO_SECTION" => "Y",
            "HIDE_NOT_AVAILABLE" => "N",
            "PAGE_ELEMENT_COUNT" => "10",
            "LINE_ELEMENT_COUNT" => "4",
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            "OFFERS_LIMIT" => "5",
            "ADD_PICT_PROP" => "-",
            "LABEL_PROP" => array(),
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "SECTION_URL" => "/catalog/#SECTION_CODE#/",
            "DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
            "BASKET_URL" => "/cart/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "Y",
            "META_KEYWORDS" => "-",
            "META_DESCRIPTION" => "-",
            "BROWSER_TITLE" => "-",
            "ADD_SECTIONS_CHAIN" => "N",
            "DISPLAY_COMPARE" => "N",
            "SET_TITLE" => "N",
            "SET_STATUS_404" => "N",
            "CACHE_FILTER" => "N",
            "PRICE_CODE" => $arParams['PRICE_CODE'],
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_PROPERTIES" => array(),
            "USE_PRODUCT_QUANTITY" => "N",
            "CONVERT_CURRENCY" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "PROPERTY_CODE_MOBILE" => array(),
            "BACKGROUND_IMAGE" => "-",
            "TEMPLATE_THEME" => "",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false}]",
            "ENLARGE_PRODUCT" => "STRICT",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
            "SHOW_SLIDER" => "Y",
            "LABEL_PROP_MOBILE" => "",
            "LABEL_PROP_POSITION" => "top-left",
            "SHOW_MAX_QUANTITY" => "N",
            "SHOW_CLOSE_POPUP" => "N",
            "RCM_TYPE" => "personal",
            "RCM_PROD_ID" => '',
            "SHOW_FROM_SECTION" => "N",
            "SEF_MODE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_LAST_MODIFIED" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "USE_ENHANCED_ECOMMERCE" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "LAZY_LOAD" => "N",
            "LOAD_ON_SCROLL" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => "",
            "COMPATIBLE_MODE" => "Y",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "SLIDER_INTERVAL" => "3000",
            "SLIDER_PROGRESS" => "N",
            "SECTION_TITLE" => 'Рекомендуем к покупке',
        ),
        false
    );
}

$frame = new \Bitrix\Main\Page\FrameBuffered("wait-list-block-detail");
$frame->begin();

$listWaitHelper = new ListWaitHelper();
$productInListWait = $listWaitHelper->checkProductinListWait($arResult['ID']);
?>
<div style="display: none" class="button-container">
    <div class="item-hidden" data-id="<?= $arResult['ID'] ?>" >
        <? if (!in_array( $arResult['ID'], $productInListWait)) { ?>
            <button type="button" class="btn btn-link btn--transparent btn--big btn--loading tooltip__handle"
                data-btn-list-wait
                 data-item-id="<?= $arResult['ID'] ?>">
                <span>В лист ожидания</span>
                <span>В листе ожидания</span>
            </button>
        <? } else { ?>
            <div class="btn btn-link btn--transparent btn--big btn--loading tooltip__handle no-hover"
                 data-btn-list-wait
                 data-item-id="<?=$arResult['ID'] ?>">
                В листе ожидания
            </div>
        <? } ?>
    </div>
</div>

<script>
    function initBtnWaitList() {
        $('.button-container .item-hidden').each(function (i, v) {
            let id = $(v).attr('data-id');
            let container = $('[data-wait-id=' + id + '] .wait-list-block');
            container.html($(v).html());
        })
    }
    if (window.frameCacheVars !== undefined)
    {
        BX.addCustomEvent("onFrameDataReceived" , function(json) {
            initBtnWaitList();
        });
    } else {
        document.addEventListener('App.Ready', function (e) {
            initBtnWaitList();
        });
    }

</script>

<?php
$frame->beginStub();
$frame->end();
