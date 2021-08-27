<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

use Citfact\DataCache\IBlockData\IBlockPropertyEnum;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;

global $APPLICATION;
$APPLICATION->SetTitle("Gauss - энергоэффективная светотехника");
$APPLICATION->SetPageProperty('show_bottom_feedback', 'Y');
$APPLICATION->SetPageProperty('class_page', 'main--gradient main--nopb');

$core = Core::getInstance();
?>
<? $APPLICATION->IncludeComponent(
    "citfact:elements.list",
    "main_slider",
    Array(
        'IBLOCK_CODE' => 'banners',
        "FIELDS" => array('PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT', "PROPERTY_FORMAT", "PROPERTY_HIDE_EL", "PROPERTY_SRC_BAN", "PROPERTY_SRC_BAN_2", "SORT","PROPERTY_*",),
    )

); ?>
<?/*$APPLICATION->IncludeComponent(
	"bitrix:advertising.banner", 
	"main_slider", 
	array(
		"COMPONENT_TEMPLATE" => "main_slider",
		"TYPE" => "main_slider",
		"NOINDEX" => "N",
		"QUANTITY" => "6",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"DEFAULT_TEMPLATE" => "-",
		"HEIGHT" => "300",
		"SCALE" => "N",
		"CYCLING" => "N",
		"EFFECTS" => "",
		"ANIMATION_DURATION" => "500",
		"WRAP" => "1",
		"ARROW_NAV" => "1",
		"BULLET_NAV" => "2",
		"KEYBOARD" => "N",
		"BS_EFFECT" => "fade",
		"BS_CYCLING" => "N",
		"BS_WRAP" => "Y",
		"BS_KEYBOARD" => "Y",
		"BS_ARROW_NAV" => "Y",
		"BS_BULLET_NAV" => "Y",
		"BS_HIDE_FOR_TABLETS" => "N",
		"BS_HIDE_FOR_PHONES" => "N",
		"EFFECT" => "random",
		"SPEED" => "500",
		"JQUERY" => "Y",
		"DIRECTION_NAV" => "Y",
		"CONTROL_NAV" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);*/?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"banner_main", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_B2B_BANNER_ID),
		"IBLOCK_TYPE" => "-",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "1", 
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "TITLE_1",
			2 => "TITLE_2",
			3 => "LINK_TITLE",
			4 => "LINK",
			5 => "IMG_MOBILE",
			6 => "IMG",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "banner_main"
	),
	false
);?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"catalog_1lvl_main",
	array(
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
		"SECTIONS_COUNT" => 6,
		"SUBSECTIONS_COUNT" => 6,
		"COMPONENT_TEMPLATE" => "catalog_1lvl",
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
	),
	$component,
	array(
		"HIDE_ICONS" => "N"
	)
);?>
<?global $arrFilterSelloutMain;
//$arrFilterSelloutMain = array("=PROPERTY_NOVINKA" => 'Да');
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/cron/make_list_of_novelty_on_main.txt')){
    $idJson = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/local/cron/make_list_of_novelty_on_main.txt');
    $arrID = [];
    $arrID = array_column(json_decode($idJson, true),'ID');
    $arrFilterSelloutMain['ID'] = $arrID;
}
global $USER;
?>

<?
$basePrice = Price::getPriceByCode(Price::PRICE_CODE_MIC);
$priceCode = empty($basePrice) ? '' : $basePrice['NAME'];
$userPriceType = UserDataManager\UserDataManager::getUserPriceType();
if (!empty($userPriceType)) {
    $priceCode = $userPriceType['NAME'];
}

$APPLICATION->IncludeComponent("bitrix:catalog.section", "novelty_on_main", Array(
	"IS_USER_AUTHORIZED" => $USER->IsAuthorized(),
	"COMPONENT_TEMPLATE" => "novelty_on_main",
	"IBLOCK_TYPE" => "1c_catalog",	// Тип инфоблока
	"IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_CATALOG),	// Инфоблок
	"SECTION_ID" => "",	// ID раздела
	"SECTION_CODE" => "",	// Код раздела
	"SECTION_USER_FIELDS" => array(	// Свойства раздела
		0 => "",
		1 => "",
	),
	"FILTER_NAME" => "arrFilterSelloutMain",	// Имя массива со значениями фильтра для фильтрации элементов
    "PROPERTY_CODE" => array(
        "ARTNUMBER", "ENERGY", "TEMPERATURE", "LIGHT_FLOW",
        'KOLICHESTVO_V_UPAKOVKE', 'PROPERTY_CML2_ARTICLE', 'PROPERTY_NOVINKA'),
	"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
	"SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
	"CUSTOM_FILTER" => "",
	"HIDE_NOT_AVAILABLE" => "N",	// Недоступные товары
	"HIDE_NOT_AVAILABLE_OFFERS" => "N",	// Недоступные торговые предложения
	"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
	"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
	"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
	"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
	"PAGE_ELEMENT_COUNT" => "50",	// Количество элементов на странице
	"LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
	"OFFERS_LIMIT" => "5",	// Максимальное количество предложений для показа (0 - все)
	"BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
	"TEMPLATE_THEME" => "blue",	// Цветовая тема
	"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",	// Вариант отображения товаров
	"ENLARGE_PRODUCT" => "STRICT",	// Выделять товары в списке
	"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",	// Порядок отображения блоков товара
	"SHOW_SLIDER" => "Y",	// Показывать слайдер для товаров
	"SLIDER_INTERVAL" => "3000",	// Интервал смены слайдов, мс
	"SLIDER_PROGRESS" => "N",	// Показывать полосу прогресса
	"ADD_PICT_PROP" => "-",	// Дополнительная картинка основного товара
	"LABEL_PROP" => "",	// Свойства меток товара
	"PRODUCT_SUBSCRIPTION" => "Y",	// Разрешить оповещения для отсутствующих товаров
	"SHOW_DISCOUNT_PERCENT" => "N",	// Показывать процент скидки
	"SHOW_OLD_PRICE" => "N",	// Показывать старую цену
	"SHOW_MAX_QUANTITY" => "N",	// Показывать остаток товара
	"SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
	"MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
	"MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
	"MESS_BTN_SUBSCRIBE" => "Подписаться",	// Текст кнопки "Уведомить о поступлении"
	"MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
	"MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
	"RCM_TYPE" => "personal",	// Тип рекомендации
	"RCM_PROD_ID" => '',	// Параметр ID продукта (для товарных рекомендаций)
	"SHOW_FROM_SECTION" => "N",	// Показывать товары из раздела
	"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
	"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
	"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
	"SEF_MODE" => "N",	// Включить поддержку ЧПУ
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
	"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
	"SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
	"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
	"SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
	"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
	"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
	"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
	"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
	"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
	"PRICE_CODE" => array(
		0 => $priceCode,
	),	// Тип цены
	"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
	"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
	"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
	"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
	"BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
	"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
	"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
	"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
	"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
	"PRODUCT_PROPERTIES" => array(
	),
	"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
	"ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
	"DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
	"USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
	"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
	"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
	"PAGER_TITLE" => "Товары",	// Название категорий
	"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
	"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
	"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
	"LAZY_LOAD" => "N",	// Показать кнопку ленивой загрузки Lazy Load
	"LOAD_ON_SCROLL" => "N",	// Подгружать товары при прокрутке до конца
	"SET_STATUS_404" => "N",	// Устанавливать статус 404
	"SHOW_404" => "N",	// Показ специальной страницы
	"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
	"COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
	"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
),
	false,
	array(
		"HIDE_ICONS" => "Y"
	)
);?>

<?global $arrFilterPromotionsMain;
$arrFilterPromotionsMain = array("=PROPERTY_SHOW_ON_MAIN_VALUE" => 'Y');?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"promotions_main",
	array(
		"COMPONENT_TEMPLATE" => "promotions",
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_PROMOTIONS),
		"NEWS_COUNT" => "4",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arrFilterPromotionsMain",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "SHOW_ON_MAIN",
			1 => "DATE_FROM",
			2 => "DATE_TO",
			3 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N ",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Акции",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"USE_SHARE" => "N",
		"MAIN_PAGE" => true,
	),
	false
);?>
<?global $arFilterAboutMain;
$arFilterAboutMain = array("SECTION_CODE" => false);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"advantages",
	array(
		"COMPONENT_TEMPLATE" => "advantages",
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_ABOUT_COMPANY_MAIN),
		"NEWS_COUNT" => "1",
		"SORT_BY1" => "PROPERTY_DATE",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "",
		"SORT_ORDER2" => "",
		"FILTER_NAME" => "arFilterAboutMain",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "ADVANTAGES",
			1 => "TITLE",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Статьи",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"USE_SHARE" => "N"
	),
	false,
	array(
		"HIDE_ICONS" => "Y"
	)
);?>
<div class="main-i">
	<?
    $IBlockPropertyEnum = new IBlockPropertyEnum($core->getIblockId($core::IBLOCK_CODE_PRESS_CENTER_ARTICLES), 'SHOW_ON_MAIN');
    global $arrFilterArticlesMain;
    $arrFilterArticlesMain = array("=PROPERTY_SHOW_ON_MAIN" => $IBlockPropertyEnum->getByCode('Y'));?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"articles_main",
		array(
			"COMPONENT_TEMPLATE" => "articles_main",
			"IBLOCK_TYPE" => "content",
			"IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_PRESS_CENTER_ARTICLES),
			"NEWS_COUNT" => "2",
			"SORT_BY1" => "PROPERTY_DATE",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "",
			"SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilterArticlesMain",
			"FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"PROPERTY_CODE" => array(
				1 => "SHOW_ON_MAIN",
				2 => "DATE",
				3 => "TYPE",
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "Y",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "Y",
			"STRICT_SECTION_CHECK" => "N",
			"DISPLAY_DATE" => "Y",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Статьи",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"MESSAGE_404" => "",
			"USE_SHARE" => "N"
		),
		false,
		array(
			"HIDE_ICONS" => "Y"
		)
	);?>
	<?
    $IBlockPropertyEnum = new IBlockPropertyEnum($core->getIblockId($core::IBLOCK_CODE_PRESS_CENTER_NEWS), 'SHOW_ON_MAIN');
    global $arrFilterNewsMain;
	$arrFilterNewsMain = array("=PROPERTY_SHOW_ON_MAIN" => $IBlockPropertyEnum->getByCode('Y'));
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"news_main",
		array(
			"COMPONENT_TEMPLATE" => "news_main",
			"IBLOCK_TYPE" => "content",
			"IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_PRESS_CENTER_NEWS),
			"NEWS_COUNT" => "4",
			"SORT_BY1" => "TIMESTAMP_X",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "",
			"SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilterNewsMain",
			"FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "SHOW_ON_MAIN",
				2 => "DATE",
				3 => "TYPE",
				4 => "",
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "Y",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "Y",
			"STRICT_SECTION_CHECK" => "N",
			"DISPLAY_DATE" => "Y",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"MESSAGE_404" => "",
			"USE_SHARE" => "N"
		),
		false,
		array(
			"HIDE_ICONS" => "Y"
		)
	);?>
</div>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>