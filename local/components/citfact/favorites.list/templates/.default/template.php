<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\CatalogHelper\ElementRepository;

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
/** @var OrderCheckoutComponent $component */


$this->setFrameMode(false);
$core = Core::getInstance();

$arIdsProducts = array_keys($arResult['PRODUCTS']);
?>
    <div class="c__empty" data-favourites-empty style="display: <?= (empty($arIdsProducts))?'block':'none'; ?>;">
        <h3>В данном разделе пока нет товаров.</h3>
        <h3>Вернуться в <a href="/catalog/" class="link">каталог</a></h3>
    </div>
<?php
if (empty($arResult["PRODUCTS"])) {
    return;
}

global $USER;

$basePrice = Price::getPriceByCode(Price::PRICE_CODE_MIC);
$priceCode = empty($basePrice) ? '' : $basePrice['NAME'];
$userPriceType = UserDataManager\UserDataManager::getUserPriceType();
if (!empty($userPriceType)) {
    $priceCode = $userPriceType['NAME'];
}


global $arFilterFavorites;
$arFilterFavorites['=ID'] = $arIdsProducts;
?>
<? $intSectionID = $APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    'catalog_table_favorites',
    array (
        'TEMPLATE_THEME' => 'blue',
        'SHOW_MAX_QUANTITY' => 'N',
        'SHOW_OLD_PRICE' => 'N',
        'SHOW_CLOSE_POPUP' => 'N',
        'SHOW_DISCOUNT_PERCENT' => 'N',
        'DISCOUNT_PERCENT_POSITION' => 'bottom-right',
        'LABEL_PROP_POSITION' => 'top-left',
        'PRODUCT_SUBSCRIPTION' => 'Y',
        'MESS_BTN_BUY' => 'Купить',
        'MESS_BTN_ADD_TO_BASKET' => 'В корзину',
        'MESS_BTN_SUBSCRIBE' => 'Подписаться',
        'MESS_BTN_DETAIL' => 'Подробнее',
        'MESS_NOT_AVAILABLE' => 'Нет в наличии',
        'MESS_BTN_COMPARE' => 'Сравнение',
        'SHOW_SLIDER' => 'N',
        'SLIDER_PROGRESS' => 'N',
        'USE_ENHANCED_ECOMMERCE' => 'N',
        'DATA_LAYER_NAME' => '',
        'BRAND_PROPERTY' => '',
        'ENLARGE_PRODUCT' => 'STRICT',
        'ENLARGE_PROP' => '',
        'ADD_TO_BASKET_ACTION' => 'ADD',
        'MESS_BTN_LAZY_LOAD' => '',
        'IBLOCK_TYPE' => '1c_catalog',
        'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
        'AJAX_MODE' => 'Y',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
        'ELEMENT_SORT_FIELD' => 'NAME',
        'ELEMENT_SORT_ORDER' => 'asc',
        'ELEMENT_SORT_FIELD2' => 'id',
        'ELEMENT_SORT_ORDER2' => 'desc',
        'PROPERTY_CODE' =>
            array (

            ),
        'META_KEYWORDS' => '-',
        'META_DESCRIPTION' => '-',
        'BROWSER_TITLE' => '-',
        'SET_LAST_MODIFIED' => 'N',
        'INCLUDE_SUBSECTIONS' => 'Y',
        'BASKET_URL' => '/cart/',
        'ACTION_VARIABLE' => 'action',
        'PRODUCT_ID_VARIABLE' => 'id',
        'SECTION_ID_VARIABLE' => 'SECTION_ID',
        'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
        'PRODUCT_PROPS_VARIABLE' => 'prop',
        'FILTER_NAME' => 'arFilterFavorites',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '3600',
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'N',
        'SET_TITLE' => 'N',
        'MESSAGE_404' => '',
        'SET_STATUS_404' => 'Y',
        'SHOW_404' => 'Y',
        'FILE_404' => '',
        'DISPLAY_COMPARE' => 'N',
        'PAGE_ELEMENT_COUNT' => 15,
        'PRICE_CODE' =>
            array (
                0 => $priceCode,
            ),
        'USE_PRICE_COUNT' => 'N',
        'SHOW_PRICE_COUNT' => 1,
        'PRICE_VAT_INCLUDE' => 'Y',
        'USE_PRODUCT_QUANTITY' => 'Y',
        'ADD_PROPERTIES_TO_BASKET' => 'Y',
        'PARTIAL_PRODUCT_PROPERTIES' => 'N',
        'PRODUCT_PROPERTIES' => '',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'PAGER_TITLE' => 'Товары',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => '.default',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'LAZY_LOAD' => 'N',
        'LOAD_ON_SCROLL' => 'N',
        'OFFERS_SORT_FIELD' => 'sort',
        'OFFERS_SORT_ORDER' => 'asc',
        'OFFERS_SORT_FIELD2' => 'id',
        'OFFERS_SORT_ORDER2' => 'desc',
        'OFFERS_LIMIT' => '',
        'SECTION_ID' => '',
        'SECTION_CODE' => '',
        'SECTION_URL' => '/catalog/#SECTION_CODE_PATH#/',
        'DETAIL_URL' => '/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
        'USE_MAIN_ELEMENT_SECTION' => 'Y',
        'CONVERT_CURRENCY' => 'N',
        'CURRENCY_ID' => '',
        'HIDE_NOT_AVAILABLE' => 'N',
        'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
        'ADD_PICT_PROP' => '',
        'PRODUCT_DISPLAY_MODE' => 'Y',
        'MESS_SHOW_MAX_QUANTITY' => '',
        'MESS_RELATIVE_QUANTITY_MANY' => '',
        'MESS_RELATIVE_QUANTITY_FEW' => '',
        'ADD_SECTIONS_CHAIN' => 'N',
        'COMPARE_PATH' => '/catalog/compare.php?action=#ACTION_CODE#',
        'COMPARE_NAME' => 'CATALOG_COMPARE_LIST',
        'BACKGROUND_IMAGE' => '',
        'COMPATIBLE_MODE' => 'Y',
        'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
        'IS_USER_AUTHORIZED' => $USER->IsAuthorized(),
        'PARENT_NAME' => 'bitrix:catalog',
        'PARENT_TEMPLATE_NAME' => '.default',
        'PARENT_TEMPLATE_PAGE' => 'section',
        'SET_BROWSER_TITLE' => 'Y',
        'SET_META_KEYWORDS' => 'Y',
        'SET_META_DESCRIPTION' => 'Y',
        'USE_COMPARE_LIST' => 'N',
        'SHOW_FROM_SECTION' => 'N',
        'SHOW_ALL_WO_SECTION' => 'Y',
        'SECTIONS_CHAIN_START_FROM' => 0,
        'CUSTOM_CURRENT_PAGE' => '',
    ),
    $component
);?>