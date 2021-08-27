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
$id = $arResult['ID'] ? $arResult['ID'] : $this->randString() . $arResult['NAME'];
?>
<div class="b-form__item <?= $arResult['HIDDEN'] === 'Y' ? 'hidden' : ''?>" data-f-item>
    <span class="b-form__label" data-f-label><?= $arResult['PLACEHOLDER'] ?></span>
    <input type="<?= ($arResult['INPUT_TYPE']) ? $arResult['INPUT_TYPE'] : 'text' ?>"
           id="<?= $id; ?>"
           name="<?= $arResult['NAME']; ?>"
           data-f-field
        <?= $arResult['ATTRIBUTES']; ?>
           autocomplete="new-password"
           value="<?= ($arResult['NAME_STOCK']) ? $arResult['NAME_STOCK'] : $arResult['VALUE']; ?>">
    <span class="b-form__text" data-form-error>

    </span>
</div>