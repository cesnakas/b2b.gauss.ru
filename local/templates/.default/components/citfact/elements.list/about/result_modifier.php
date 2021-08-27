<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 * @global CUser $USER
 * @global CMain $APPLICATION
 */

foreach ($arResult['ITEMS'] as $key => &$item) {
    $item['PREVIEW_PICTURE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($item['PREVIEW_PICTURE'], 1920, 700);
    $item['PROPERTY_IMAGE_MOBILE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($item['PROPERTY_IMAGE_MOBILE_VALUE'], 320, 250);
}
