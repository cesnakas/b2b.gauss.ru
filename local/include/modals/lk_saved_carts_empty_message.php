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
            <span>Нету возможности добавить или оформить заказ, так как товары в сохраненной корзине на данный момент отсутствуют в каталоге</span>
        </div>
        <div class="b-modal__content">

            <div class="basket basket--modal">

                <div class="basket__inner">
                    <div class="basket__bottom">

                        <div class="basket__filter b-form">
                            <a href="/catalog/" class="btn btn--grey" data-modal-close>Перейти в каталог</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>