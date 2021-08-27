<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

// Сообщение после отправки формы
$strMessage = 'Вы успешно зарегистрировались на нашем сайте.';
/*if ((int)$arParams['SUCCESS_MESSAGE_IBLOCK_ID'] > 0 && $arParams['SUCCESS_MESSAGE_CODE'] != ''){
    $arOrder = array();
    $arFilter = array('IBLOCK_ID' => (int)$arParams['SUCCESS_MESSAGE_IBLOCK_ID'], 'CODE' => $arParams['SUCCESS_MESSAGE_CODE'], 'ACTIVE' => 'Y');
    $arSelectFields = array("ID", "NAME", "PROPERTY_DESC");
    $rsElements = CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
    if($arElement = $rsElements->GetNext())
    {
        $strMessage = $arElement['~PROPERTY_DESC_VALUE']['TEXT'];
    }
}*/
$arResult['SUCCESS_MESSAGE'] = $strMessage;