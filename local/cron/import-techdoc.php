<?php
use Citfact\Sitecore\CatalogHelper\Ftp;
use Citfact\SiteCore\Dokumentatsiya\DokumentatsiyaManager;

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('highloadblock'); //модуль highload инфоблоков
$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>'b_dokumentatsiya')));
if ( !($hldata = $rsData->fetch()) ) {

} else {
    $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $res = $hlDataClass::getList(array(
            'select' => array("*"),
            'order' => array(
                'ID' => 'asc'
            ),
        )
    );
    while ($row = $res->fetch()) {
        $HLinfo[] =$row;
    }
}
$arProd['XML_IDS'] = $HLinfo;
foreach ($arProd['XML_IDS'] as $prod){
    if (!in_array($prod['UF_SSYLKANAFAYL'], $arProdXmlId)){
        $arProdXmlId[] = $prod['UF_SSYLKANAFAYL'];
    }
}

$ftp = Ftp::getInstance();

$files = [];
$counter = 0;
foreach ($arProdXmlId as $remoteUrl) {

    $files[] = $ftp->downloadFtpFile($remoteUrl, DokumentatsiyaManager::DIR_FILES, [], false);

    $counter++;

}