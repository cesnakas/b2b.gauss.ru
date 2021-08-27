<?php
$arUrlRewrite=array (
  1 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/press-center/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/press-center/articles/index.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^/personal/companies/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/personal/companies/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/press-center/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/press-center/news/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/personal/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/orders/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/promotions/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/promotions/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/products/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/products/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);
