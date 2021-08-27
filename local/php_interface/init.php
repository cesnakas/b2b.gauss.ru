<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php')) {
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/constants.php')) {
    require $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/constants.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/functions.php')) {
    require $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/functions.php';
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

Loader::includeModule('citfact.tools');
Loader::includeModule('citfact.sitecore');
Loader::includeModule('iblock');
Loader::includeModule('highloadblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');
Loader::includeModule('subscribe');
Loader::includeModule('citfact.datacache');

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/providers/product_provider_custom.php')) {
    require $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/providers/product_provider_custom.php';
}

// Обработчики событий тут: /local/modules/citfact.sitecore/lib/eventlistener
$eventDispatcher = new \Citfact\Tools\Event\Dispatcher();
$eventDispatcher->registerByModule('citfact.sitecore');