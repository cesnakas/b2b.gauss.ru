<?php
ini_set("memory_limit","2048M");

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Citfact\SiteCore\ContragentHelper\ContragentHelper;


try {
    (new ContragentHelper())->deactivateContragents();
} catch (Exception $e) {
    exit($e->getMessage());
}
