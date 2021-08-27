<?
use Citfact\SiteCore\Core;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$core = Core::getInstance();

if (!empty($arParams['ITEM']['DETAIL_PICTURE']['ID'])) {
    $arParams['ITEM']['DETAIL_PICTURE'] = getResizePictures($arParams['ITEM']['DETAIL_PICTURE']['ID'], 215, 215, 43, 43);
} else {
    $arParams['ITEM']['DETAIL_PICTURE']['SRC'] = [
        'PREVIEW' => $core::NO_PHOTO_SRC,
        'ORIGIN' => $core::NO_PHOTO_SRC,
    ];
}
