<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var OrderMake $component */

$date = strtotime($arResult['ORDER']['DATE_INSERT']);
$hourInsert = (int)date('H', $date);
?>
<div class="static-content order-final">
    <div class="title-1"><span>Спасибо, Ваш заказ успешно принят!</span></div>
    <p>Вы можете отслеживать выполнение заказа в
        <a href="/personal/orders/" class="link" title="Личный кабинет">Личном кабинете</a>
        по  <a href="/personal/orders/<?= $arResult['ORDER']['ACCOUNT_NUMBER']?>/" title="№<?= $arResult['ORDER']['ACCOUNT_NUMBER']?>" class="link">ссылке</a></p>
    <p>Счет на оплату будет выставлен после обработки заказа ассистентом. Счет будет доступен в личном кабинете.</p>

    <? if ($hourInsert >= 14) { ?>
        <p>Заявка на доставку будет сформирована завтра.</p>
    <? } else { ?>
        <p>Заявка на доставку будет сформирована в течение текущего дня.</p>
    <? } ?>
</div>