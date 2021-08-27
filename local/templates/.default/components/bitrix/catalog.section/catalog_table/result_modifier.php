<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Citfact\Sitecore\CatalogHelper\SectionIblockPropertiesTable;

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


/**
 * заполняем резервы по элементам
 * [START]
 */
$arXMLID = [];
foreach ($arResult['ITEMS'] as $ITEM) {
    $arXMLID[] = $ITEM['XML_ID'];
}

$arResult['RESERV_BALANCE'] = [];
$RESERV_BALANCE = Citfact\SiteCore\Rezervy\RezervyManager::getListByNomenclaturers($arXMLID);
foreach ($RESERV_BALANCE as $balance) {
    $arResult['RESERV_BALANCE'][$balance['UF_NOMENKLATURA']] = $balance;
}

$displayProperties = SectionIblockPropertiesTable::getProperties($arResult['ID'], 'UF_IBLOCK_PROPERTIES');

foreach ($arResult['ITEMS'] as &$ITEM) {

    if (!empty($displayProperties)) {

        $ITEM['DISPLAY_PROPERTIES'] = [];

        foreach ($displayProperties as $propertyCode) {
            $ITEM['DISPLAY_PROPERTIES'][$propertyCode] = $ITEM['PROPERTIES'][$propertyCode];
        }
    }

    $ITEM['RESERV_BALANCE'] = $arResult['RESERV_BALANCE'][$ITEM['XML_ID']];

}
/**
 * [END]
 * заполняем резервы по элементам
 */

$arResult['ELEMENT_PAGE_AMOUNT'] = $arResult['NAV_RESULT']->NavRecordCount;

$this->__component->SetResultCacheKeys(['ELEMENT_PAGE_AMOUNT','ITEMS']);