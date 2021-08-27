<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Добавление в корзину"); ?><?

use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\Sitecore\UserDataManager;
use Citfact\SiteCore\Core;

$cCore = Core::getInstance();
$data = array();
foreach ($_POST as $key => $value) {
	if ($value == "on") {
		$data[] = $key;
	}
}

$basePrice = Price::getPriceByCode(Price::PRICE_CODE_MIC);
$priceCode = empty($basePrice) ? '' : $basePrice['NAME'];
$userPriceType = UserDataManager\UserDataManager::getUserPriceType();
if (!empty($userPriceType)) {
    $priceCode = $userPriceType['NAME'];
}
?>
    <p id="download_price">
        <span>Скачать прайс-лист по выбраным разделам</span>&nbsp;
        <a id="createPDF" href="javascript:void(null);" class="link" title="PDF">PDF</a>&nbsp;
        <a id="createXLSX" href="javascript:void(null);" class="link" title="XLS">XLS</a>
    </p>

    <p id="error_price" class="red hidden">Не выбран ни один раздел или в выбранных разделах нет товаров</p>

    <form method="post" id="formPriceListKP" class="portal-price" action="javascript:void(null);">

		<? foreach ($data as $item) { ?>
			<? $APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"catalog_price_kp",
				array(
                    "USER_IS_AUTHORIZED" => $USER->IsAuthorized(),
					"ACTION_VARIABLE" => "action",
					"ADD_PROPERTIES_TO_BASKET" => "Y",
					"ADD_SECTIONS_CHAIN" => "N",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"BACKGROUND_IMAGE" => "-",
					"BASKET_URL" => "/personal/basket.php",
					"BROWSER_TITLE" => "-",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"COMPATIBLE_MODE" => "Y",
					"CONVERT_CURRENCY" => "N",
					"DETAIL_URL" => "",
					"DISABLE_INIT_JS_IN_COMPONENT" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_TOP_PAGER" => "N",
					"ELEMENT_SORT_FIELD" => "sort",
					"ELEMENT_SORT_FIELD2" => "id",
					"ELEMENT_SORT_ORDER" => "asc",
					"ELEMENT_SORT_ORDER2" => "desc",
					"FILTER_NAME" => "arrFilter",
					"HIDE_NOT_AVAILABLE" => "N",
					"HIDE_NOT_AVAILABLE_OFFERS" => "N",
					"IBLOCK_ID" => $cCore->getIblockId($cCore::IBLOCK_CODE_CATALOG),
					"IBLOCK_TYPE" => "1c_catalog",
					"INCLUDE_SUBSECTIONS" => "Y",
					"LINE_ELEMENT_COUNT" => "3",
					"MESSAGE_404" => "",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_LIMIT" => "5",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => "9999",
					"PARTIAL_PRODUCT_PROPERTIES" => "N",
					"PRICE_CODE" => array(
                        0 => $priceCode,
                    ),
					"PRICE_VAT_INCLUDE" => "Y",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array(),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
					"SECTION_CODE" => "",
					"SECTION_ID" => $item,
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array(
						0 => "EDINITSA_IZMERENIYA_1",
						1 => "CML2_ARTICLE",
					),
					"SEF_MODE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SHOW_ALL_WO_SECTION" => "N",
					"SHOW_PRICE_COUNT" => "1",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "N",
					"COMPONENT_TEMPLATE" => "catalog_price_kp",
					"CUSTOM_FILTER" => "",

				),
				false
			); ?>
		<? } ?>

        <?php if (!empty($data)) { ?>
            <div id="submit_form" class="b-form__submit">
                <button class="btn btn--transparent btn--big" type="submit">
                    В корзину
                </button>
            </div>
            <div style="display: none" class="b-form__submit">
                <a class="btn btn--grey btn--big" href="/personal/price/" title="Назад">Назад</a>
            </div>
        <?php } else {?>
            <div class="b-form__submit">
                <a class="btn btn--grey btn--big" href="/personal/price/" title="Назад">Назад</a>
            </div>
        <?php } ?>


    </form>

    <div id="rezult" class="input-block__error-text"></div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>