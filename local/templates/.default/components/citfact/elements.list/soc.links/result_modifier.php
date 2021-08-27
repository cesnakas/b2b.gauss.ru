<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 * @global CUser $USER
 * @global CMain $APPLICATION
 */

foreach ($arResult['ITEMS'] as $key =>  $item) {
    if ($item['PROPERTY_SVG_VALUE']) {
        $path = CFile::GetPath($item['PROPERTY_SVG_VALUE']);
        $arResult['ITEMS'][$key]['SVG_CONTENT'] = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $path);
    }
}
