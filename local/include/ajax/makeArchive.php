<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('STOP_STATISTICS', true);
define("NO_AGENT_CHECK", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
ini_set('memory_limit', '2024M');
$ID  = $_POST['sectionId'];
$archivePath = '/upload/tmp/archive/archives'.date(YmdsHis). $ID .'.zip';

$packarc = CBXArchive::GetArchive($_SERVER["DOCUMENT_ROOT"] . $archivePath);
$files = [];
foreach ($_POST['files'] as $filePath){
    //создание копий файлов, чтобы избавиться от лишних папок
    $tmp = explode("/",$filePath);
    copy($_SERVER["DOCUMENT_ROOT"] . $filePath, $_SERVER["DOCUMENT_ROOT"]  . "/upload/tmp/archive/". $tmp[4] );
    $files[] = $_SERVER["DOCUMENT_ROOT"]  . "/upload/tmp/archive/". $tmp[4] ;
}
$packarc->SetOptions(Array(
    "REMOVE_PATH" => $_SERVER["DOCUMENT_ROOT"]."/upload/tmp/archive",
));

$pRes = $packarc->Pack($files);
//удаление копий
foreach ($_POST['files'] as $filePath){
    $tmp = explode("/",$filePath);
    unlink($_SERVER["DOCUMENT_ROOT"]  . "/upload/tmp/archive/". $tmp[4]);
}
die($archivePath);
