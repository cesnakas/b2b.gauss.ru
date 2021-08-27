<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */
use Citfact\SiteCore\Core;
$core = Core::getInstance();
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

global $USER;
$finalArr = $arResult['ITEMS'];
foreach ($arResult['ITEMS'] as $id => $item) {
    $arrT = array();
    if (!empty($item['PREVIEW_PICTURE'])){
        $arrT['ID'] = $item['PREVIEW_PICTURE'];
        $arrT['SRC'] = CFile::GetPath($item['PREVIEW_PICTURE']);
        $finalArr[$id]['PREVIEW_PICTURE'] = array();
        $finalArr[$id]['PREVIEW_PICTURE'] = $arrT;
        $finalArr[$id]['PREVIEW_PICTURE']['WIDTH'] = 300;
        $finalArr[$id]['PREVIEW_PICTURE']['HEIGHT'] = 300;
    }
//    $finalArr[$id]['PRODUCT']['QUANTITY'] = $finalArr[$id]['QUANTITY'];
}
$arResult['ITEMS']= array();
$arResult['ITEMS']=$finalArr;
/**
 * заполняем резервы по элементам START
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

foreach ($arResult['ITEMS'] as &$ITEM) {
    $ITEM['RESERV_BALANCE'] = $arResult['RESERV_BALANCE'][$ITEM['XML_ID']];
}
/**
 * заполняем резервы по элементам END
 */
