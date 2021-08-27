<?
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("class_page", "main--about");
$APPLICATION->SetPageProperty("TITLE", "О компании");
$APPLICATION->SetTitle("О компании");

$core = Core::getInstance();
?>
<? $APPLICATION->IncludeComponent(
    "citfact:elements.list",
    "about",
    array(
        "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_ABOUT_COMPANY_SECTION),
        "FILTER" => array(),
        "FIELDS" => array('PREVIEW_TEXT', 'PREVIEW_PICTURE'),
        "PROPERTY_CODES" => array('TEXT_COLOR', 'BACKGROUND_COLOR', 'IMAGE_MOBILE'),
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600000",
    ),
    false,
    array('HIDE_ICON' => 'Y')
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
