<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
Loader::includeModule('citfact.tools');

//echo "<pre style=\"display:block;\">"; print_r($_REQUEST); echo "</pre>";
//var_dump(file_exists($_SERVER['DOCUMENT_ROOT'].$_REQUEST['path']));

if ($_REQUEST['path'] != '' && file_exists($_SERVER['DOCUMENT_ROOT'].$_REQUEST['path'])) {
	Citfact\Tools\Tools::file_force_download($_SERVER['DOCUMENT_ROOT'].$_REQUEST['path']);
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
