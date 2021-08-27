<?
define("NEED_AUTH",true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональные данные");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
	<?
	$APPLICATION->IncludeComponent(
			"citfact:main.profile",
			".default",
			[
				"PAGE" => 'company',
				"USER_ID" => $userId,
			],
			false
		); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>