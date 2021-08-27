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
foreach ($arResult['ITEMS'] as $key => $article) {
    if (!empty($article['ACTIVE_FROM'])) {
        $month = (int)date('m', strtotime($article['ACTIVE_FROM']));
        $year = date('Y', strtotime($article['ACTIVE_FROM']));
        $arResult['ITEMS'][$key]['ACTIVE_FROM'] = $months[$month] . ', ' . $year;
    }
}

foreach ($arResult['ITEMS'] as $key => &$item) {
    if (!empty($item['PREVIEW_PICTURE']['SRC'])) {
        $pictureResized = CFile::ResizeImageGet(
            $item['PREVIEW_PICTURE'],
            ['width' => 400, 'height' => 400],
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
            true
        );
        //Сохраняем поля оригинального изображения и добавляем поля resize изображения
        $item['PREVIEW_PICTURE'] = array_merge($item['PREVIEW_PICTURE'], $pictureResized);
    }
    unset($item);
}
