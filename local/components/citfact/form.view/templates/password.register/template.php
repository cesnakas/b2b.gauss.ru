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
<div class="b-form__field">
    <div class="b-input" data-wrap-input>
        <input type="password"
               id="<?= $id; ?>"
               class="b-input__input"
               name="<?= $arResult['NAME']; ?>"
            <?= $arResult['ATTRIBUTES']; ?>
               value="">
        <label for="<?= $id; ?>" class="b-input__label"><?= $arResult['PLACEHOLDER'] ?></label>
    </div>
    <div class="b-form__subtext"> Пароль может содержать латинские строчные и заглавные буквы, а также цифры 0-9. </div>
</div>