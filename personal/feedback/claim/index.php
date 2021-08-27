<?
define("NEED_AUTH",true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Претензия");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
    <div class="lk__section lk__section--claim">
        <p>
            Специально для наших клиентов разработан сервис, где каждый сможет оставить отзыв, предложение по улучшению работы или претензию.
        </p>

        <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/personal/feedback/tabs-head.php"; ?>

        <? $APPLICATION->IncludeComponent(
            "citfact.lib:webform.ajax",
            "page",
            array(
                "WEB_FORM_CODE" => "SIMPLE_FORM_13",
                "COMPONENT_TEMPLATE" => "page",
                "PARAM" => "",
                'SET_PLACEHOLDER' => 'N',
                "HIDDEN_FIELD" => "COMPLEX_NAME",
                "HIDDEN_FIELD_VALUE" => "",
            ),
            false
        ); ?>
    </div>
    <script src="../script.js"></script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>