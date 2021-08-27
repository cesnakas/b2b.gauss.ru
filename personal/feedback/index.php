<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Citfact\SiteCore\Core;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle("Обратная связь");
$core = Core::getInstance();
LocalRedirect("/personal/feedback/review/"); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>