<?
define("NEED_AUTH", true);

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заявка на недостачу/пересорт");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>


        <? $APPLICATION->IncludeComponent(
            "citfact.lib:webform.ajax",
            "shortage-form",
            array(
                "WEB_FORM_CODE" => "SIMPLE_FORM_21",
                "COMPONENT_TEMPLATE" => "shortage-form",
                "PARAM" => "",
                'SET_PLACEHOLDER' => 'N',
                "SUCCESS_MESSAGE" => "Ваша Заявка на недостачу/пересорт принята [ID - #id#]. В течение 3 рабочих дней с 
                Вами свяжется сотрудник Клиентского сервиса.",
                "HIDDEN_FIELD" => "COMPLEX_NAME",
                "HIDDEN_FIELD_VALUE" => "",
            ),
            false
        ); ?>



<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>