<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//ajax
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
      || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    \Bitrix\Iblock\Component\Tools::process404(
        '404 Not Found'
        ,true
        ,"Y"
        ,"Y"
        , ""
    );
}
?>
<?php
$APPLICATION->IncludeComponent(
    "citfact.lib:webform.ajax",
    "modal",
    Array(
        'WEB_FORM_CODE' => 'SIMPLE_FORM_7',
        'SUCCESS_MESSAGE' => 'После обработки заявки с Вами свяжется менеджер компании',
        'SET_PLACEHOLDER' => 'N',
        'SHOW_FORM_TITLE' => 'Y',
        'AJAX_URL' => $APPLICATION->GetCurPage()
    ),
    false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>