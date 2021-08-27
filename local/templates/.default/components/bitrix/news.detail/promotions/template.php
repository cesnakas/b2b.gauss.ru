<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$core = Core::getInstance();
$this->setFrameMode(true);
?>
<div class="sale-d styled-list">
    <?if($arResult['DETAIL_PICTURE']['SRC']['ORIGIN']):?>
    <img src="<?=$arResult['DETAIL_PICTURE']['SRC']['PREVIEW']?>"
         data-src="<?=$arResult['DETAIL_PICTURE']['SRC']['ORIGIN']?>"
         title="<?= $arResult["NAME"] ?>"
         alt="<?= $arResult["NAME"] ?>"
         class="sale-d__img lazy lazy--replace">
    <?endif;?>
    <div class="sale-d__inner">
        <h1 class="title"><?= $arResult["NAME"] ?></h1>

        <? if((!empty($arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'])) || (!empty($arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']))) {?>
        <div class="sale-d__date">
            <? if ($arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] || $arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']):
                echo "Акция проходит в период: "; ?>
                <? if ($arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] && $arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']) {
                echo $arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] . " - " . $arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE'];
            } elseif ($arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] && !$arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']) {
                echo "С " . $arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'];
            } elseif (!$arResult['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] && $arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']) {
                echo "По " . $arResult['DISPLAY_PROPERTIES']['DATE_TO']['VALUE'];
            }
                ?>
            <? endif; ?>
        </div>
        <?}?>

        <div class="sale-d__content">
            <?= $arResult["DETAIL_TEXT"]; ?>
        </div>
    </div>
</div>

<? if ($arResult['DISPLAY_PROPERTIES']['PRODUCTS']['VALUE']): ?>
    <? global $arrFilterPromotionsSection;
    $arrFilterPromotionsSection = array("=ID" => $arResult['DISPLAY_PROPERTIES']['PRODUCTS']['VALUE']); ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "promotions",
        array(
            "IS_USER_AUTHORIZED" => $USER->IsAuthorized(),
            "COMPONENT_TEMPLATE" => ".default",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
            "SECTION_ID" => false,
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "FILTER_NAME" => "arrFilterPromotionsSection",
            "USE_FILTER" => "Y",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "CUSTOM_FILTER" => "",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "ELEMENT_SORT_FIELD" => "",
            "ELEMENT_SORT_ORDER" => "",
            "ELEMENT_SORT_FIELD2" => "",
            "ELEMENT_SORT_ORDER2" => "",
            "PAGE_ELEMENT_COUNT" => "180",
            "LINE_ELEMENT_COUNT" => "3",
            "OFFERS_LIMIT" => "5",
            "BACKGROUND_IMAGE" => "-",
            "TEMPLATE_THEME" => "blue",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
            "ENLARGE_PRODUCT" => "STRICT",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
            "SHOW_SLIDER" => "Y",
            "SLIDER_INTERVAL" => "3000",
            "SLIDER_PROGRESS" => "N",
            "ADD_PICT_PROP" => "-",
            "LABEL_PROP" => array(),
            "PRODUCT_SUBSCRIPTION" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_MAX_QUANTITY" => "N",
            "SHOW_CLOSE_POPUP" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "RCM_TYPE" => "personal",
            "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
            "SHOW_FROM_SECTION" => "N",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "SEF_MODE" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "Y",
            "SET_TITLE" => "Y",
            "SET_BROWSER_TITLE" => "Y",
            "BROWSER_TITLE" => "-",
            "SET_META_KEYWORDS" => "Y",
            "META_KEYWORDS" => "-",
            "SET_META_DESCRIPTION" => "Y",
            "META_DESCRIPTION" => "-",
            "SET_LAST_MODIFIED" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "CACHE_FILTER" => "N",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRICE_CODE" => array(
                0 => $arParams['PRICE_CODE']
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "BASKET_URL" => "/personal/basket.php",
            "USE_PRODUCT_QUANTITY" => "N",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "DISPLAY_COMPARE" => "N",
            "USE_ENHANCED_ECOMMERCE" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "LAZY_LOAD" => "N",
            "LOAD_ON_SCROLL" => "N",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => "",
            "COMPATIBLE_MODE" => "Y",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N"
        ),
        false
    ); ?>
<? endif; ?>

<a href="<?= $arParams['MAIN_HREF'] ?>" class="link-more link-more--back">
    <svg class='i-icon'>
        <use xlink:href='#icon-arrow-r'/>
    </svg>
    <span><?= GetMessage('T_NEWS_DETAIL_BACK') ?></span>
</a>