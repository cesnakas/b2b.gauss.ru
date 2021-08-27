<?

use Bitrix\Main\Application;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");

global $USER;
if ($USER->IsAuthorized() === false) {
        LocalRedirect('/personal/');
}


$rsUser = \CUser::GetByID($USER->GetID());
$arResult["arUser"] = $rsUser->GetNext(false);

if (!$arResult['arUser']['UF_ACTIVATE_PROFILE']) {
        LocalRedirect('/personal/', false, "301 Moved permanently");
} else {
        $action = Application::getInstance()->getContext()->getRequest()->get('action');
        ?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:sale.basket.basket",
            "_template",
            [
                "ACTION_VARIABLE" => "basketAction",
                "AUTO_CALCULATION" => "Y",
                "COLUMNS_LIST" => [
                    0 => "NAME",
                    1 => "DELETE",
                    2 => "DELAY",
                    3 => "PRICE",
                    4 => "QUANTITY",
                    5 => "SUM",
                ],
                "CORRECT_RATIO" => "N",
                "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
                "GIFTS_CONVERT_CURRENCY" => "N",
                "GIFTS_HIDE_BLOCK_TITLE" => "N",
                "GIFTS_HIDE_NOT_AVAILABLE" => "N",
                "GIFTS_MESS_BTN_BUY" => "Выбрать",
                "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
                "GIFTS_PAGE_ELEMENT_COUNT" => "4",
                "GIFTS_PLACE" => "BOTTOM",
                "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
                "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "GIFTS_SHOW_DISCOUNT_PERCENT" => "N",
                "GIFTS_SHOW_IMAGE" => "N",
                "GIFTS_SHOW_NAME" => "N",
                "GIFTS_SHOW_OLD_PRICE" => "N",
                "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
                "HIDE_COUPON" => "N",
                "OFFERS_PROPS" => [
                    0 => "",
                ],
                "PATH_TO_ORDER" => "/order/",
                "PRICE_VAT_SHOW_VALUE" => "Y",
                "QUANTITY_FLOAT" => "N",
                "SET_TITLE" => "N",
                "TEMPLATE_THEME" => "",
                "USE_ENHANCED_ECOMMERCE" => "N",
                "USE_GIFTS" => "N",
                "USE_PREPAYMENT" => "N",
                "COMPONENT_TEMPLATE" => "_template",
                "DEFERRED_REFRESH" => $action === 'setContragent' ? 'N' : 'Y', ///Если в корзине меняется контрагент, то отключаем, т.к. отображается некорректная цена
                                           /// Необходимо установить Y для уменьшения загрузки страницы,
                                           /// но при этом начинает цена обновляться два раза.
                                           /// Для этого необходимо установить значение параметра N в самом шаблоне
                "USE_DYNAMIC_SCROLL" => "Y",
                "SHOW_FILTER" => "Y",
                "SHOW_RESTORE" => "Y",
                "COLUMNS_LIST_EXT" => [
                    0 => "PREVIEW_PICTURE",
                    1 => "DISCOUNT",
                    2 => "DELETE",
                    3 => "DELAY",
                    4 => "TYPE",
                    5 => "SUM",
                ],
                "COLUMNS_LIST_MOBILE" => [
                    0 => "PREVIEW_PICTURE",
                    1 => "DISCOUNT",
                    2 => "DELETE",
                    3 => "DELAY",
                    4 => "TYPE",
                    5 => "SUM",
                ],
                "TOTAL_BLOCK_DISPLAY" => [
                    0 => "bottom",
                ],
                "DISPLAY_MODE" => "extended",
                "PRICE_DISPLAY_MODE" => "Y",
                "SHOW_DISCOUNT_PERCENT" => "N",
                "DISCOUNT_PERCENT_POSITION" => "",
                "PRODUCT_BLOCKS_ORDER" => "",
                "USE_PRICE_ANIMATION" => "Y",
                "LABEL_PROP" => [],
                "COMPATIBLE_MODE" => "N",
                "ADDITIONAL_PICT_PROP_1" => "-",
                "BASKET_IMAGES_SCALING" => "no_scale",
                "EMPTY_BASKET_HINT_PATH" => "/"
            ],
            false
        );?>
        <?

}

?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
