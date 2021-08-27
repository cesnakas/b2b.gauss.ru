<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
    <input type="hidden" name="<?= $arParams['WEB_FORM_CODE']; ?>" value="Y">
    <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">
    <input type="hidden" name="COMPONENT_TEMPLATE" value="<?= $arParams['COMPONENT_TEMPLATE']; ?>">
    <input type="hidden" name="PAGE_TITLE" value="<?= $arParams['TITLE'] ?: $APPLICATION->GetPageProperty('title'); ?>">
<div class="lk__section lk-shortage-form"
     data-checkbox-agree-form
     data-form-submit="<?= $arResult['WEB_FORM_NAME']; ?>"
     data-form-submit-url="<?= $APPLICATION->GetCurDir(); ?>">
    <form class="b-form"
          action="<?= $APPLICATION->GetCurDir(); ?>"
          method="post"
          enctype="multipart/form-data"
          name="<?= $arResult['WEB_FORM_NAME']; ?>">

        <?= bitrix_sessid_post(); ?>
        <input type="hidden" name="WEB_FORM_CODE" value="<? echo $arParams['WEB_FORM_CODE']; ?>">

        <? if (!empty($arResult['SUCCESS'])): ?>
            <div>
                <?= $arResult['SUCCESS_MESSAGE'] ? str_replace('#id#', $arResult['ID_RESULT'], $arResult['SUCCESS_MESSAGE']) :
                    'Ваша заявка отправлена. В ближайшее время наш менеджер свяжется с Вами.'?>
                <span>Посмотреть статус заявки Вы сможете на <a class="link" href="/personal/shortage-list/">этой странице</a>.</span>
            </div>
        <? else: ?>
            <?foreach ($arResult['QUESTIONS'] as $name => $field){?>
                <? if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') { ?>
                <?= $field['HTML_CODE'] ?>
             <? } else { ?>
            <div class=" <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'file' ? 'b-form__file' : 'b-form__item' ?>
                    <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'dropdown' ? 'b-form__item--select' :
                ($field['STRUCTURE']['0']['FIELD_TYPE'] === 'textarea' ? 'b-form__item--textarea' : ''); ?>"
                    <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'file' ? 'data-file-field' :
                        ($field['STRUCTURE']['0']['FIELD_TYPE'] === 'date' ? 'data-f-item data-datepicker' : 'data-f-item'); ?> data-f-item="">
                <span class="b-form__label active" data-f-label>
                    <?= $field['CAPTION']; ?>&nbsp;
                    <?= ($field['REQUIRED'] === 'Y' ? '*' : ''); ?>
                </span>
                    <?= $field['HTML_CODE']; ?>
                    <?
                    if ($field['REQUIRED'] == 'Y' && $field['STRUCTURE']['0']['FIELD_TYPE'] !== 'file') { ?>
                        <span class="b-form__text alert alert--error hidden" data-form-error>
                                Некорректно заполнено поле
                        </span>
                    <? } ?>

            </div>

            <? }
        } ?>
            <div class="b-form__bottom">
                <button class="btn btn--transparent btn--big" type="submit"
                        data-agree-submit="WEB_FORM_AJAX"><?= $arResult['arForm']['BUTTON']; ?></button>
            </div>
        <?endif;?>
    </form>
</div>


