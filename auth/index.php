<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

$APPLICATION->SetTitle("Авторизация");

if ($USER->IsAuthorized())
	LocalRedirect('/personal/');
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>