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

<div id="confidential" class="b-modal b-modal--text-document">
    <div class="plus plus--cross b-modal__close" data-modal-close></div>
    <div class="b-modal__title">Обработка персональных данных</div>
    <div class="b-modal__content">
        <? $APPLICATION->IncludeComponent(
            "citfact:elements.list",
            "static",
            Array(
                "IBLOCK_ID" => 23,
                "FIELDS" => array('PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT'),
                "PROPERTY_CODES" => array('TITLE'),
                "SORT" => array('SORT' => 'ASC', 'ID' => 'ASC'),
                "FILTER" => array('ACTIVE_DATE' => 'Y', 'CODE' => 'confidential'),
                "ELEMENTS_COUNT" => 1,
            )
        ); ?>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>