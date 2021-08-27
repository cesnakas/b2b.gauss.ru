<?php
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

if (!isset($_POST['siteId']) || !is_string($_POST['siteId']))
    die();

if (!isset($_POST['templateName']) || !is_string($_POST['templateName']))
    die();

if ($_SERVER['REQUEST_METHOD'] != 'POST' ||
    preg_match('/^[A-Za-z0-9_]{2}$/', $_POST['siteId']) !== 1)
    die;

define('SITE_ID', $_POST['siteId']);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (!check_bitrix_sessid())
    die;

ob_start();
$_POST['arParams']['AJAX'] = 'Y';
$APPLICATION->RestartBuffer();
header('Content-Type: text/html; charset='.LANG_CHARSET);
$APPLICATION->IncludeComponent('bitrix:sale.basket.basket.line', $_POST['templateName'], $_POST['arParams']);
$html = ob_get_contents();
ob_end_clean();


/**
 * @var $basketItem Bitrix\Sale\BasketItem
 */
$arBasketItems = [];
$basket = Citfact\SiteCore\Tools\Basket::getCurrentCart();
$basketItems = $basket->getBasketItems();
foreach ($basketItems as $basketItem) {
    if (false === $basketItem->isDelay()) {
        $arBasketItems[$basketItem->getProductId()] = $basketItem->getQuantity();
    }
}



$arResult = [
    'html' => $html,
    'items' => $arBasketItems,
];

echo json_encode($arResult);



