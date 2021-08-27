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
<div class="b-form__field <?= $arResult['CLASS_WRAP']; ?>">
    <div class="b-input" data-wrap-input>
        <input type="<?=($arResult['INPUT_TYPE']) ? $arResult['INPUT_TYPE'] : 'text'?>"
               id="<?= $id; ?>"
               class="b-input__input"
               name="<?= $arResult['NAME']; ?>"
            <?= $arResult['ATTRIBUTES']; ?>
               value="<?= ($arResult['NAME_STOCK']) ? $arResult['NAME_STOCK'] : $arResult['VALUE']; ?>">
        <label for="<?= $id; ?>" class="b-input__label"><?= $arResult['PLACEHOLDER'] ?></label>
        <div class="b-input__errortext" data-error></div>
    </div>
</div>
