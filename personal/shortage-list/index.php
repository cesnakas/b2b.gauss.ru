<?
define("NEED_AUTH", true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\CountdownTimer;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заявки на недостачу/пересорт");

CModule::IncludeModule("form");
global $USER;
$userId = $GLOBALS["USER"]->GetID();
?><?$APPLICATION->IncludeComponent(
	"citfact:shortage.list",
	"shortage-list", 
	array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FORMS" => array(
			0 => "21",
			1 => "",
		),
		"NUM_RESULTS" => "15",
		"COMPONENT_TEMPLATE" => "shortage-list"
	),
	false
);?><br>
 <br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>