<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Результаты поиска");

use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;

$core = Core::getInstance();



$contragent = UserDataManager\UserDataManager::getUserContragentXmlID();

$basePrice = Price::getPriceByCode(Price::PRICE_CODE_MIC);
$priceCode = empty($basePrice) ? '' : $basePrice['NAME'];
$userPriceType = UserDataManager\UserDataManager::getUserPriceType();
if (!empty($userPriceType)) {
    $priceCode = $userPriceType['NAME'];
}


global $APPLICATION;

require_once ($_SERVER["DOCUMENT_ROOT"]."/local/include/areas/search/search-page.php");

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$query = htmlspecialchars($request->getQuery("q"));

global $arrSearchFound;

if (count($arrSearchFound['CATALOG']) > 0) {

    global $arrFilter;
    $arrFilter['ID'] = $arrSearchFound['CATALOG'];
    $iBlockId = $core->getIblockId(Core::IBLOCK_CODE_CATALOG);
    ?>
    <?
    global $USER;
    $APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"catalog_list",
	[
		"IS_USER_AUTHORIZED" => $USER->IsAuthorized(),
		"ELEMENT_SORT_FIELD" => "shows",
		"ELEMENT_SORT_ORDER" => "desc",
		"IS_SEARCH_PAGE" => "Y",
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => SITE_DIR."personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => $iBlockId,
		"IBLOCK_TYPE" => "1c_catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => "",
		"LINE_ELEMENT_COUNT" => "3",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => Loc::getMessage("TO_CART"),
		"MESS_BTN_BUY" => Loc::getMessage("TO_BUY"),
		"MESS_BTN_DETAIL" => Loc::getMessage("SHOW_MORE"),
		"MESS_BTN_SUBSCRIBE" => Loc::getMessage("SUBSCRIBE"),
		"MESS_NOT_AVAILABLE" => Loc::getMessage("NOT_AVAILABLE"),
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => Loc::getMessage("PRODUCTS"),
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => "",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PROPERTY_CODE" => [
			0 => "CML2_ARTICLE",
			4 => "",
		],
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => [
			0 => "",
			1 => "",
		],
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "catalog_list",
		"CUSTOM_FILTER" => "",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"PAGE_ELEMENT_COUNT" => "18",
		"PROPERTY_CODE_MOBILE" => "",
		"OFFERS_FIELD_CODE" => "",
		"OFFERS_PROPERTY_CODE" => "",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"ENLARGE_PRODUCT" => "STRICT",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"PRODUCT_DISPLAY_MODE" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"RCM_TYPE" => "personal",
		"SHOW_FROM_SECTION" => "N",
		"PRICE_CODE" => [
            $priceCode,
		],
		"OFFERS_CART_PROPERTIES" => "",
		"DISPLAY_COMPARE" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"LAZY_LOAD" => "N",
		"LOAD_ON_SCROLL" => "N",
		"COMPATIBLE_MODE" => "Y",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"]
	],
	false
);
} else { ?>
    <div class="empty-page">
        <h2><?= Loc::getMessage('SEARCH_TITLE_NOT_FOUND_TITLE'); ?></h2>
        <div class="empty-page__text"><?= Loc::getMessage('SEARCH_TITLE_NOT_FOUND_TEXT'); ?></div>
    </div>
<? } ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>