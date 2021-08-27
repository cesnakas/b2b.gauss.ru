<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(false);
?>

<? if ($arResult['arUser']['UF_ACTIVATE_PROFILE']) { ?>
    <div class="lk__section">
        <? if ($component->isAjax == true) {
            $GLOBALS['APPLICATION']->RestartBuffer();
        } ?>
        <form method="post"
              name="lk_change_profile"
              id="lk_change_profile"
              action="<?= $arResult["FORM_TARGET"]; ?>"
              enctype="multipart/form-data"
              class="b-form"
              data-form-validation>
            <p>Выберите юр.лицо от которого будет оформлен заказ.
                Пользователя можно изменять в разделе "Персональные данные" и "Корзина".</p>
            <? $APPLICATION->IncludeComponent(
                "citfact:contragent.list",
                ".default",
                Array()
            ); ?>
            <?= $arResult["BX_SESSION_CHECK"] ?>
            <input type="hidden" name="LOGIN" value="<?= $arResult["arUser"]["LOGIN"] ?>"/>
            <input type="hidden" name="EMAIL" value="<? echo $arResult["arUser"]["EMAIL"] ?>"/>
            <input type="hidden" name="lang" value="<?= LANG ?>"/>
            <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>"/>
            <div class="b-tabs">
                <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/personal/profile/tabs-head.php"; ?>
                <div class="b-tabs__content">
                    <div class="b-tabs__item active">
                        <? if (!empty($arResult["ERRORS"])) { ?>
                            <div class="errors_cont scroll-to">
                                <? foreach ($arResult["ERRORS"] as $error) { ?>
                                    <?= $error; ?><br>
                                <? } ?>
                            </div><br>
                        <? } ?>
                        <? if ($arResult['SUCCESS'] === true) { ?>
                            <div class="result_cont scroll-to">
                                Ваши персональные данные обновлены!
                            </div><br>
                        <? } ?>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Ф.И.О. *</span>
                            <input type="text"
                                   id="user_name"
                                   name="NAME"
                                   placeholder=""
                                   maxlength="255"
                                   data-f-field
                                   data-required="Y"
                                   value="<?= $arResult["arUser"]["LAST_NAME"] .' '. $arResult["arUser"]["NAME"] . ' '.$arResult["arUser"]["SECOND_NAME"]?>"
                                   readonly
                            >
                            <span class="b-form__text" data-form-error>

                            </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>E-mail *</span>
                            <input type="email"
                                   id="user_email"
                                   name="EMAIL"
                                   placeholder=""
                                   maxlength="50"
                                   data-f-field
                                   data-required="Y"
                                   data-form-field-email
                                   value="<?= $arResult["arUser"]["EMAIL"] ?>"
                                   readonly
                            >
                            <span class="b-form__text" data-form-error>

                            </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Мобильный телефон *</span>
                            <input type="text"
                                   id="user_phone"
                                   name="PERSONAL_PHONE"
                                   placeholder=""
                                   maxlength="50"
                                   data-required="Y"
                                   data-form-field-phone
                                   data-mask="phone"
                                   data-f-field
                                   value="<?= $arResult["arUser"]["PERSONAL_PHONE"] ?>"
                            >
                            <span class="b-form__text" data-form-error>

                            </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Должность</span>
                            <input type="text"
                                   id="user_work_position"
                                   name="WORK_POSITION"
                                   placeholder=""
                                   maxlength="50"
                                   data-f-field
                                   value="<?= $arResult["arUser"]["WORK_POSITION"] ?>"
                                   disabled
                            >
                            <span class="b-form__text" data-form-error>

                            </span>
                        </div>
                        <input type="text" class="hidden">
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Пароль</span>
                            <input type="password"
                                   id="user_pwd"
                                   name="NEW_PASSWORD"
                                   maxlength="50"
                                   autocomplete="new-password"
                                   placeholder=""
                                   value=""
                                   data-f-field
                            >
                            <span class="b-form__text" data-form-error>

                            </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Подтверждение нового пароля</span>
                            <input type="password"
                                   id="user_pwdrepeat"
                                   name="NEW_PASSWORD_CONFIRM"
                                   maxlength="50"
                                   autocomplete="off"
                                   placeholder=""
                                   value=""
                                   data-f-field
                            >
                            <span class="b-form__text" data-form-error>

                            </span>
                        </div>
                        <div class="b-form__pp">Мы настоятельно рекомендуем Вам придумать достаточно надежный пароль.
                            Пароль может
                            содержать латинские строчные ии заглавные буквы, а также цифры 0-9.
                            Не передавайте доступы к Вашему личному кабинету третьим лицам.
                        </div>
                        <div class="b-form__bottom">
                            <button class="btn btn--transparent btn--big"
                                    type="submit"
                                    name="save"
                                    value="Сохранить изменения"
                                    data-agree-submit="WEB_FORM_AJAX">Сохранить изменения</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
<? } else {
    echo "Личный кабинет будет доступен после его активации Вашим менеджером.";
}; ?>
<? if ($component->isAjax !== true) { ?>
    <? $fileMTime = filemtime($_SERVER['DOCUMENT_ROOT'] . $componentPath . '/script.js'); ?>
    <script src="<?= $componentPath . '/script.js' . '?' . $fileMTime ?>"></script>
    <script>
        if (window.ProfileChange && typeof window.ProfileChange.inited !== "undefined" && window.ProfileChange.inited !== true) {
            // signedParameters - перечень ключей параметров компонента
            ProfileChange.init(<?=json_encode([
                'signedParameters' => $component->getSignedParameters(),
                'htmlContainerSelector' => '#lk_change_profile',
                'formSelector' => '#lk_change_profile',
            ])?>);
        }
    </script>
<? } else { ?>
    <script>Am.validation.run();</script>
<? } ?>

<? if ($component->isAjax === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
} ?>
