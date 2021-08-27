<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$core = Citfact\SiteCore\Core::getInstance();

$currencyFormat = CCurrencyLang::GetFormatDescription($core::DEFAULT_CURRENCY);
$arResult['CURRENCIES'][] = array(
    'CURRENCY' => $core::DEFAULT_CURRENCY,
    'FORMAT' => array(
        'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
        'DEC_POINT' => $currencyFormat['DEC_POINT'],
        'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
        'DECIMALS' => $currencyFormat['DECIMALS'],
        'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
        'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
    )
);

foreach ($arResult['ITEMS'] as &$cart) {
    if (!empty($cart['PRODUCTS'])) {
        foreach ($cart['PRODUCTS'] as $id => $product) {
            if (empty($product['NAME'])) {
                unset($cart['PRODUCTS'][$id]);
            }
        }
        unset($cart);
    }
}