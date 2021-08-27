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
<div class="b-form__item <?= $arResult['HIDDEN'] === 'Y' ? 'hidden' : ''?>" data-f-item>
    <span class="b-form__label" data-f-label><?= $arResult['PLACEHOLDER'] ?></span>
    <input type="password"
           id="<?= $id; ?>"
           name="<?= $arResult['NAME']; ?>"
           maxlength="50"
           autocomplete="new-password"
           value=""
           data-f-field
        <?= $arResult['ATTRIBUTES']; ?>>
    <span class="b-form__text" data-form-error>

    </span>
</div>