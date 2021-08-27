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
<div data-hidden-fields-check-form-submit
     data-form-submit="<?= $arResult['WEB_FORM_NAME']; ?>">
    <form action="<?= $APPLICATION->GetCurDir(); ?>"
          method="post"
          enctype="multipart/form-data"
          name="<?= $arResult['WEB_FORM_NAME']; ?>"
          data-form-validation
          class="b-form b-form--small">
        <?php echo bitrix_sessid_post(); ?>

        <input type="hidden" name="WEB_FORM_CODE" value="<?php echo $arParams['WEB_FORM_CODE']; ?>">

        <?php if ('Y' === $arParams['SHOW_FORM_TITLE']) { ?>
            <div class="title animated" data-animation="fadeInUp">
                <span><?php echo $arResult['arForm']['NAME']; ?></span>
            </div>
        <?php } ?>

        <?php if (!empty($arResult['SUCCESS'])) { ?>
            <div class="b-form__item success">
                <span class="b-form__text">
                    Ваша заявка отправлена. В ближайшее время наш менеджер свяжется с Вами.
                </span>
            </div>
        <?php } ?>

        <?php
        foreach ($arResult['QUESTIONS'] as $name => $field) {
            if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                echo $field['HTML_CODE'];
            } else { ?>
                <div class="b-form__item animated <?= ($field['STRUCTURE'][0]['FIELD_TYPE']=='textarea')?'b-form__item--textarea small--textarea':''; ?>" data-f-item  data-animation="fadeInUp">
                    <span class="b-form__label" data-f-label>
                        <?= $field['CAPTION']; ?>&nbsp;
                        <?= ($field['REQUIRED'] === 'Y' ? '*' : ''); ?>
                    </span>
                    <?php
                    echo $field['HTML_CODE'];

                    if ($field['REQUIRED'] == 'Y') { ?>
                        <span class="b-form__text alert alert--error hidden" data-form-error>
                            <?= Loc::getMessage('ERROR_TEXT'); ?>
                        </span>
                    <?php } ?>
                </div>
            <?php }
        } ?>

        <div class="b-form__bottom animated" data-animation="fadeInUp">
            <div class="b-form__pp">
                Нажимая на кнопку, я подтверждаю свое согласие на
                <a href="/policy/" rel="noopener noreferrer" target="_blank">«Политику в отношении обработки персональных данных»</a>
            </div>
            <button type="submit" class="btn btn--transparent btn--big">
                <?= $arResult['arForm']['BUTTON']; ?>
            </button>
        </div>

    </form>

</div>

<?php $frame->end();
