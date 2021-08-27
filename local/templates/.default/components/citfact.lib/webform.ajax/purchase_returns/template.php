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

?>
<input type="hidden" name="<?= $arParams['WEB_FORM_CODE']; ?>" value="Y">
<input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">
<input type="hidden" name="COMPONENT_TEMPLATE" value="<?= $arParams['COMPONENT_TEMPLATE']; ?>">
<input type="hidden" name="PAGE_TITLE" value="<?= $arParams['TITLE'] ?: $APPLICATION->GetPageProperty('title'); ?>">

<div class="lk-return__form"
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
            </div>
        <? else: ?>
            <div class="title-1"><span>Заявка на возврат/обмен товара</span></div>
            <? foreach ($arResult['QUESTIONS'] as $name => $field) { ?>
                <? if (stripos($name, 'FILE') !== false) {
                    continue;
                } ?>
                <? if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') { ?>
                    <?= $field['HTML_CODE'] ?>
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
                        <?
                        if ($field['REQUIRED'] == 'Y') { ?>
                            <span class="b-form__text alert alert--error hidden" data-form-error>
                                Некорректно заполнено поле
                            </span>
                        <? } ?>
                    </div>

                    <?
                }
                unset($arResult['QUESTIONS'][$name]);
            } ?>
            <div class="b-form__file" data-file-field>
            <span class="b-form__label">
                <span>Прикрепите заполненный файл претензии на возврат.</span>
            </span>
                <?$i=0;
                foreach ($arResult['QUESTIONS'] as $name => $field) {
                    if ($i<1 && $name !== $arParams['HIDDEN_FIELD']) { ?>
                        <? echo $field['HTML_CODE']; ?>
                    <? } elseif($i<1) { ?>
                        <? echo $field['HTML_CODE']; ?>
                    <? }
                $i++;} ?>
                <span class="b-form__format">Формат документов: XLS, DOC, PDF, JPG, PNG, BMP</span>
                <div data-field-errors></div>
            </div>
            <div class="b-form__bottom">
                <button class="btn btn--transparent btn--big" type="submit"
                        data-agree-submit="WEB_FORM_AJAX"><?= $arResult['arForm']['BUTTON']; ?></button>
            </div>
        <? endif; ?>
    </form>
</div>