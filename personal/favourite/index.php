<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");
$APPLICATION->SetPageProperty('title', "Избранное");
?>

<? $APPLICATION->IncludeComponent(
    "citfact:favorites.list",
    ".default",
    Array(
        'PARAM_NAME' => 'pampam',
    )
); ?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
