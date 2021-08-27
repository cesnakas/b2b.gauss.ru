<?
define("NEED_AUTH",true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Счет-фактура на аванс");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
    <div class="lk__section">
        <p>
            Техническая документация расположена на странице:
            <a href="/technical-documentation/" class="link" title="https://www.gauss.ru/technical-documentation/">
                https://b2b.gauss.ru/technical-documentation/
            </a>
        </p>
        <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/personal/documents/tabs-head.php"; ?>

        <? $APPLICATION->IncludeComponent(
            "citfact.lib:webform.ajax",
            "page",
            array(
                "WEB_FORM_CODE" => "SIMPLE_FORM_15",
                "COMPONENT_TEMPLATE" => "page",
                "PARAM" => "",
                'SET_PLACEHOLDER' => 'N',
                "HIDDEN_FIELD" => "COMPLEX_NAME",
                "HIDDEN_FIELD_VALUE" => "",
            ),
            false
        ); ?>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>