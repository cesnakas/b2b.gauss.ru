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
    LocalRedirect('/auth/', true, '301 Moved Permanently');
}

$titleTypeText = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getQuery('text');

$text = '';

switch ($titleTypeText) {
    case 'favorite':
        $text = 'Для добавления товара в избранное Вам необходимо зарегистрироваться или авторизоваться';
        break;
    case 'auth':
        $text = 'Для оформления заказа Вам необходимо зарегистрироваться или авторизоваться';
        break;
}

?>
<? $APPLICATION->IncludeComponent(
    "citfact:authorize.ajax",
    "modal",
    Array(
        "FORM_ID" => "need_auth",
        "REDIRECT_TO" => "",
        "TITLE_TEXT" => $text,
    )
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");