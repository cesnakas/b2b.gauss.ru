<?php
//Проверка для того, чтобы пролог не подключался второй раз при подключении файла.
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    define("STOP_STATISTICS", true);
    define("NO_KEEP_STATISTIC", "Y");
    define("NO_AGENT_STATISTIC", "Y");
    define("DisableEventsCheck", true);
    define("BX_SECURITY_SHOW_MESSAGE", true);
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    $bPrologIncluded = true;
}

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

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$savedCartId = $request->getQuery('cart-id');
?>
    <div class="b-modal" data-modal-form>
        <div class="plus plus--cross b-modal__close" data-modal-close></div>
        <div class="title-1">
            <span>Выберите действие:</span>
        </div>
        <div class="b-modal-f__bottom">
            <a href="javascript:void(0);"
               data-savedcart-id="<?php echo $savedCartId; ?>"
               data-cart-add
               class="btn btn--transparent">Добавить позиции в корзину</a>
            <a href="javascript:void(0);"
               data-savedcart-id="<?php echo $savedCartId; ?>"
               data-cart-change
               class="btn btn--transparent">Заменить текущую корзину</a>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>