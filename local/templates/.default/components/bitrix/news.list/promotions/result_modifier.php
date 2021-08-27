<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as $key => &$item){

    $item['PREVIEW_PICTURE'] = getResizePictures($item['PREVIEW_PICTURE']['ID'], 265, 200, 53, 40);
}