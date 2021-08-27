<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

global $APPLICATION;
if($arResult['1C_NUMBER']){
    $title = Loc::getMessage('SPOD_LIST_MY_ORDER', array(
        '#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["1C_NUMBER"]),
        '#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT"]->format('d.m.Y')
    ));
    $APPLICATION->SetPageProperty('title', $title);

} else {
    $title = Loc::getMessage('SPOD_LIST_MY_ORDER', array(
        '#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["ID"]),
        '#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT"]->format('d.m.Y')
    ));
    $APPLICATION->SetPageProperty('title', $title);

}

