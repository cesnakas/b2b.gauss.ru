<?

use Citfact\SiteCore\Core;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Убираем подразделы, ставим заглушку фото
foreach ($arResult["SECTIONS"] as $key => $arSection) {
    if ($arSection['DEPTH_LEVEL'] > $arResult['SECTION']['DEPTH_LEVEL'] + 1){
        unset($arResult['SECTIONS'][$key]);
        continue;
    }

    if ($arSection['PICTURE'] == ''){
        $arResult['SECTIONS'][$key]['PICTURE']['SRC'] = Core::NO_PHOTO_SRC;
    }
}