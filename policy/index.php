<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Citfact\SiteCore\Core;

$APPLICATION->SetTitle("Политика конфиденциальности");
?>
<? $APPLICATION->IncludeComponent(
    "citfact:elements.list",
    "privacy_policy",
    array(
        "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_PRIVACY_POLOCY),
        "FILTER" => array(),
        "FIELDS" => array('PREVIEW_TEXT'),
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600000",
    ),
    false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>