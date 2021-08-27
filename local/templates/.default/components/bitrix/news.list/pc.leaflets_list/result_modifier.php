<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// меняем размер картинки
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