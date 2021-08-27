<?

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Дебиторская задолженность");

global $USER;
$userId = $GLOBALS["USER"]->GetID();

?>
    <div class="lk-receivables">
        <? $APPLICATION->IncludeComponent(
            "citfact:receivables.list",
            ".default",
            Array(
                "AJAX_MODE" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_HISTORY" => "N",
                'ELEMENTS_COUNT' => 5,
                'XML_ID' => $_REQUEST['XML_ID'],
            )
        ); ?>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>