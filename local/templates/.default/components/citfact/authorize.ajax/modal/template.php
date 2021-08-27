<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="b-modal" id="modal-auth">
    <div class="b-modal__close" data-modal-close="">
        <div class="plus plus--cross"></div>
    </div>

    <div class="title-1">
        <span>Авторизация</span>
    </div>

    <?php
    if (!empty($arParams['TITLE_TEXT'])) { ?>
        <p><?php echo $arParams['TITLE_TEXT']; ?></p>
    <?php } ?>

    <form class="b-form"
          action="<?php echo POST_FORM_ACTION_URI; ?>"
          name="citfact_authorize_ajax_form"
          data-form-validation="auth">

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
            <span class="b-form__label" data-f-label><?= Loc::getMessage('CITFACT_AUTH_TITLE_EMAIL'); ?></span>
            <input type="text"
                   id="LOGIN"
                   name="LOGIN"
                   data-f-field
                   data-required="Y"
                   value="<?= $arResult['VALUE']; ?>">
            <span class="b-form__text alert alert--error hidden" data-form-error>

            </span>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label><?= Loc::getMessage('CITFACT_AUTH_PASSWORD'); ?></span>
            <input type="password"
                   id="USER_PASSWORD"
                   name="USER_PASSWORD"
                   data-f-field
                   data-required="Y"
                   value="<?= $arResult['VALUE']; ?>">
            <svg class="b-form__show" data-show-password>
                <use xlink:href="#icon-blind"></use>
            </svg>
            <span class="b-form__text alert alert--error hidden" data-form-error>

            </span>
        </div>

        <div class="b-modal__bottom b-modal__bottom--auth">
            <div>
                <a href="/auth/?register=yes" class="link">
                    <?= Loc::getMessage('CITFACT_AUTH_REGISTRATION'); ?>
                </a>
                <a href="/auth/?forgot_password=yes" class="link">
                    <?= Loc::getMessage('CITFACT_AUTH_FORGOT_PASSWORD'); ?>
                </a>
            </div>
            <button type="submit" class="btn btn--transparent btn--big">Войти</button>
        </div>
    </form>

</div>

<script>
    BX.message({
        COMPONENT_PATH_CITFACT_AUTHORIZE_AJAX: '<? echo $componentPath ?>'
    });
</script>
<script type="text/javascript" src="<?= $templateFolder . '/script.js' ?>"></script>
