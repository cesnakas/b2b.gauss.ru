<?

use Citfact\Sitecore\Video;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as $key => &$item) {

    $item['PREVIEW_PICTURE'] = getResizePictures($item['PREVIEW_PICTURE']['ID'], 280, 200, 70, 50);

    $item['DISPLAY_PROPERTIES']['LINK']['VALUE'] = Video::getFrameUrlByShareUrl($item['DISPLAY_PROPERTIES']['LINK']['VALUE']);
}