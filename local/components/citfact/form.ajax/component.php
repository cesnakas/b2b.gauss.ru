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

Loc::loadMessages(__FILE__);
Loader::includeModule('iblock');

$app = Application::getInstance();
$request = $app->getContext()->getRequest();

//CJSCore::Init();
if ($arParams['IBLOCK_ID'] != '' && !empty($arParams['SHOW_PROPERTIES']) && $this->StartResultCache()) {
	$IBLOCK_ID = (int)$arParams['IBLOCK_ID'];

	$arPropCodes = array_keys($arParams['SHOW_PROPERTIES']);

	$properties = CIBlockProperty::GetList(Array('SORT' => 'ASC'), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID));
	$arResult['SHOW_PROPERTIES'] = array();
	while ($arResProp = $properties->GetNext()){
		if (in_array($arResProp['CODE'], $arPropCodes)){
		    $arParamsProp = $arParams['SHOW_PROPERTIES'][$arResProp['CODE']];

			$arResProp['PARAMS_TYPE'] = $arParamsProp['type'];
			$arResProp['PLACEHOLDER'] = $arParamsProp['placeholder'];
			$arResProp['REQUIRED'] = $arParamsProp['required'];
			$arResProp['VALUE'] = $arParamsProp['value'];
			$arResProp['CLASS'] = $arParamsProp['class'];
			$arResProp['ERROR'] = $arParamsProp['error'];
			$arResProp['ID'] = $arParamsProp['id'];
			$arResProp['NAME'] = $arParamsProp['name'];

			$arResult['SHOW_PROPERTIES'][] = $arResProp;
		}
	}

	$arResult['PARAMS_HASH'] = md5(serialize($arParams));

    // Cancel cache data
    /*if ($arParams['ID'] < 10) {
        $this->AbortResultCache();
    }*/

    $this->IncludeComponentTemplate();
}

