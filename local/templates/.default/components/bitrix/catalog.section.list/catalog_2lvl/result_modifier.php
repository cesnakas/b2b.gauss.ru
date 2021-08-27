<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\CatalogHelper\ItemCounter;
use Citfact\SiteCore\Core;

$core = \Citfact\SiteCore\Core::getInstance();

// Убираем подразделы, ставим заглушку фото
foreach ($arResult["SECTIONS"] as $key => &$arSection) {
    if ($arResult["SECTIONS"][$key]['DEPTH_LEVEL'] > $arResult['SECTION']['DEPTH_LEVEL'] + 1) {
        unset($arResult['SECTIONS'][$key]);
        continue;
    }

    if (!is_null($arResult['SECTIONS'][$key]['PICTURE'])) {
        $file = \CFile::resizeImageGet($arResult['SECTIONS'][$key]['PICTURE'], ['width' => 284, 'height' => 284], BX_RESIZE_IMAGE_PROPORTIONAL);
        $arResult['SECTIONS'][$key]['PICTURE'] = [
            'ID' => $arResult['SECTIONS'][$key]['PICTURE']['ID'],
            'SRC' => $file['src'],
            'DESCRIPTION' => ($arResult['SECTIONS'][$key]['PICTURE']['DESCRIPTION']) ? $arResult['SECTIONS'][$key]['PICTURE']['DESCRIPTION'] : $arResult['SECTIONS'][$key]['NAME'],
        ];
    } else {
        $arResult['SECTIONS'][$key]['PICTURE'] = [
            'SRC' => Core::NO_PHOTO_SRC
        ];
    }
}

$arResSection = CIBlockSection::GetList([], [
    'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'],
    'ID' => $arResult['SECTION']['ID'],
], false, [
    'UF_*'
])->Fetch();
foreach ($arResSection as $code => $item) {
    if (strpos($code, 'UF_') === 0) {
        $arResult['SECTION'][$code] = $item;
    }
}

$pathBanners = [];
foreach ($arResult['SECTION']['UF_BANNER'] as $idFile) {
    $pathBanners[] = CFile::ResizeImageGet($idFile, array('width' => 1770, 'height' => round(1770 / 100 * 30)), BX_RESIZE_IMAGE_EXACT, true);
    break;
}
$arResult['BANNERS'] = $pathBanners;