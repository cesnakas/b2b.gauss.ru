<? use Citfact\SiteCore\Rezervy\RezervyManager;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


/**
 * заполняем резервы по элементам START
 */
$arXMLID = [];
foreach ($arResult['ITEMS'] as $ITEM) {
    $arXMLID[] = $ITEM['XML_ID'];
}

$arResult['RESERV_BALANCE'] = [];
$RESERV_BALANCE = RezervyManager::getListByNomenclaturers($arXMLID);
foreach ($RESERV_BALANCE as $balance) {
    $arResult['RESERV_BALANCE'][$balance['UF_NOMENKLATURA']] = $balance;
}

foreach ($arResult['ITEMS'] as &$ITEM) {
    $ITEM['RESERV_BALANCE'] = $arResult['RESERV_BALANCE'][$ITEM['XML_ID']];
}
/**
 * заполняем резервы по элементам END
 */