<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if ($arResult["FILE"] <> '') {
    ob_start();
    include $arResult["FILE"];
    $origin = ob_get_contents();
    ob_end_clean();
    $link = $origin;
    $targetBlank = false;
    if (strpos($origin, '/') !== 0 && strpos($origin, 'http') !== 0) {
        $link = 'http://' . $origin;
        $targetBlank = true;
    }
    if ($arParams['TARGET'] == 'Y') {
        $targetBlank = true;
    }
    ?>
    <a href="<?= $link; ?>" class="<?php echo $arParams['CLASS']; ?>" <?= ($targetBlank)?'target="_blank"':''; ?>>
        <?php echo $origin; ?>
    </a>
<? }
