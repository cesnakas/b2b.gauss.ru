<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

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

$captchaCode = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getQuery('captcha_code');

use Bitrix\Main\Localization\Loc; ?>

    <div class="b-modal" data-captcha-modal>
        <div class="b-modal__close" data-modal-close="">
            <div class="plus plus--cross"></div>
        </div>

        <div class="title-1">
            <span>Введите код с картинки</span>
        </div>

        <div class="b-form">

            <div class="b-form__inner b-form__inner--double">
                <div class="b-form__item" data-f-item>
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $captchaCode; ?>" alt="CAPTCHA" height="55">
                </div>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>Введите код с картинки</span>
                    <input type="text" data-captcha-word maxlength="50" data-f-field value="" autocomplete="off">
                    <span class="b-form__text hidden" data-form-error="">Поле не заполнено</span>
                </div>
            </div>

            <div class="b-modal__bottom">
                <button type="button" data-submit-reg-form class="btn btn--transparent btn--big">Зарегистрироваться</button>
            </div>

        </div>

    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");