<?php
ini_set("memory_limit","2048M");

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Citfact\Sitecore\CatalogHelper\Ftp;
use Citfact\SiteCore\Core;
use Citfact\Tools\Logger\Logger;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\SiteCore\Tools\InternetResourcesHelper;


?>

<?php
$core = Core::getInstance();
$ftp = Ftp::getInstance();
$logger = new Logger();
global $APPLICATION, $DB;
$DB->Query("SET wait_timeout=28800");
$patchDateScript = $_SERVER["DOCUMENT_ROOT"] . '/local/cron/last_start_script.txt';
if (!file_exists($patchDateScript)) {
    $lastStart = time() - 7200;
} else {
    $lastStart = strtotime(file_get_contents($patchDateScript));
}

file_put_contents($patchDateScript, date('Y-m-d H:i:s')); // записываем дату последнего запуска скрипта

$logger->setLogPath('/upload/ftp_images/logs');
$logger->setLogName('updated_elements_' . date('Y-m-d'));

$internetResourcesHelper = new InternetResourcesHelper();
$filter = ['>UF_LAST_UPDATE'  => ConvertTimeStamp($lastStart)];
$rsData = $internetResourcesHelper->getResources($filter);
while ($item = $rsData->Fetch()){
    try {
        downloadPreviewPicsForInternetResources($ftp, $item['ID']);
    }catch (Exception $e) {
        $logger->addToLog('Error product ID: ' . $item['ID']);
        $logger->addToLog('Error: ' . $e->getMessage());
    }
}


$itemsDB = CIBlockElement::GetList(
    ['SORT' => 'ASC'],
    [
        'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
        '>TIMESTAMP_X' => ConvertTimeStamp($lastStart),
    ],
    [
        'ID',
        'IBLOCK_ID',
        'PROPERTY_DOPOLNITELNOE_IZOBRAZHENIE',
        'PROPERTY_OSNOVNOE_IZOBRAZHENIE',
        'PROPERTY_LINKS_IMG_360',
        'DETAIL_PICTURE',
        'PREVIEW_PICTURE',
        'TIMESTAMP_X',
    ]);

while ($item = $itemsDB->GetNextElement()) {
    try {
        $arProps = $item->GetProperties();
        $item = $item->GetFields();

        $detailPictureName = CFile::GetFileArray($item['DETAIL_PICTURE'])['ORIGINAL_NAME'];
        $previewPictureName = CFile::GetFileArray($item['PREVIEW_PICTURE'])['ORIGINAL_NAME'];

        $detailPicturePropertyUrl = $item['~PROPERTY_DOPOLNITELNOE_IZOBRAZHENIE_VALUE'];
        $previewPicturePropertyUrl = $item['~PROPERTY_OSNOVNOE_IZOBRAZHENIE_VALUE'];



//    switch ( (bool) $detailPicturePropertyUrl <=> (bool) $previewPicturePropertyUrl ) {
//        case -1:
//            $detailPicturePropertyUrl = $previewPicturePropertyUrl;
//            break;
//        case 1:
//            $previewPicturePropertyUrl = $detailPicturePropertyUrl;
//            break;
//    }

        downloadImages360($item,$arProps,$ftp,$logger);

        $updateMorePhoto = setMorePictures($item, $arProps, $ftp, $logger);

        if (null !== $detailPicturePropertyUrl) {

            $detailPicturePropertyPath = str_replace('/', '_', $ftp->getPathFromFtpUrl($detailPicturePropertyUrl));

            if ($detailPictureName !== $detailPicturePropertyPath) {

                $pictures[$item['ID']]['DETAIL_PICTURE'] = $ftp->downloadFtpFile(
                    $detailPicturePropertyUrl,
                    '/upload/ftp_images/' . $item['ID'] . '/',
                    ['png', 'jpg', 'jpeg']);

            }

        }

        if (null !== $previewPicturePropertyUrl) {

            $previewPicturePropertyPath = str_replace('/', '_', $ftp->getPathFromFtpUrl($previewPicturePropertyUrl));

            if ($previewPictureName !== $previewPicturePropertyPath) {

                $pictures[$item['ID']]['PREVIEW_PICTURE'] = $ftp->downloadFtpFile(
                    $previewPicturePropertyUrl,
                    '/upload/ftp_images/' . $item['ID'] . '/',
                    ['png', 'jpg', 'jpeg']);

            }

        }

        if (!empty($pictures[$item['ID']])) {

            $element = new CIBlockElement;
            $elementFields = [];

            if (!empty($pictures[$item['ID']]['PREVIEW_PICTURE'])) {
                $elementFields['PREVIEW_PICTURE'] = CIBlock::ResizePicture(
                    $pictures[$item['ID']]['PREVIEW_PICTURE'], ['WIDTH' => 430, 'HEIGHT' => 430, 'METHOD' => 'resample']
                );
                $logger->addToLog('Element\'s preview picture ' . $item['ID'] . ' is downloaded');
            }

            if (!empty($pictures[$item['ID']]['DETAIL_PICTURE'])) {
                $elementFields['DETAIL_PICTURE'] = CIBlock::ResizePicture(
                    $pictures[$item['ID']]['DETAIL_PICTURE'], ['WIDTH' => 1230, 'HEIGHT' => 1230, 'METHOD' => 'resample']
                );
                $logger->addToLog('Element\'s detail picture ' . $item['ID'] . ' is downloaded');
            }

            if (!empty($elementFields)) {
                $isUpdated = $element->Update($item['ID'], $elementFields);
            }

            if ((!empty($elementFields) && true === $isUpdated) || $updateMorePhoto) {
                $dirPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/ftp_images/' . $item['ID'] . '/';
                $dirFiles = array_diff(scandir($dirPath), ['..', '.']);

                foreach ($dirFiles as $dirFile) {
                    unlink($dirPath . $dirFile);
                }

                if (count(scandir($dirPath)) <= 2) {
                    rmdir($dirPath);
                }

                $logger->addToLog(
                    'Element ' .
                    $item['ID'] .
                    ' is updated' .
                    "\n" .
                    '=============================================================================='
                );
            } else {

                $logger->addToLog(
                    'Element ' .
                    $item['ID'] .
                    ' isn\'t updated --- ERROR' .
                    "\n" .
                    '=============================================================================='
                );

            }

        } else {

            $logger->addToLog(
                '==============================================================================' .
                "\n" .
                'Element ' . $item['ID'] . ' isn\'t updated' .
                "\n" .
                '=============================================================================='
            );

        }
    } catch (Exception $e) {
        $logger->addToLog('Error product ID: ' . $item['ID']);
        $logger->addToLog('Error: ' . $e->getMessage());
    }
}

$ftp->closeConnection();

/**
 * Из свойства DOPOLNITELNYE_IZOBRAZHENIYA скачиваем и устанавливаем изображения для MORE_PHOTO
 *
 * @param $item
 * @param $arProps
 * @param $ftp
 * @param $logger
 *
 * @return bool
 */
function setMorePictures($item, $arProps, $ftp, $logger)
{
    $morePicturesPropertyUrl = [];
    $morePicturesPropertyFiles = [];
    $morePicturesPropertyOld = [];

    if (isset($arProps['DOPOLNITELNYE_IZOBRAZHENIYA']) && !empty($arProps['DOPOLNITELNYE_IZOBRAZHENIYA']['VALUE'])) {
        $morePicturesPropertyUrl = $arProps['DOPOLNITELNYE_IZOBRAZHENIYA']['VALUE'];
    }

    if (isset($arProps['MORE_PHOTO']) && !empty($arProps['MORE_PHOTO']['DESCRIPTION'])) { // установленные изображения
        $morePicturesPropertyOld = $arProps['MORE_PHOTO']['DESCRIPTION'];
    }

    // Если список ссылок пуст, а доп. изображения есть, то очищаем их
    if (empty($morePicturesPropertyUrl) && !empty($morePicturesPropertyOld)) {
        CIBlockElement::SetPropertyValuesEx($item['ID'], $item['IBLOCK_ID'], ['MORE_PHOTO' => array('VALUE' => 'del', 'DESCRIPTION' => '')]);
        $logger->addToLog('Element\'s more photo ' . $item['ID'] . ' delete');

        return true;
    }

    //Если список ссылок не пуст, то сравниваем с установленными
    if (!empty($morePicturesPropertyUrl) && count($morePicturesPropertyUrl) > 0) {
        foreach ($morePicturesPropertyUrl as $url) {
            $tmpFile = $ftp->downloadFtpFile(
                $url,
                '/upload/ftp_images/' . $item['ID'] . '/',
                ['png', 'jpg', 'jpeg']);
            if (!empty($tmpFile)) {
                $morePicturesPropertyFiles[] = array('VALUE' => $tmpFile, 'DESCRIPTION' => $tmpFile['name']);
            }
        }
    }

    if (!empty($morePicturesPropertyFiles)) {
        CIBlockElement::SetPropertyValuesEx($item['ID'], $item['IBLOCK_ID'], ['MORE_PHOTO' => $morePicturesPropertyFiles]);
        $logger->addToLog('Element\'s more photo ' . $item['ID'] . ' is downloaded');
        $logger->addToLog('Count more photo: ' . count($morePicturesPropertyUrl));
        return true;
    }

    return false;
}

/**
 * Скачивает архив с ftp и распаковывает в папку ftp_images_360
 *
 * @param $item
 * @param $arProps
 * @param $ftp
 * @param $logger
 *
 * @return bool
 */
function downloadImages360($item, $arProps, $ftp, $logger)
{
    if (!empty($arProps['LINKS_IMG_360']['VALUE'])) {
        $images360Url = $arProps['LINKS_IMG_360']['VALUE'];
        $tmpFile = $ftp->downloadFtpFile(
            $images360Url,
            '/upload/ftp_images_360/',
            ['zip']);
        $zip = new ZipArchive;
        $archive = $tmpFile['tmp_name'];
        $res = $zip->open($archive);
        if ($res === TRUE) {
            $zip->extractTo($_SERVER['DOCUMENT_ROOT'] . '/upload/ftp_images_360/');
            $zip->close();
            echo 'ok';
            $logger->addToLog('Element\'s 360 pictures ' . $item['ID'] . ' is downloaded ');
            unlink($archive);
        } else {
            echo 'failed';
        }
    }
}


function downloadPreviewPicsForInternetResources($ftp, $id)
{
    $internetResourcesHelper = new InternetResourcesHelper();
    $filter = ['ID'=>$id];
    $rsDataRes = $internetResourcesHelper->getResources($filter);
    while ($arReviews = $rsDataRes->Fetch()) {
        $internetResources [$arReviews['ID']] = $arReviews['UF_PICTURE'];
    }

    foreach ($internetResources as $id => $imgUrl) {
        $imgUrl = trim($imgUrl);
        if (!empty($imgUrl)) {
            if(mb_substr($imgUrl,0,1) !== '/') {
                $imgUrl= '/'. $imgUrl;
            }
            $tmpFile = $ftp->downloadFtpFile(
                $imgUrl,
                $internetResourcesHelper::PREVIEW_IMAGE_PATH . $id . '/',
                ['png', 'jpg', 'jpeg']);
        }
    }
}
?>

<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); ?>
