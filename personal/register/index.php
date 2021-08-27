<?

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
LocalRedirect('/personal/register/add_new_user');
?>
<? $APPLICATION->IncludeComponent(
    "citfact:lk.register.newuser",
    ".default",
    Array(
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        'ELEMENTS_COUNT' => 5,
    )
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>