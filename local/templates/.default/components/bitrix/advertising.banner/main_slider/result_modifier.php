<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['BANNERS'] as $key => $banner){

    preg_match('/href=["\']?([^"\'>]+)["\']?/', $banner, $hrefs);
    preg_match('/src=["\']?([^"\'>]+)["\']?/', $banner, $srcs);
    $href = $hrefs[1];
    $src = $srcs[1];

    $arResult['BANNERS_PROPERTIES'][$key]['HREF'] = $href;
    $arResult['BANNERS_PROPERTIES'][$key]['SRC'] = $src;
    unset($arResult['BANNERS'][$key]);
}

foreach ($arResult['BANNERS_PROPERTIES'] as $key => &$bannerProp){

    //получение размеров изображения из b_files
    $arFilter = ["MODULE_ID"=>"advertising", "@ID" => $bannerProp['IMAGE_ID']];
    $res = CFile::GetList(["FILE_SIZE"=>"desc"], $arFilter);

    while($res_arr = $res->GetNext()) {
        $width = $res_arr['WIDTH'];
        $height = $res_arr['HEIGHT'];
    }

    $bannerProp['IMAGE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($bannerProp['IMAGE_ID'], 620, 620);

    $arResult['BANNERS'][$bannerProp['GROUP_SID']][$bannerProp['WEIGHT']] = $bannerProp;
}

ksort($arResult['BANNERS']);
