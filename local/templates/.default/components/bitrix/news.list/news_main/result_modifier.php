<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach($arResult['ITEMS'] as $key => &$item){

    // формирование формата даты
    $date = FormatDate('f, Y', MakeTimeStamp($item['DISPLAY_PROPERTIES']['DATE']['VALUE'], 'DD.MM.YYYY'));
    $item['DISPLAY_PROPERTIES']['DATE']['VALUE'] = $date;

    // ресайз изображений
    $item['PREVIEW_PICTURE'] = getResizePictures($item['PREVIEW_PICTURE']['ID'], 1340, 400, 335, 100);
}