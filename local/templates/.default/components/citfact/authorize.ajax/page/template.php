<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;
$APPLICATION->SetPageProperty("class", 'auth-page');
?>
<div class="auth static-content">
    <form action="#" name="citfact_authorize_ajax_form" class="b-form b-form--small" data-form-validation="auth">

        <div class="errors_auth"></div>
        <div class="success_auth"></div>

        <? echo bitrix_sessid_post(); ?>

        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="AUTH">
        <input type="hidden" name="Login" value="Войти">
        <input type="hidden" name="USER_LOGIN" value="" data-login-clear>

        <? if (strlen($arResult["BACKURL"]) > 0): ?>
            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
        <? endif ?>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>Логин *</span>
            <input type="text"
                   id="page_LOGIN"
                   class="b-input__input"
                   name="LOGIN"
                   value=""
                   data-f-field
                   autocomplete="new-password"
                   data-required="Y">
            <span class="b-form__text" data-form-error>

            </span>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>Пароль *</span>
            <input type="password"
                   id="page_USER_PASSWORD"
                   name="USER_PASSWORD"
                   maxlength="50"
                   placeholder=""
                   value=""
                   data-f-field
                   autocomplete="new-password"
                   data-required="Y">
            <svg class="b-form__show" data-show-password>
                <use xlink:href="#icon-blind"></use>
            </svg>
            <span class="b-form__text" data-form-error>

            </span>
        </div>

        <div class="b-modal__bottom b-modal__bottom--auth">
            <div>
                <a href="?register=yes" class="link">Регистрация</a>
                <a href="?forgot_password=yes" class="link">Восстановить пароль</a>
            </div>
            <button type="submit" class="btn btn--transparent btn--big">Войти</button>
        </div>
    </form>
    <?if($arResult["AUTH_SERVICES"]):?>
        <?
        $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
            array(
                "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
                "AUTH_URL" => $arResult["AUTH_URL"],
                "POST" => $arResult["POST"],
                "SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
                "FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
                "AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
            ),
            $component,
            array("HIDE_ICONS"=>"Y")
        );
        ?>
    <?endif?>
</div>

<script>
    BX.message({
        COMPONENT_PATH_CITFACT_AUTHORIZE_AJAX: '<? echo $componentPath ?>'
    });
</script>
<script type="text/javascript" src="<?= $templateFolder . '/script.js' ?>"></script>
