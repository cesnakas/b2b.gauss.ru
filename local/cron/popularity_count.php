<?

use Citfact\SiteCore\Core;

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

if (STOP_STATISTICS)
    die;

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$dbBasketItems = Bitrix\Sale\Internals\BasketTable::getlist([
    'select' => ['PRODUCT_ID', 'QUANTITY']
]);

$productIdCount = [];
while ($arItem = $dbBasketItems->fetch()) {
    $productId = $arItem['PRODUCT_ID'];
    $productIdCount[$productId] += $arItem['QUANTITY'];
}

$core = Core::getInstance();
$IBLOCK_ID = $core->getIblockId($core::IBLOCK_CODE_CATALOG); //инфоблок каталога
foreach ($productIdCount as $productId => $quantity) {
    CIBlockElement::SetPropertyValuesEx($productId, false, array("POPULARITY" => $quantity));
}
