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
    $output = str_replace(["-", ":", "/", "\\",], "", $origin);
    $tel = str_replace(["-"," ","(", ")", ":", "/", "\\",], "", $output);
    ?>
    <a <?=$arParams['SCHEMA_ORG']=='Y'?'itemprop="email"':''; ?> href="mailto:<?= $tel; ?>" class="<?php echo $arParams['CLASS']; ?>">
        <?php echo $origin; ?>
    </a>
<? }
