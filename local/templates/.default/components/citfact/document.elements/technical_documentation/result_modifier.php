<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;

$core = Core::getInstance();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

/**
 * возвращает разделы в виде дерева. если указан $sectionIdClear - убирает данный раздел из дерева
 * $sectionIdClear необходим, если строим дерево не из нулевого уровня
 * @param array $arSections
 * @param int $sectionIdClear
 * @return array|mixed
 */
$getStructuredSections = function ($arSections = array(), $sectionIdClear = 0) {
    $sectionLink = array();
    $arStructured = array();
    $sectionLink[0] = &$arStructured;
    foreach ($arSections as $arSection) {
        if ($sectionIdClear == $arSection['IBLOCK_SECTION_ID']) {
            $sectionLink[0]['SUBSECTIONS'][$arSection['ID']] = $arSection;
            $sectionLink[$arSection['ID']] = &$sectionLink[0]['SUBSECTIONS'][$arSection['ID']];

        } else {
            $sectionLink[intval($arSection['IBLOCK_SECTION_ID'])]['SUBSECTIONS'][$arSection['ID']] = $arSection;
            $sectionLink[$arSection['ID']] = &$sectionLink[intval($arSection['IBLOCK_SECTION_ID'])]['SUBSECTIONS'][$arSection['ID']];
        }
    }
    unset($sectionLink);
    $arSections = $arStructured['SUBSECTIONS'];
    return $arSections;
};

/**
 * распределяем товары по соответствующим им разделам
 */
$arSectionId = $arContentSections = $arSections = [];
foreach ($arResult['ITEMS'] as $item) {
    foreach ($arParams['HL_BLOCK_DOCS'] as $prod) {
        if ($item['XML_ID'] === $prod['UF_NOMENKLATURA']) {
            $item['FILE'] = $prod['UF_IMYAFAYLA'];
            $arSectionId[$item['IBLOCK_SECTION_ID']] = true;
            $arContentSections[$item['IBLOCK_SECTION_ID']][] = $item;
            break;
        }
    }
}

/**
 * получаем список всех разделов в виде развернутого дерева
 */
$db_list_all = \CIBlockSection::GetList([
    "depth_level" => "asc",
    "sort" => "asc",
], [
    'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
    'ACTIVE' => 'Y',
    'GLOBAL_ACTIVE' => 'Y',
], true, [
    'ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL', 'CODE'
]);

$emptyProductsSections = [];
while ($arSection = $db_list_all->Fetch()) {
    $arSection['PRODUCTS'] = $arContentSections[$arSection['ID']];
    $arSections[$arSection['ID']] = $arSection;
    if (empty($arSection['PRODUCTS'])){
        $emptyProductsSections[$arSection['ID']] = true;
    }
}

$reverseArSections = array_reverse($arSections,true);
$parentStack = [];
foreach ($reverseArSections as $key => $section){
    if (!$emptyProductsSections[$section['ID']]){
        $parentStack[$section['IBLOCK_SECTION_ID']] = true;
    } else {
        if (!$parentStack[$section['ID']]){
            unset($reverseArSections[$key]);
        } else {
            $parentStack[$section['IBLOCK_SECTION_ID']] = true;
        }
    }
}
$arResult['ITEMS'] = $getStructuredSections(array_reverse($reverseArSections, true));
