<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;

$core = Core::getInstance();

// Разбираем результат поиска на группы
foreach ($arResult['SEARCH'] as $key => $searchItem)
{
    switch ($searchItem['PARAM2'])
    {
        case $core->getIblockId(Core::IBLOCK_CODE_CATALOG):
            $arResult['SEARCH_SORTED']['CATALOG'][] = $searchItem;
            break;
        case $core->getIblockId(Core::IBLOCK_CODE_MANUFACTURES):
            $arResult['SEARCH_SORTED']['MANUFACTURERS'][] = $searchItem;
            break;
        case $core->getIblockId(Core::IBLOCK_CODE_BUYERS):
            $arResult['SEARCH_SORTED']['BUYERS'][] = $searchItem;
            break;
        case $core->getIblockId(Core::IBLOCK_CODE_COMPANY_NEWS):
            $arResult['SEARCH_SORTED']['COMPANY_NEWS'][] = $searchItem;
            break;
        default:
    }
}