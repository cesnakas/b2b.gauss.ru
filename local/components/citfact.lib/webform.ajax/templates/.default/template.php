<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
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
/** @var CBitrixComponent $component */

$frame = $this->createFrame()->begin();

?>
<div class="form <?= $arParams['CONTAINER_CLASS']; ?>"
     data-hidden-fields-check-form-submit
     data-form-submit="<?= $arResult['WEB_FORM_NAME']; ?>">
    <form method="POST"
          action="<?= $APPLICATION->GetCurDir(); ?>"
          name="webform_consultation">
        <?= bitrix_sessid_post(); ?>

        <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">

        <div class="form__content">
            <h3>
                <span><? if ($arParams['SHOW_NAME'] == 'Y'): echo $arResult['arForm']['NAME']; endif; ?></span>
            </h3>

            <?php
            if (!empty($arResult['SUCCESS'])): ?>
                <div class="modal__wrap">
                    <div class="modal__status success"></div>
                    <div class="modal__info">
                        <div class="modal__subtitle"><?= Loc::getMessage('SUCCESS_CONGRATULATE'); ?></div>
                        <div>Спасибо! Ваше сообщение отправлено в администрацию магазина.</div>
                    </div>
                </div>
            <? else: ?>
                <? foreach ($arResult['QUESTIONS'] as $name => $field): ?>
                    <? if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'): ?>
                        <?= $field['HTML_CODE'] ?>
                    <? elseif ($field['STRUCTURE'][0]['FIELD_TYPE'] !== 'file'): ?>
                        <label class="form__label">
                            <span><?= $field['CAPTION']; ?><?= ($field['REQUIRED'] == 'Y' ? '*' : '') ?></span>
                            <div class="form-input">
                                <?= $field['HTML_CODE']; ?>

                                <div class="tooltip">
                                    <svg class='i-icon'>
                                        <use xlink:href='#icon-i'/>
                                    </svg>
                                    <div class="tooltip__content">
                                        Заполните поле корректным значением
                                    </div>
                                </div>
                            </div>
                        </label>
                    <? else: ?>
                        <div class="form__file" data-file-field>
                            <label>
                                <span class="form__label">
                                    <span><?= $field['CAPTION']; ?><?= ($field['REQUIRED'] == 'Y' ? '*' : '') ?></span>
                                </span>
                                <?= $field['HTML_CODE']; ?>
                            </label>

                            <div class="error-required form--error hidden" data-form-error>
                                <div class="form-item-error">
                                    Поле обязательно для заполнения
                                </div>
                            </div>
                            <div class="error-invalid-size form--error hidden" data-form-error-size>
                                <div class="form-item-error">
                                    Превышен допустимый размер файла 20 Мб
                                </div>
                            </div>
                            <div class="error-valid-extension form--error hidden" data-form-error-extension>
                                <div class="form-item-error">
                                    Недопустимый тип файла
                                </div>
                            </div>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>
                <div class="form__label">
                    <label class="b-checkbox__label">
                        <input type="checkbox" name="agree" value="1" class="b-checkbox__input" checked
                               data-agree-check-box="WEB_FORM_AJAX">
                        <span class="b-checkbox__box">
                            <span class="b-checkbox__line b-checkbox__line--short"></span>
                            <span class="b-checkbox__line b-checkbox__line--long"></span>
                        </span>
                        <span class="b-checkbox__text">Я прочитал и согласен с <a href="/policy/"
                                                                                  title="Политика в отношении обработки персональных данных"
                                                                                  class="link dotted popup-ajax">Условиями обработки моих персональных данных</a> и ознакомлен с <a
                                    href="<?php echo SITE_DIR; ?>policy/" title="privacy" class="link dotted popup-ajax" rel="noopener noreferrer" target="_blank">Политикой конфиденциальности компании</a>.</span>
                    </label>
                </div>
                <div class="form__agreement form__agreement--check">
                    <button type="submit" data-agree-submit="WEB_FORM_AJAX" class="btn btn--bright-blue"><?= $arResult['arForm']['BUTTON']; ?></button>
                </div>
            <? endif; ?>

        </div>
    </form>
</div>

<? $frame->end(); ?>
