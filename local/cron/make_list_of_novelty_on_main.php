<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Citfact\SiteCore\Core;
$core = Core::getInstance();

$arSelect = Array('ID', 'PROPERTY_NOVELTY_ACTIVE_FROM', 'PROPERTY_NOVELTY_ACTIVE_TO');
$objDateTime = date("Y-m-d H:i:s");
$arFilter = Array("IBLOCK_ID"=>$core->getIblockId(CORE::IBLOCK_CODE_CATALOG), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 'PROPERTY_NOVINKA_VALUE'=>'Да',  '<PROPERTY_NOVELTY_ACTIVE_FROM'=> $objDateTime, '>PROPERTY_NOVELTY_ACTIVE_TO'=> $objDateTime);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()){
    $arFields[] = $ob->GetFields();
}

if (count($arFields)<50){
    $count = 50 - count($arFields);
    $arSelect = Array('ID');
    $arFilter = Array("IBLOCK_ID"=>$core->getIblockId(CORE::IBLOCK_CODE_CATALOG), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", '!PROPERTY_MAIN_NOVELTY_VALUE'=>'Да', '!DETAIL_PICTURE' => false);
    $res = CIBlockElement::GetList(Array('DATE_CREATE'=>'DESC'), $arFilter, false, Array("nTopCount"=>$count), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields[] = $ob->GetFields();
    }
}

file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/cron/make_list_of_novelty_on_main.txt', json_encode($arFields));
