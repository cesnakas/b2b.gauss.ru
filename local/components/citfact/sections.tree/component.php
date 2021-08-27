<?php

/*
 * This file is part of the Studio Fact package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');

Loc::loadMessages(__FILE__);

$app = Application::getInstance();
$request = $app->getContext()->getRequest();
global $APPLICATION;


$iblock_id = (int)$arParams['IBLOCK_ID'];
if ($this->StartResultCache() && $iblock_id != '') {

    // Если задан код раздела и не задан ID, то достаем ID
    if ($arParams['SECTION_CODE'] != '' && $arParams['SECTION_ID'] == '') {
        $arSort = array();
        $arFilter = array("IBLOCK_ID" => $iblock_id, "CODE" => $arParams['SECTION_CODE']);
        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, array('ID'));
        if ($arSection = $rsSections->GetNext()) {
            $arParams['SECTION_ID'] = $arSection['ID'];
        }
    }

    if ($arParams['SECTION_ID']){
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $arParams['SECTION_ID']);
        $arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();
    }

    $arSections = array();

    $arSelect = array(
        'IBLOCK_ID',
        'ID',
        'NAME',
        'CODE',
        'DEPTH_LEVEL',
        'IBLOCK_SECTION_ID',
        'SECTION_PAGE_URL',
    );
    if (!empty($arParams['SELECT_FIELDS'])) {
        $arSelect = array_merge($arSelect, $arParams['SELECT_FIELDS']);
    }

    if (!$arParams['SECTION_ID']) {
        $arFilter = array(
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblock_id,
            'GLOBAL_ACTIVE' => 'Y',
        );
        $arOrder = array('DEPTH_LEVEL' => 'ASC', 'SORT' => 'ASC');
        $rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
        while ($arSection = $rsSections->GetNext()) {
            $arSections[$arSection['ID']] = $arSection;
            $arSectionsIds[] = $arSection['ID'];
        }
    }
    else{
        //$rsParentSection = CIBlockSection::GetByID((int)$arParams['SECTION_ID']);
        $arSort = array();
        $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => (int)$arParams['SECTION_ID'] );
        $arSelect = array(
            'ID',
            'NAME',
            'CODE',
            'PICTURE',
            'DESCRIPTION',
            'IBLOCK_ID',
            'LEFT_MARGIN',
            'RIGHT_MARGIN',
            'DEPTH_LEVEL',
            'IBLOCK_SECTION_ID',
            'ACTIVE',
            'UF_H1',
            'UF_TITLE',
            'UF_KEYWORDS',
            'UF_DESCRIPTION',

            'UF_PDF',
            'UF_SERTIFICATIONS',
            'UF_PHOTOGALERY',
            'UF_VIDEO',
			'UF_PRICELIST',
        );
        $rsParentSection = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
        if ($arParentSection = $rsParentSection->GetNext())
        {
            $arResult['NAME'] = $arParentSection['NAME'];
            $arResult['UF_H1'] = $arParentSection['UF_H1'];
            $arResult['UF_TITLE'] = $arParentSection['UF_TITLE'];
            $arResult['UF_KEYWORDS'] = $arParentSection['UF_KEYWORDS'];
            $arResult['UF_DESCRIPTION'] = $arParentSection['UF_DESCRIPTION'];

            $arResult['UF_PDF'] = $arParentSection['UF_PDF'];
            $arResult['UF_SERTIFICATIONS'] = $arParentSection['UF_SERTIFICATIONS'];
            $arResult['UF_PHOTOGALERY'] = $arParentSection['UF_PHOTOGALERY'];
            $arResult['UF_VIDEO'] = $arParentSection['UF_VIDEO'];
            $arResult['UF_PRICELIST'] = $arParentSection['UF_PRICELIST'];

            $arFilter = array(
                'IBLOCK_ID' => $arParentSection['IBLOCK_ID'],
                '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
                '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
                '>=DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL'],
                'ACTIVE' => 'Y'
            );
            $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);
            while ($arSection = $rsSect->GetNext())
            {
                $arSections[$arSection['ID']] = $arSection;
                $arSectionsIds[] = $arSection['ID'];
            }
        }
    }

    // Количество элементов в разделах
    if ($arParams['GET_COUNT'] == 'Y') {
        $arFilter = Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], 'SECTION_ID'=>$arSectionsIds, "ACTIVE"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y");
        $arFilter = array_merge($arFilter, $arParams["FILTER_COUNT"]);

        $res = CIBlockElement::GetList(
            Array(),	//array arOrder
            $arFilter,	//array arFilter
            false,	// mixed arGroupBy
            false,	//mixed arNavStartParams
            Array('ID', 'IBLOCK_SECTION_ID')	//array arSelectFields
        );
        $arIds = array();
        while ($arRes = $res->Fetch()){
            $arIds[] = $arRes['ID'];
        }

        $res_groups = CIBlockElement::GetElementGroups($arIds, false, array('ID', 'IBLOCK_SECTION_ID'));
        while ($arResGroups = $res_groups->Fetch()){
            $arElementsCount[$arResGroups['ID']]++;
            $arElementsCount[$arResGroups['IBLOCK_SECTION_ID']]++;
        }
        $arResult["ELEMENTS_COUNT"] = $arElementsCount;

        if ($arParams['DELETE_EMPTY'] == 'Y') {
            foreach ($arSections as $arSection) {
                if (!isset($arElementsCount[$arSection['ID']]) && $arSection['DEPTH_LEVEL'] != 1) {
                    unset($arSections[$arSection['ID']]);
                }
            }
        }
    }


    // Наследуемые свойства
    if ($arParams["GET_IPROPERTY_VALUES"] === "Y") {
        foreach ($arSections as &$arSection) {
            $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection["IBLOCK_ID"], $arSection["ID"]);
            $arSection["IPROPERTY_VALUES"] = $ipropValues->getValues();
        }
        unset($arSection);
    }

    $arResult['SECTIONS_LIST'] = $arSections;


    // Строим дерево
    $sectionLinc = array();
    $arRoot['ROOT'] = array();

    reset($arResult['SECTIONS_LIST']);
    $current = current($arResult['SECTIONS_LIST']);
    $sectionLinc[intval($arResult['SECTIONS_LIST'][ $current['ID'] ]['IBLOCK_SECTION_ID'])] = &$arRoot['ROOT'];

    foreach($arSections as $arSection){
        if (!(strpos(strtolower($arSection['NAME']), 'распродажа') === false)) {
            $arSection['IS_SALE'] = true;
        }
        $sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['SECTIONS'][$arSection['ID']] = $arSection;
        $sectionLinc[$arSection['ID']] = &$sectionLinc [intval($arSection['IBLOCK_SECTION_ID'])] ['SECTIONS'] [$arSection['ID']];
    }
    unset($sectionLinc);

    $arResult['SECTIONS_TREE'] = $arRoot['ROOT']['SECTIONS'];

    $this->SetResultCacheKeys(array(
        "NAME",
        "UF_H1",
        "UF_TITLE",
        "UF_KEYWORDS",
        "UF_DESCRIPTION",
        "IPROPERTY_VALUES",
    ));

    $this->IncludeComponentTemplate();
}


if($arParams["SET_TITLE"])
{
    /*if ($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
        $APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arTitleOptions);
    elseif(isset($arResult["NAME"]))
        $APPLICATION->SetTitle($arResult["NAME"], $arTitleOptions);*/
    if(isset($arResult[$arParams['PAGE_TITLE']])) {
        //$APPLICATION->SetTitle($arResult[$arParams['PAGE_TITLE']], $arTitleOptions);
        $APPLICATION->AddViewContent('h1', $arResult[$arParams['PAGE_TITLE']]);
    }
    elseif ($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "") {
        //$APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arTitleOptions);
        $APPLICATION->AddViewContent('h1', $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]);
    }
}

if ($arParams["SET_BROWSER_TITLE"] === 'Y')
{
    $browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
        $arResult, $arParams["BROWSER_TITLE"]
        ,$arResult["IPROPERTY_VALUES"], "SECTION_META_TITLE"
    );
    if (is_array($browserTitle))
        $APPLICATION->SetPageProperty("title", implode(" ", $browserTitle), $arTitleOptions);
    elseif ($browserTitle != "")
        $APPLICATION->SetPageProperty("title", $browserTitle, $arTitleOptions);
}

if ($arParams["SET_META_KEYWORDS"] === 'Y')
{
    $metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
        $arResult, $arParams["META_KEYWORDS"]
        ,$arResult["IPROPERTY_VALUES"], "SECTION_META_KEYWORDS"
    );
    if (is_array($metaKeywords))
        $APPLICATION->SetPageProperty("keywords", implode(" ", $metaKeywords), $arTitleOptions);
    elseif ($metaKeywords != "")
        $APPLICATION->SetPageProperty("keywords", $metaKeywords, $arTitleOptions);
}

if ($arParams["SET_META_DESCRIPTION"] === 'Y')
{
    $metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
        $arResult, $arParams["META_DESCRIPTION"]
        ,$arResult["IPROPERTY_VALUES"], "SECTION_META_DESCRIPTION"
    );
    if (is_array($metaDescription))
        $APPLICATION->SetPageProperty("description", implode(" ", $metaDescription), $arTitleOptions);
    elseif ($metaDescription != "")
        $APPLICATION->SetPageProperty("description", $metaDescription, $arTitleOptions);
}