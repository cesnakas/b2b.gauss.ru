<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach ($arResult['RESULTS'] as $key => $arRes)
{
		$arResult['RESULTS'][$key]['DATE_CREATE'] = $GLOBALS['DB']->FormatDate($arRes['DATE_CREATE'], CSite::GetDateFormat('FULL'), CSite::GetDateFormat('SHORT'));
}

?>