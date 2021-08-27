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
<div class="b-form__item b-form__item--select 111" data-f-item data-select-wrap>
    <span class="b-form__label" data-f-label><?= $arResult['PLACEHOLDER'] ?></span>

    <select class=""
            id="<?= $id; ?>"
            name="<?= $arResult['NAME']; ?>"
        <?= $arResult['ATTRIBUTES']; ?>
            data-f-field
            data-select-reg>
        <option class="empty"></option>
        <?
        if (isset($arResult['UF_REGIONS'])) {
            foreach ($arResult['UF_REGIONS'] as $region) {
                echo '<option value="' . $region['ID'] . '">' . $region['UF_NAME'] . '</option>';
            }
        } elseif (isset($arResult['UF_FORM_ORGANIZATION'])) {
            foreach ($arResult['UF_FORM_ORGANIZATION'] as $key => $region) {
                echo '<option value="' . $key . '">' . $region . '</option>';
            }
        }
        ?>
    </select>
    <input class="b-form__input" data-select-sort/>
    <span class="b-form__text"></span>
</div>
