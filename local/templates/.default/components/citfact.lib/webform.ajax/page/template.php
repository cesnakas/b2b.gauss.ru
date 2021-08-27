<?php

use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;

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

$dir = $APPLICATION->GetCurDir();
$core = Core::getInstance();
?>

<input type="hidden" name="<?= $arParams['WEB_FORM_CODE']; ?>" value="Y">
<input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">
<input type="hidden" name="COMPONENT_TEMPLATE" value="<?= $arParams['COMPONENT_TEMPLATE']; ?>">
<input type="hidden" name="PAGE_TITLE" value="<?= $arParams['TITLE'] ?: $APPLICATION->GetPageProperty('title'); ?>">

<div data-form-submit="<?= $arResult['WEB_FORM_NAME']; ?>" data-form-submit-url="<?= $APPLICATION->GetCurDir(); ?>">
    <form class="b-form"
          action="<?= $APPLICATION->GetCurDir(); ?>"
          method="post"
          enctype="multipart/form-data"
          name="<?= $arResult['WEB_FORM_NAME']; ?>">


        <?= bitrix_sessid_post(); ?>
        <input type="hidden" name="CTRLVFILE" value="">
        <input type="hidden" name="WEB_FORM_CODE" value="<?php echo $arParams['WEB_FORM_CODE']; ?>">

        <? if (!empty($arResult['SUCCESS'])): ?>
            <div class="green">
                <?= $arResult['SUCCESS_MESSAGE'] ?: 'Ваша заявка отправлена. В ближайшее время наш менеджер свяжется с Вами.'?>
            </div>
        <? else: $countFile = 0; ?>
            <? if (stripos($dir, 'feedback') !== false || stripos($dir, 'documents') !== false): ?>
                <p>
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "",
                        [
                            "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                            "PATH" => '/local/include/areas' . $dir . 'title.php',    // Путь к файлу области
                        ],
                        false
                    ); ?>
                </p>
            <? endif; ?>
            <? foreach ($arResult['QUESTIONS'] as $name => $field) { ?>
                <? if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') { ?>
                    <?= $field['HTML_CODE'] ?>
                <? } elseif ($field['STRUCTURE'][0]['FIELD_TYPE'] !== 'file' || $countFile < 1) { ?>

                    <div class="<?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'file' ? 'b-form__file' : 'b-form__item ' ?>
                    <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'dropdown' ? 'b-form__item--select' :
                            ($field['STRUCTURE']['0']['FIELD_TYPE'] === 'textarea' ? 'b-form__item--textarea' : ''); ?>"
                        <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'file' ? 'data-file-field' :
                            ($field['STRUCTURE']['0']['FIELD_TYPE'] === 'date' ? 'data-f-item data-datepicker' : 'data-f-item'); ?>>
                        <? if ($field['STRUCTURE']['0']['FIELD_TYPE'] !== 'radio'): ?>
                            <span class="b-form__label" data-f-label>
                            <? if(in_array($arParams['WEB_FORM_ID'], $core::WEB_FORM_ID) && $field['VARNAME'] !== 'TEXT') { ?>
                                Прикрепите файл с устройства или вставьте изображение из буфера обмена
                            <? } else { ?>
                                <?= $field['CAPTION']; ?>
                            <? } ?>
                            <?= $field['REQUIRED'] === 'Y' && $field['STRUCTURE']['0']['FIELD_TYPE'] !== 'file' ? '*' : ''; ?>
                        </span>
                        <?endif; ?>
                        <?= $field['HTML_CODE']; ?>
                        <?
                        if ($field['REQUIRED'] == 'Y' && $field['STRUCTURE']['0']['FIELD_TYPE'] === 'file') { ?>
                            <span class="b-form__format">Формат документов: XLS, DOC, PDF, JPG, PNG, BMP</span>
                            <div data-field-errors></div>
                        <? } elseif ($field['REQUIRED'] == 'Y') { ?>
                            <span class="b-form__text alert alert--error hidden" data-form-error>

                            </span>
                        <? } ?>

                    </div>

                    <?
                }
                if ($field['STRUCTURE'][0]['FIELD_TYPE'] === 'file') {
                    $countFile++;
                }
            } ?>
            <div class="b-form__bottom">
                <button class="btn btn--transparent btn--big" type="submit"
                        data-agree-submit="WEB_FORM_AJAX"><?= $arResult['arForm']['BUTTON']; ?></button>
            </div>
        <? endif; ?>

    </form>
</div>
