<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

global $APPLICATION;
global $arrSearchFound;

$arrSearchFound = [
    'CATALOG' => array_column($arResult['SEARCH_SORTED']['CATALOG'], 'ITEM_ID'),
    'MANUFACTURER' => array_column($arResult['SEARCH_SORTED']['MANUFACTURERS'], 'ITEM_ID'),
    'COMPANY_NEWS' => array_column($arResult['SEARCH_SORTED']['COMPANY_NEWS'], 'ITEM_ID'),
    'BUYERS' => array_column($arResult['SEARCH_SORTED']['BUYERS'], 'ITEM_ID'),
];
?>
<form action="" method="get" class="b-form b-form--small c-search">
    <div class="b-form__item" data-f-item>
        <span class="b-form__label" data-f-label>Поиск по товарам</span>

        <input class="b-input__input" type="text" name="q" data-f-field value="<?=$arResult["REQUEST"]["QUERY"]?>"
               size="40">

        <button type="submit">
            <svg class='i-icon'>
                <use xlink:href='#icon-search'/>
            </svg>
        </button>
    </div>
    <input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
</form>


