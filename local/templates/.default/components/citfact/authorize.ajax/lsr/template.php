<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

//Asset::getInstance()->addJs($componentPath . '/script.js');
?>
<div id="modal-auth" class="b-modal">
    <div class="plus plus--cross b-modal__close" data-modal-close></div>
    <div class="b-modal__title">Авторизация</div>
    <form action="#" name="citfact_authorize_ajax_form" class="b-form">
        <div class="errors_cont"></div>
        <div class="result_cont"></div>
        <div class="b-modal__content">
            <? echo bitrix_sessid_post(); ?>
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="AUTH">
            <input type="hidden" name="Login" value="Войти">
            <input type="hidden" name="USER_LOGIN" value="" data-login-clear>

            <div data-form-item>
                <div class="b-form__section">
                    <input type="text"
                           name="LOGIN"
                           maxlength="50"
                           class="b-form__input phone required"
                           placeholder="Номер телефона"
                           value="<?//= $_COOKIE[COption::GetOptionString("main", "cookie_name", "BITRIX_SM") . "_LOGIN"] ?>"
                           data-mask="phone"
                           data-login
                    >
                    <span class="tooltip">
                            ?
                            <span class="tooltip__content">
                                 Логином для входа в личный кабинет является основной номер телефона, указанный при регистрации.
                            </span>
                        </span>
                </div>
                <div class="b-form__error error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
                <div class="b-form__error error-format" style="display: none">
                    Поле заполнено неверно
                </div>
            </div>
            <div data-form-item>
                <input type="password"
                       name="USER_PASSWORD"
                       placeholder="Пароль"
                       class="b-form__input required"
                       maxlength="30">
                <div class="b-form__error error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>
            <div class="b-form__privacy">
                Нажимая на кнопку, я подтверждаю свое согласие
                на <a href="/confidential/" target="_blank" data-modal="ajax">«Обработку персональных данных»</a>
            </div>

            <div class="b-modal__buttons">
                <input type="submit" class="btn btn--blue" value="Войти">
                <a href="/account/?forgot_password=yes" class="btn">Восстановить пароль</a>
            </div>
            <a href="/register/" class="b-modal__register">Зарегистрироваться</a>
        </div>
    </form>
</div>
<script>
    BX.message({
        COMPONENT_PATH_CITFACT_AUTHORIZE_AJAX: '<? echo $componentPath ?>',
        arParams: <?=CUtil::PhpToJSObject($arParams)?>
    });
    window.inputMask.run();
</script>
<script type="text/javascript" src="<?= $componentPath . '/script.js' ?>"></script>