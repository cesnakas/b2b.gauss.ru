<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach($arResult['ITEMS'] as $key => &$item){

    // формирование формата даты
    $date = FormatDate('f, Y', MakeTimeStamp($item['DISPLAY_PROPERTIES']['DATE']['VALUE'], 'DD.MM.YYYY'));
    $item['DISPLAY_PROPERTIES']['DATE']['VALUE'] = $date;

    // ресайз изображений
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
    ///$item['PREVIEW_PICTURE'] = getResizePictures($item['PREVIEW_PICTURE']['ID'], 400, 400, 100, 100);
}
unset($item);