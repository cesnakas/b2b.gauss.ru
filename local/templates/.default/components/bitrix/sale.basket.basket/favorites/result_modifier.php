<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;

foreach ($arResult['GRID']['ROWS'] as $key => $item){
    if ($item["DELAY"] == "Y" && $item["CAN_BUY"] == "Y") {
        $prods[] = $item['PRODUCT_ID'];
    }else{
        unset($arResult['GRID']['ROWS'][$key]);
    }
}

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$prods);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);

while ($object = $res->GetNextElement()) {
    $properties = $object->GetProperties();
    $item = $object->GetFields();
    foreach ($properties as $property) {
        $this->arResult['PROPERTIES'][$property['CODE']] = $property;
        if ($property['VALUE'] && in_array($property['CODE'], $arParams['PROPERTY_CODE'])) {
            $item['DISPLAY_PROPERTIES']['PROPERTY_' . $property['CODE']]['CODE'] = $property['CODE'];
            $item['DISPLAY_PROPERTIES']['PROPERTY_' . $property['CODE']]['NAME'] = $property['NAME'];
            $item['DISPLAY_PROPERTIES']['PROPERTY_' . $property['CODE']]['VALUE'] = $property['VALUE'];
        }
    }
    $arProps[$item['ID']] = $item;
}

foreach ($arResult['GRID']['ROWS'] as $key => &$arItem){
    foreach ($arProps[$arItem['PRODUCT_ID']]['DISPLAY_PROPERTIES'] as $prop) {
        $arItem['DISPLAY_PROPERTIES'][$prop['CODE']]['NAME'] = $prop['NAME'];
        $arItem['DISPLAY_PROPERTIES'][$prop['CODE']]['VALUE'] = $prop['VALUE'];
    }
}