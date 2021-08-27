<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(false);
?>

<form method="post"
      name="lk_change_profile"
      id="lk_change_profile"
      action="<?= $arResult["FORM_TARGET"]; ?>"
      enctype="multipart/form-data"
      class="b-form"
      data-form-validation>
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
                <?= Loc::getMessage('PROFILE_DATA_SAVED'); ?>
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
                   value="<?= $arResult["arUser"]["NAME"] ?>"
                   readonly
            >
            <span class="b-form__text" data-form-error></span>
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
            <span class="b-form__text" data-form-error></span>
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
                   readonly
            >
            <span class="b-form__text" data-form-error></span>
        </div>
    </div>
</form>
