<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

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
?>
<div class="order">
    <div class="order__main">
        <div class="title-3">Требуется авторизация</div>
        <? $linkRegister = SITE_DIR . 'personal/registration/?backurl=' . SITE_DIR . 'order/'; ?>
        <p><?= Loc::getMessage('ORDER_AUTH_TEXT', ['#LINK_REGISTER#' => $linkRegister]);?></p>
        <?$APPLICATION->IncludeComponent(
            "citfact:authorize.ajax",
            "page",
            Array(
                "FORM_ID" => "need_auth",
                "REDIRECT_TO" => ""
            )
        );?>
    </div>
</div>