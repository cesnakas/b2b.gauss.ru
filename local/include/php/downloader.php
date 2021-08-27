<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
Loader::includeModule('citfact.tools');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$path = $request->getQuery('path');
$fileName = $request->getQuery('name') ?: '';

//echo "<pre style=\"display:block;\">"; print_r($_REQUEST); echo "</pre>";
//var_dump(file_exists($_SERVER['DOCUMENT_ROOT'].$_REQUEST['path']));

if ($path != '' && file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
    Citfact\Tools\Tools::file_force_download($_SERVER['DOCUMENT_ROOT'] . $path, $fileName);
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
