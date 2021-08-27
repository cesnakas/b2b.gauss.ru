<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

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
    <div class="b-modal" data-modal-form>
        <div class="plus plus--cross b-modal__close" data-modal-close></div>
        <div class="title-1">
            <span>Товары успешно добавлены в корзину.</span>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>