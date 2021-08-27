<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var array $arCurSection */

use Bitrix\Main\Loader;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\Sitecore\UserDataManager;

$this->setFrameMode(true);

$userPriceType = UserDataManager\UserDataManager::getUserPriceType();

if($_GET['only_available'] == 'show') {
global $arrFilter;
$arrFilter['>CATALOG_QUANTITY'] = 0;
}

if (!empty($userPriceType['ID'])) {
    $priceId = $userPriceType['ID'];
} else {
    $basePrice = Price::getPriceByCode(Price::PRICE_CODE_MIC);
    $basePriceId = empty($basePrice) ? '' : $basePrice['ID'];
    $priceId = empty($basePrice) ? '' : $basePrice['ID'];
}


global $USER;
$rsUser = \CUser::GetByID($USER->GetId());
$arUserInfo = $rsUser->GetNext(false);

$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');
$isFilter = ($arParams['USE_FILTER'] == 'Y');

if ($isFilter)
{
    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
    );
    if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
        $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
    elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
        $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
    
    $arFilter['ELEMENT_SUBSECTIONS'] = 'N';
    $arFilter['CNT_ACTIVE'] = 'Y';
    
    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            // bIncCnt = true для корректного показа разделовыуее или товаров на разных уровнях каталога
            // см. $arFilter['ELEMENT_SUBSECTIONS'] = 'N'; $arFilter['CNT_ACTIVE'] = 'Y';
            $dbRes = CIBlockSection::GetList(array(), $arFilter, true, array("ID", "DEPTH_LEVEL", 'DESCRIPTION', 'UF_TITLE'));
            
            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");
                
                if ($arCurSection = $dbRes->GetNext())
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
                
                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->GetNext())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
        $arCurSection = array();
}

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
    $basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
    $basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}

?>

    <div class="c">
        <div class="aside">
            <?// КОЛОНКА ?>
            <div class="aside__sidebar">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "catalog_right_column",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => "",
                        "SECTION_CODE" => "",
                        "SECTIONS_COUNT" => 60,
                        "SUBSECTIONS_COUNT" => 70,
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                        "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                        "ADD_SECTIONS_CHAIN" => 'N',
                        "CATALOG_TEMPLATE" => $settings['TEMPLATE'],
                        "CUR_SECTION" => $arCurSection['ID']
                    ),
                    $component
                );?>
        
                <?// ФИЛЬТР ?>
                <?$APPLICATION->ShowViewContent('smartfilter');?>
            </div>

            <div class="aside__main">
                <div class="c-top">
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
                    <? if ($USER->IsAuthorized() && $arUserInfo['UF_ACTIVATE_PROFILE']) { ?>
                        <a href="/personal/price/" class="link-download">
                            <svg class="i-icon">
                                <use xlink:href="#icon-t-doc"/>
                            </svg>
                            Прайс-листы
                        </a>
                    <? } ?>
                    <h1 class="title<?= !$USER->IsAuthorized() || !$arUserInfo['UF_ACTIVATE_PROFILE'] ? ' title--unauth' : ''?>"><?php $APPLICATION->ShowTitle(true); ?></h1>
                </div>

                <?if ($isFilter):?>
                    <?$this->SetViewTarget('smartfilter');?>
                    <?
                    $APPLICATION->IncludeComponent(
                        "bitrix:catalog.smart.filter",
                        "main",
                        array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "SECTION_ID" => $arCurSection['ID'],
                            "FILTER_NAME" => $arParams["FILTER_NAME"],
                            "PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "SAVE_IN_SESSION" => "N",
                            "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                            "XML_EXPORT" => "Y",
                            "SECTION_TITLE" => "NAME",
                            "SECTION_DESCRIPTION" => "DESCRIPTION",
                            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                            "SEF_MODE" => $arParams["SEF_MODE"],
                            "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                            "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                        ),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );
                    ?>
                    <?$this->EndViewTarget();?>
                <? endif ?>

                <?php  $isAuth = ($USER->IsAuthorized() && $arUserInfo['UF_ACTIVATE_PROFILE']) ? 'Y' : 'N'; ?>
                <div class="b-sort <?= $isAuth === 'Y' ? '' : 'b-sort--min'; ?>">
                    <div class="b-sort__top">
                        <div class="b-sort__text"><?= $isAuth === 'Y' ? 'Выбрать юр.лицо, от которого будет оформлен заказ:' : ''; ?></div>
                        <div class="b-sort__text b-sort__text--count">
                            <?php $APPLICATION->ShowViewContent('amount'); ?>
                        </div>
                    </div>

                    <?
                    $settings = $APPLICATION->IncludeComponent(
                        'citfact:catalog.settings',
                        'catalog',
                        array(
                            'IS_AUTH' => ($USER->IsAuthorized() && $arUserInfo['UF_ACTIVATE_PROFILE'])?'Y':'N',
                            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                            'FILTER_NAME' => $arParams['FILTER_NAME'],
                            'SECTION_ID' => $arResult["VARIABLES"]["SECTION_ID"],
                            'NO_VIEW' => 'N',
                            'SORT' => array(
                                array('KEY' => 'quantity-asc', 'NAME' => 'По наличию', 'FIELD' => 'catalog_QUANTITY', 'ORDER' => 'desc'),
                                array('KEY' => 'alpahbet-asc', 'NAME' => 'По алфавиту', 'FIELD' => 'NAME', 'ORDER' => 'asc'),
                                array('KEY' => 'popularity-asc', 'NAME' => 'По популярности', 'FIELD' => 'property_POPULARITY', 'ORDER' => 'asc'),
                                array('KEY' => 'price-asc', 'NAME' => 'Цена, по возрастанию', 'FIELD' => 'catalog_PRICE_'.$priceId, 'ORDER' => 'asc'),
                                array('KEY' => 'price-desc', 'NAME' => 'Цена, по убыванию', 'FIELD' => 'catalog_PRICE_'.$priceId, 'ORDER' => 'desc'),
                                array('KEY' => 'data-desc', 'NAME' => 'По новизне', 'FIELD' => 'property_NOVINKA', 'ORDER' => 'desc'),
                            ),
                            'COUNT' => array(
                                array('VALUE' => '16'),
                                array('VALUE' => '32'),
                                array('VALUE' => '48'),
                                array('VALUE' => '96'),
                            ),
                            'VIEW' => array(
                                array('TEMPLATE' => 'list', 'CLASS' => 'list', 'TITLE' => 'Отображение списком'),
                                array('TEMPLATE' => 'table', 'CLASS' => 'table', 'TITLE' => 'Отображение плиткой'),
                            ),
                        ),
                        false
                    ); ?>
                </div>

                <? $intSectionID = $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    'catalog_' . $settings['TEMPLATE'],
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "AJAX_MODE" => "Y",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_HISTORY" => "N",
                        "ELEMENT_SORT_FIELD" => $settings['SORT']['FIELD'],
                        "ELEMENT_SORT_ORDER" => $settings['SORT']['ORDER'],
                        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                        "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                        "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                        "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                        "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                        "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                        "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "SET_TITLE" => $arParams["SET_TITLE"],
                        "MESSAGE_404" => $arParams["~MESSAGE_404"],
                        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                        "SHOW_404" => $arParams["SHOW_404"],
                        "FILE_404" => $arParams["FILE_404"],
                        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                        "PAGE_ELEMENT_COUNT" => $settings['COUNT'],
                        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                
                        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                
                        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                        "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                        "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                        "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                        "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                        "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],
                
                        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                        "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                        "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                        "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                        "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                        "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
                
                        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                        "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                        'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                
                        'LABEL_PROP' => $arParams['LABEL_PROP'],
                        'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                        'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                        'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                        'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                        'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                        'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                        'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
                        'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                        'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                        'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',
                
                        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                        'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                        'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                        'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                        'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                        'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                        'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                        'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                        'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                        'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                        'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                        'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                        'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),
                
                        'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                        'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
                        'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),
                
                        'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                        "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
                        'ADD_TO_BASKET_ACTION' => $basketAction,
                        'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                        'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                        'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                        'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                        'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
                        'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
                        'CURRENT_SECTION' => $arCurSection,
                        'IS_USER_AUTHORIZED' => $USER->IsAuthorized(),
                    ),
                    $component
                );
                ?>
            </div>
        </div>
    </div>
