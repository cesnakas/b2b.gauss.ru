<?

use Citfact\Sitecore\Video;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as $key => &$item){

    $item['PREVIEW_PICTURE'] = getResizePictures($item['PREVIEW_PICTURE']['ID'], 1340, 400, 335, 100);
    

    $arResult['DEADLINE'] = $item['PROPERTIES']['DATE']['VALUE'];
}

$date = new DateTime($arResult['DEADLINE']);
$arResult['DEADLINE'] = $date->format('F d Y H:i:s') . ' GMT+0300';