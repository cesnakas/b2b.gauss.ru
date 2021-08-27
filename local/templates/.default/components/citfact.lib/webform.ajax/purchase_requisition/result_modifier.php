<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Citfact\SiteCore\Core;

$core = Core::getInstance();

// id элементов из POST-запроса
$postData = Application::getInstance()->getContext()->getRequest()->getPostList()->toArray();

$iBlockCode = $arParams['WEB_FORM_CODE'] == 'SIMPLE_FORM_18' ? $core::IBLOCK_CODE_MARKETING_SUPPORT_TRADING_EQUIPMENT_POS_MATERIALS :
    $core::IBLOCK_CODE_MARKETING_SUPPORT_SOUVENIRS;

// элементы из инфоблока
if ($postData['ids']) {
    $arOrder = array("SORT" => "ASC");
    $arFilter = array('IBLOCK_ID' => $core->getIblockId($iBlockCode),
        'ID' => $postData['ids'],
        'ACTIVE' => 'Y');
    $arSelectFields = array("ID", "NAME");

    $rsElements = CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
    $arFoundedIds = array();

    while ($arElement = $rsElements->Fetch()) {
        $arFoundedIds[] = $arElement;
    }

    $arResult['PRODS'] = $arFoundedIds;
}