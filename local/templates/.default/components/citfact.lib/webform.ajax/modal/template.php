<?php

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
    <div class="b-modal"
         data-hidden-fields-check-form-submit
         data-form-submit="<?= $arResult['WEB_FORM_NAME']; ?>"
         data-form-submit-url="<?= $arParams['AJAX_URL'] ?>">

        <div class="b-modal__close" data-modal-close="">
            <div class="plus plus--cross"></div>
        </div>
        <?php if ('Y' === $arParams['SHOW_FORM_TITLE']) { ?>
            <div class="title-1">
                <span><?php echo $arResult['arForm']['NAME']; ?></span>
            </div>
        <?php } ?>

        <?php
        if (!empty($arResult['SUCCESS'])) { ?>
            <p>Ваша заявка отправлена. В ближайшее время наш менеджер свяжется с Вами.</p>
        <?php } else { ?>


            <form class="b-form"
                  action="<?= $APPLICATION->GetCurDir(); ?>"
                  method="post"
                  enctype="multipart/form-data"
                  name="<?= $arResult['WEB_FORM_NAME']; ?>"
                  data-form-validation>

                <?= bitrix_sessid_post(); ?>
                <input type="hidden" name="WEB_FORM_CODE" value="<?php echo $arParams['WEB_FORM_CODE']; ?>">

                <?php foreach ($arResult['QUESTIONS'] as $name => $field) { ?>
                    <? if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') { ?>
                        <?= $field['HTML_CODE'] ?>
                    <? } elseif ($field['STRUCTURE'][0]['FIELD_TYPE'] === 'date') { ?>

                        <div class="b-form__item" data-f-item data-datepicker>
                            <span class="b-form__label" data-f-label>
                                <?= $field['CAPTION']; ?>

                                <?= $field['REQUIRED'] === 'Y' && $field['STRUCTURE']['0']['FIELD_TYPE'] !== 'file' ? '*' : ''; ?>
                            </span>
                            <?= $field['HTML_CODE']; ?>
                            <span class="b-form__text alert alert--error hidden" data-form-error></span>
                        </div>

                    <? } else { ?>

                        <div
                            class="b-form__item <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'dropdown' ? 'b-form__item--select' :
                                ($field['STRUCTURE']['0']['FIELD_TYPE'] === 'textarea' ? 'b-form__item--textarea' : ''); ?>"
                            data-f-item>
                        <span class="b-form__label" data-f-label>
                            <?= $field['CAPTION']; ?>&nbsp;
                            <?= ($field['REQUIRED'] === 'Y' ? '*' : ''); ?>
                        </span>
                            <?= $field['HTML_CODE']; ?>
                            <?php
                            if ($field['REQUIRED'] == 'Y') { ?>
                                <span class="b-form__text alert alert--error hidden" data-form-error>
                                <?= Loc::getMessage('REQUIRED_FIELD'); ?>
                            </span>
                            <?php } ?>
                        </div>

                    <?php } ?>
                <?php } ?>

                <div class="b-modal__bottom">
                    <div class="b-form__pp">
                        Нажимая на кнопку, я подтверждаю свое согласие на <a href="/policy/" rel="noopener noreferrer" target="_blank">«Политику в отношении
                            обработки персональных данных»</a>
                    </div>
                    <button type="submit"
                            data-agree-submit="WEB_FORM_AJAX"
                            class="btn btn--transparent btn--big">
                        <?= $arResult['arForm']['BUTTON']; ?>
                    </button>
                </div>
            </form>
        <?php } ?>
    </div>

<?php $frame->end();
