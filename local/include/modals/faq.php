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
<? $APPLICATION->IncludeComponent(
    "citfact:form.ajax",
    "faq",
    Array(
        "IBLOCK_ID" => 17,
        "SHOW_PROPERTIES" => array(
            "NAME" => array(
                "type" => "text",
                "placeholder" => "Ваше имя",
                "required" => "Y",
                "class" => "",
            ),
            "EMAIL" => array(
                "type" => "text",
                "placeholder" => "E-mail",
                "required" => "Y",
                "class" => "",
            ),
            "QUESTION" => array(
                "type" => "text",
                "placeholder" => "Вопрос",
                "required" => "Y",
                "class" => "",
            ),
        ),
        "EVENT_NAME" => 'FAQ',
        "EVENT_MESSAGE_ID" => array(''),
        "SUCCESS_MESSAGE" => 'Ваш вопрос принят.<br/>Мы свяжемся с вами в ближайшее время.',
        "ELEMENT_ACTIVE" => 'N',
        "ATTACH_FILES" => 'N',
        "FILE_PROPERTY_CODE" => '',
        "AJAX_FILES_PATH" => '/upload/ajax_files'
    )
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>