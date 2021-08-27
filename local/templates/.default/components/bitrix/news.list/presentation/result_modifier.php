<?

use \Citfact\SiteCore\Video;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as $key => &$item) {

    $item['PREVIEW_PICTURE'] = getResizePictures($item['PREVIEW_PICTURE']['ID'], 1340, 400, 335, 100);

    $item['DISPLAY_PROPERTIES']['LINK']['VALUE'] = Video::getFrameUrlByShareUrl($item['DISPLAY_PROPERTIES']['LINK']['VALUE']);
}