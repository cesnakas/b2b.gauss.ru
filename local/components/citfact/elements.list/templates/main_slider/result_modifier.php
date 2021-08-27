<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$right = $three = $simple = $tmp_three = [];
foreach ($arResult['ITEMS'] as $key => $banner) {
    switch ($banner["PROPERTY_FORMAT_VALUE_XML_ID"]) {
        case 'THREE' :
            $banner['IMAGE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($banner['PREVIEW_PICTURE'], 365, 365);
            if(!empty($banner["PROPERTY_PICT_GIF_VALUE"]))
            {
                $banner['GIF'] = CFile::GetPath( $banner['PROPERTY_PICT_GIF_VALUE']);
            }
            if(!empty($banner["PROPERTY_VIDEO_FILE_VALUE"]))
            {
                $arVID['MY_VIDEO_SRC'] = CFile::GetPath($banner['PROPERTY_VIDEO_FILE_VALUE']);
                $banner['VIDEO'] = $arVID['MY_VIDEO_SRC'];
            }
            $tmp_three[] = $banner;
            if (count($tmp_three) == 3) {
                $simple[] = ['TYPE' => 'THREE', 'BANNERS' => $tmp_three];
                $tmp_three = [];
            }
            break;
        case 'ONE' :
        case 'ALL_SPACE' :
            $banner['IMAGE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($banner['PREVIEW_PICTURE'], 1120, 620);
            if(!empty($banner["PROPERTY_PICT_GIF_VALUE"]))
            {
                $banner['GIF'] = CFile::GetPath( $banner['PROPERTY_PICT_GIF_VALUE']);
            }
        if(!empty($banner["PROPERTY_VIDEO_FILE_VALUE"]))
        {
            $arVID['MY_VIDEO_SRC'] = CFile::GetPath($banner['PROPERTY_VIDEO_FILE_VALUE']);
            $banner['VIDEO'] = $arVID['MY_VIDEO_SRC'];
        }
            $simple[] = $banner;
            break;
        case 'RIGHT' :
            $banner['IMAGE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($banner['PREVIEW_PICTURE'], 620, 620);
            if(!empty($banner["PROPERTY_PICT_GIF_VALUE"]))
            {
                $banner['GIF'] = CFile::GetPath( $banner['PROPERTY_PICT_GIF_VALUE']);
            }
            if(!empty($banner["PROPERTY_VIDEO_FILE_VALUE"]))
            {
                $arVID['MY_VIDEO_SRC'] = CFile::GetPath($banner['PROPERTY_VIDEO_FILE_VALUE']);
                $banner['VIDEO'] = $arVID['MY_VIDEO_SRC'];
            }
            $right[] = $banner;
            break;
    }
}

$arResult['SIMPLE'] = $simple;
$arResult['RIGHT'] = $right;
unset($arResult['ITEMS']);
unset($arResult['MENU_ITEMS']);
unset($arResult['ITEM_IDS']);

