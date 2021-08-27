<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
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

$this->setFrameMode(true);
$id = $this->randString() . $arResult['NAME'];
?>
<div class="b-form__item b-form__item--select" data-f-item>
    <span class="b-form__label" data-f-label><?= $arResult['PLACEHOLDER'] ?></span>

    <select class=""
            id="<?= $id; ?>"
            name="<?= $arResult['NAME']; ?>"
        <?= $arResult['ATTRIBUTES']; ?>
            data-f-field
            data-select-reg>
        <option></option>
        <?
        if (isset($arResult['UF_REGIONS'])) {
            foreach ($arResult['UF_REGIONS'] as $region) {
                echo '<option value="' . $region['ID'] . '">' . $region['UF_NAME'] . '</option>';
            }
        } elseif (isset($arResult['UF_FORM_ORGANIZATION'])) {
            foreach ($arResult['UF_FORM_ORGANIZATION'] as $region) {
                echo '<option value="' . $region['ID'] . '">' . $region['UF_NAME'] . '</option>';
            }

            //тестовый вывод для UF_FORM_ORGANIZATION
            echo '<option value="1">Вариант 1</option>';
            echo '<option value="2">Вариант 2</option>';
            echo '<option value="3">Вариант 3</option>';
        }
        ?>
    </select>

    <span class="b-form__text"></span>
</div>