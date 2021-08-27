<?

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$request = $context->getRequest();
$contragentGuid = $request->get("contragent_guid");
?>
<? $APPLICATION->IncludeComponent("citfact:lk.register.existinguser", ".default",
    array(
        'contragent_guid' => $contragentGuid
    ),
    false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>