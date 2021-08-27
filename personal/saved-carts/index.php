<?

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Сохраненные корзины");
$APPLICATION->SetPageProperty('title', "Сохраненные корзины");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
    <div class="lk-receivables">
        <? $APPLICATION->IncludeComponent(
            "citfact:lk.saved_carts",
            ".default",
            Array(
                "AJAX_MODE" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_HISTORY" => "N",
                'ELEMENTS_COUNT' => 5,
            )
        ); ?>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>