<?php 

use Citfact\SiteCore\UserDataManager\UserDataManager;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Прайс-листы");

$priceTypeXmlId = UserDataManager::getUserPriceType()['XML_ID'];
$priceListFilePath = '/upload/orders/docs/price_lists/price_list_' . $priceTypeXmlId . '.xlsx';
?>

<div class="styled-list styled-text">
    <?php if (true === file_exists($_SERVER['DOCUMENT_ROOT'] . $priceListFilePath)) { ?>
        <p>
            <span>Скачать прайс-лист всего каталога:&nbsp;</span>&nbsp;
            <a href="/local/include/php/downloader.php?path=<?= $priceListFilePath; ?>&name=Прайс_лист_каталога_Gauss.xlsx" title="XLSX">
                XLSX
            </a>*
            <br/>
            *Колонка "<b>оптовая цена</b>" = актуальная цена
            <br/>
            Для оформления заказа из прайс-листа воспользуйтесь функцией быстрый заказ
            <br/>
        </p>
    <?php } ?>
    <div class="title-4">Для дальнейшего оформления заказа:</div>
    <ul>
        <li>Выберите и откройте соответствующую товарную категорию прайс-листа;</li>
        <li>Укажите количество товара в интересующей Вас товарной позиции;</li>
        <li>Сохраните прайс-лист с проставленным количеством товара и перейдите к загрузке заказа.</li>
    </ul>
    <div>
        <a href="/personal/load_order/" class="lk__section-head-link lk__section-head-link-styled">
            <span>Перейти к загрузке заказа</span>
        </a> 
    </div>

</div>

<?/* $APPLICATION->IncludeComponent("citfact:price.list", "price_kp", Array(
    "ACCOUNT" => "Y"
),
    false
); */?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>