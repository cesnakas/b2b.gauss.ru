<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Citfact\SiteCore\CatalogHelper\ItemCounter;
use Citfact\SiteCore\Core;

$core = \Citfact\SiteCore\Core::getInstance();

if ($arParams["TOP_DEPTH"] > 1) {
    $arSections = [];
    $arSectionsDepth3 = [];
    foreach ($arResult["SECTIONS"] as $key => $arItem) {
        if ($arItem['PICTURE']) {
            $file = \CFile::resizeImageGet($arItem['PICTURE'],['width' =>250, 'height' =>175], BX_RESIZE_IMAGE_PROPORTIONAL);
            $arItem['PICTURE'] = [
                'ID' => $arItem['PICTURE']['ID'],
                'SRC' => $file['src'],
                'DESCRIPTION' => ($arItem['PICTURE']['DESCRIPTION']) ? $arItem['PICTURE']['DESCRIPTION'] : $arItem['NAME'],
            ];
        } else {
            $arItem['PICTURE'] = [
                'SRC' => Core::NO_PHOTO_SRC
            ];
        }
        if ($arItem["DEPTH_LEVEL"] == 1) {
            $arSections[$arItem["ID"]] = $arItem;
        } elseif ($arItem["DEPTH_LEVEL"] == 2) {
            $arSections[$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
        } elseif ($arItem["DEPTH_LEVEL"] == 3) {
            $arSectionsDepth3[] = $arItem;
        }
    }
    if ($arSectionsDepth3) {
        foreach ($arSectionsDepth3 as $arItem) {
            foreach ($arSections as $key => $arSection) {
                if (is_array($arSection["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]) && !empty($arSection["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]])) {
                    $arSections[$key]["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
                }
            }
        }
    }
    $arResult["SECTIONS"] = $arSections;
}

$countSection = 1;

foreach ($arResult['SECTIONS'] as $key => $arSection){
    if($countSection > $arParams['SECTIONS_COUNT']){
        unset($arResult['SECTIONS'][$key]);
        continue;
    }

    $countSubSection = 1;

    foreach ($arSection['SECTIONS'] as $subKey => $arSubSection){
        if($countSubSection > $arParams['SUBSECTIONS_COUNT']){
            unset($arResult['SECTIONS'][$key]['SECTIONS'][$subKey]);
        }

        $countSubSection++;
    }
    $countSection++;
}