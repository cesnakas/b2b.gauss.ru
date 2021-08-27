<?php
define("NEED_AUTH", true);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->SetTitle('Загрузка заказа');
?>

<?
$APPLICATION->IncludeComponent(
    'citfact:uploadable.basket',
    '.default',
    array(),
    null,
    array()
);
?>

<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');