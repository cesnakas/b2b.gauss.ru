<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as $key => &$item) {
    $item['PREVIEW_PICTURE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($item['PREVIEW_PICTURE']['ID'], 280, 280);
}