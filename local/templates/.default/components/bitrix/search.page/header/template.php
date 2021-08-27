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
<form action="<?= SITE_DIR; ?>search/" class="b-form h__search" data-f-item>
    <div class="b-form__item">
        <div class="b-form__label" data-f-label><?= Loc::getMessage('SEARCH_TITLE_PLACEHOLDER'); ?></div>
        <input type="text" name="q" value="<?= $arResult['REQUEST']['QUERY']; ?>" data-f-field>
        <button type="submit">
            <svg class='i-icon'>
                <use xlink:href='#icon-search'/>
            </svg>
        </button>
    </div>
</form>