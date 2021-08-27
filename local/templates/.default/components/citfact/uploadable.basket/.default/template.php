<?
use Citfact\SiteCore\UserDataManager\UserDataManager;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
die();
}

$templatePath = '';
$priceTypeXmlId = UserDataManager::getUserPriceType()['XML_ID'];
$priceListFilePath = '/upload/orders/docs/price_lists/price_list_' . $priceTypeXmlId . '.xlsx';
if (true === file_exists($_SERVER['DOCUMENT_ROOT'] . $priceListFilePath)) {
    $templatePath = '/local/include/php/downloader.php?path='.$priceListFilePath.'&name=Прайс_лист_каталога_Gauss.xlsx';

} else {
    $templatePath = '/local/xlsx_templates/price_list_Gauss.xlsx';
}
?>
<div id="upload-basket" data-component="uploadable-basket" data-url="<?= $templatePath; ?>">
<!--    <message-block :message="message" :status="status"></message-block>-->
    <input-form :data="data" :status="status" :message="message"  :type="type"></input-form>
    <basket-form :data="data" :status="status"></basket-form >
</div>
