<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];
$arResult['NUM_DELAYED'] = count($arResult["CATEGORIES"]["DELAY"]);

$core = \Citfact\SiteCore\Core::getInstance();
$iblockID = $core->getIblockId($core::IBLOCK_CODE_CATALOG);
$arResult['NUM_COMPARE'] = count($_SESSION["CATALOG_COMPARE_LIST"][$iblockID]["ITEMS"]);

require(realpath(dirname(__FILE__)).'/top_template.php');
