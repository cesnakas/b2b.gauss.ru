<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$months = [
    1 => 'Январь',
    2 => 'Февраль',
    3 => 'Март',
    4 => 'Апрель',
    5 => 'Май',
    6 => 'Июнь',
    7 => 'Июль',
    8 => 'Август',
    9 => 'Сентябрь',
    10 => 'Октябрь',
    11 => 'Ноябрь',
    12 => 'Декабрь',
];
// менем формат даты
if (!empty($arResult['ACTIVE_FROM'])) {
    $month = (int)date('m', strtotime($arResult['ACTIVE_FROM']));
    $year = date('Y', strtotime($arResult['ACTIVE_FROM']));
    $arResult['ACTIVE_FROM'] = $months[$month] . ', ' . $year;
}

if (!empty($arResult['DETAIL_PICTURE']['SRC'])) {
    $pictureResized = CFile::ResizeImageGet(
        $arResult['DETAIL_PICTURE'],
        ['width' => 405, 'height' => 405],
        BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
        true
    );

    //Сохраняем поля оригинального изображения и добавляем поля resize изображения
    $arResult['DETAIL_PICTURE'] = array_merge($arResult['DETAIL_PICTURE'], $pictureResized);
}

$res = CIBlockElement::GetList(
    array(
        $arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
        $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
    ),
    array(
        'IBLOCK_ID' => $arResult['IBLOCK_ID'], // здесь ID инфоблока, в котором находится элемент
        'ACTIVE' => 'Y',
        'SECTION_ID' => $arResult['IBLOCK_SECTION_ID']
    ),
    false,
    array(
        'nElementID' => $arResult['ID'],
        'nPageSize' => 1
    )
);

$nearElementsSide = 'LEFT';
while ($arElem = $res->GetNext()) {
    if ($arElem['ID'] == $arResult['ID']) {
        $nearElementsSide = 'RIGHT';
        continue;
    }
    $arResult['NEAR_ELEMENTS'][$nearElementsSide][] = $arElem;
}