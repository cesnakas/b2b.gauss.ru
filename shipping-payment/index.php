<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Citfact\SiteCore\Core;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle("Доставка и оплата");
$core = Core::getInstance();
LocalRedirect("/shipping-payment/company/"); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>