<? use Citfact\SiteCore\Core;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

?>
<?$APPLICATION->IncludeComponent(
    "bitrix:breadcrumb",
    "top_catalog",
    Array(
        "PATH" => "",
        "SITE_ID" => "s1",
        "START_FROM" => "0",
    ),
    false
);?>
<h1 class="title"><?php $APPLICATION->ShowTitle(true); ?></h1>
<?php

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "catalog_1lvl",
    array(
        "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
        "HIDE_EMPTY" => "Y",
        "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
        "SECTIONS_COUNT" => 6, // TODO что за параметр?
        "SUBSECTIONS_COUNT" => 6, // TODO что за параметр?
        "COMPONENT_TEMPLATE" => $arParams["COMPONENT_TEMPLATE"],
        "SECTION_ID" => "",
        "SECTION_CODE" => "",
        "SECTION_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "SECTION_USER_FIELDS" => array(
            0 => "",
            1 => "",
        )
    ),
    $component,
    array(
        "HIDE_ICONS" => "N"
    )
);
?>
<? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/sellout/banner.php"; ?>