<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Citfact\SiteCore\Core;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle("Обучающий центр");
$core = Core::getInstance();
LocalRedirect("/education/presentation/");?>
<? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/education/tabs-head.php"; ?>
    <br>Обучающая презентация<br>
    <u>Скачать</u>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>