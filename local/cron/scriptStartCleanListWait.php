<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__));

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_CAT_CRON", true);
define("NO_AGENT_CHECK", true);

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");



include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/include/clear_waiting_list_script.php');
$ob = new ListWaitHandler();
$res = $ob->clean();