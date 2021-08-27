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
    include($arResult["FILE"]);
    $output = ob_get_contents();
    ob_end_clean();

    $regex = '/src="(.*)"/mU';

    if (!empty($output)) {
        preg_match($regex, $output, $matches);

        $src = $matches[1];
        ?>

        <img src="<?php echo 'Y' === $arParams['IMAGE_PLACEHOLDER'] ? IMAGE_PLACEHOLDER : $src; ?>"
             data-src="<?php echo $src; ?>"
             data-src-small=""
             alt="<?php echo $arParams['ALT']; ?>"
             title="<?php echo $arParams['TITLE']; ?>"
             class="<?php echo $arParams['CLASS']; ?>">
    <?php } else { ?>
        <img src="<?php echo IMAGE_PLACEHOLDER; ?>"
             alt="<?php echo $arParams['ALT']; ?>"
             title="<?php echo $arParams['TITLE']; ?>">
    <?php }
}