<?php

use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;

///Проверка для того, чтобы пролог не подключался второй раз при подключении файла.
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
/*if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    \Bitrix\Iblock\Component\Tools::process404(
        '404 Not Found'
        ,true
        ,"Y"
        ,"Y"
        , ""
    );
}*/

$basket = Basket::loadItemsForFUser(
///Получение ID покупателя (НЕ ID пользователя!)
    Fuser::getId(),

    ///Текущий сайт
    Context::getCurrent()->getSite()
);

$isBasketEmpty = $basket->isEmpty();
?>
<div id="template-order" class="b-modal" data-modal-open=""
     data-form-submit="SAVE_BASKET" data-form-callback="successSaveCart">
    <form class="b-form" action="#" method="post"
          enctype="multipart/form-data" name="SAVE_BASKET">
        <input type="hidden" name="isAjaxAction" value="Y">
        <input type="hidden" name="action" value="saveCart">
        <input type="hidden" name="saveTemplateOrder" value="Y">
        <?=bitrix_sessid_post()?>

        <div class="title-1">
            <span>Сохранение корзины</span>
        </div>
        <?
        if ($_REQUEST['error'] == 'Y') {
            ?>
            <div>
                <font class="errortext">При сохранении возникла ошибка. Попробуйте перезагрузить страницу.</font>
            </div>
            <br>
            <button data-modal-close type="submit" class="btn btn--transparent btn--big">Закрыть</button>
            <?

        } elseif($_REQUEST['success'] == 'Y') {
            ?>
            <div>
                <font class="success">Ваша корзина успешно сохранена</font>
            </div>
            <br>
            <button data-modal-close type="submit" class="btn btn--transparent btn--big">Закрыть</button>
            <?
        } elseif(true === $isBasketEmpty) {
            ?>
            <div>
                <font class="errortext">В корзине отсутствуют товары</font>
            </div>
            <br>
            <button data-modal-close type="submit" class="btn btn--transparent btn--big">Закрыть</button>
            <?
        } else {
            ?>
            <div class="b-form__item init" data-f-item="">
            <span class="b-form__label" data-f-label="">
                Введите название корзины&nbsp;*
            </span>
                <input type="text" class="" data-f-field="" data-required="Y"
                       placeholder="Название*" name="name" value="" size="0">
                <span class="b-form__text alert alert--error hidden" data-form-error="">Некорректно заполнено поле</span>
            </div>
            <div class="b-form__item init" data-f-item="">
            <span class="b-form__label" data-f-label="">
                Введите описание
            </span>
                <input type="text" class="" data-f-field=""
                       placeholder="Описание" name="description" value="" size="0">
            </div>
            <div class="b-modal__bottom">
                <button type="submit" class="btn btn--transparent btn--big">Сохранить</button>
                <button data-modal-close type="submit" class="btn btn--grey btn--big">Закрыть</button>
            </div>
            <?
        }
        ?>
    </form>
</div>
<? if ($bPrologIncluded) { ?>
    <? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>
<? } ?>