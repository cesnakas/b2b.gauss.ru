<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs($componentPath . '/script.js');
?>
<script>
  BX.message({
    COMPONENT_PATH_CITFACT_REGISTER_AJAX: '<? echo $componentPath ?>',
    arParams: <?=CUtil::PhpToJSObject($arParams)?>
  });
</script>
<div class="b-form__main">
    <form action="#" name="fact_form_register_ajax" data-form-register-ajax>
        <p class="margin-bottom-20">Для регистрации Вам необходимо заполнить всего несколько полей. Весь процесс займет
            у вас не более 5 минут.</p>


        <div class="margin-bottom-15">
            <div class="result_desc h-hide success-text">
                <?=$arResult['SUCCESS_MESSAGE']?>
            </div>

            <div class="errors_cont error-text"></div>
        </div>

        <? echo bitrix_sessid_post(); ?>

        <div class="b-form__block input-block">
            <label class="input-block__label" for="NAME">Фио:</label>
            <input type="text" name="NAME" id="NAME" placeholder="Ваше полное имя" class="input-text required">
            <div class="input-block__error-text error-required" style="display: none">
                Поле обязательно для заполнения
            </div>
        </div>

        <div class="b-form__block input-block">
            <label class="input-block__label" for="PHONE">Телефон:</label>
            <input type="text" name="PHONE" id="PHONE" placeholder="" data-input-mask="phone"
                   class="input-text required">
            <div class="input-block__error-text error-required" style="display: none">
                Поле обязательно для заполнения
            </div>
        </div>

        <div class="b-form__block input-block">
            <label class="input-block__label" for="EMAIL">Email:</label>
            <input type="text" name="EMAIL" id="EMAIL" placeholder="yourmail@example.com" class="input-text required">
            <div class="input-block__error-text error-required" style="display: none">
                Поле обязательно для заполнения
            </div>
        </div>

        <div class="b-form__block input-block">
            <label class="input-block__label" for="PASSWORD">Пароль:</label>
            <input type="password" name="PASSWORD" id="PASSWORD" placeholder="" class="input-text required">
            <div class="input-block__error-text error-required" style="display: none">
                Поле обязательно для заполнения
            </div>
        </div>

        <div class="b-form__block input-block">
            <label class="input-block__label" for="CONFIRM_PASSWORD">Подтверждение пароля:</label>
            <input type="password" name="CONFIRM_PASSWORD" id="CONFIRM_PASSWORD" placeholder=""
                   class="input-text required">
            <div class="input-block__error-text error-required" style="display: none">
                Поле обязательно для заполнения
            </div>
        </div>

        <div class="b-form__block input-block">
            <div class="b-checkbox">
                <input type="checkbox" class="b-checkbox__input" name="SUBSCRIBE" id="SUBSCRIBE" value="Y" checked>
                <label for="SUBSCRIBE" class="b-checkbox__label">
                  <span class="b-checkbox__box">
                      <span class="b-checkbox__line b-checkbox__line--short"></span>
                      <span class="b-checkbox__line b-checkbox__line--long"></span>
                  </span>
                  <span class="b-checkbox__text">
                      Подписаться на e-mail и sms рассылку от компании
                  </span>
                </label>
            </div>
        </div>

        <div class="b-form__block input-block">
            <div class="b-checkbox">
                <input type="checkbox" class="b-checkbox__input" name="CONFIRM_RULES" id="CONFIRM_RULES" value="Y" checked disabled>
                <label for="CONFIRM_RULES" class="b-checkbox__label">
                  <span class="b-checkbox__box">
                      <span class="b-checkbox__line b-checkbox__line--short"></span>
                      <span class="b-checkbox__line b-checkbox__line--long"></span>
                  </span>
                    <span class="b-checkbox__text">
                      Я согласен с правилами обработки персональных данных на сайте *
                  </span>
                </label>
            </div>
        </div>

        <div class="b-form__block input-block">
            <button type="submit" class="btn" onclick="Analytics.yaMetrikaGoal('registration');">Зарегистрироваться</button>
        </div>


    </form>
    <div class="result_cont"></div>
</div>