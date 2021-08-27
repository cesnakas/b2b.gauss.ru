<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['DETAIL_PICTURE'] = getResizePictures($arResult['DETAIL_PICTURE']['ID'], 405, 405, 81, 81);
