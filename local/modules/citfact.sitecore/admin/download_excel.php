<?php

use Bitrix\Sale;
use Citfact\SiteCore\Core;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$core = Core::getInstance();
$iblockID = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

//Создание объекта класса библиотеки
$objPHPExcel = new PHPExcel();
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

//Указываем страницу, с которой работаем
$objPHPExcel->setActiveSheetIndex(0);

//Получаем страницу, с которой будем работать
$active_sheet = $objPHPExcel->getActiveSheet();

$active_sheet->setTitle('Товары');

$active_sheet->mergeCells("A1:G1");
$active_sheet->setCellValue("A1", "Заказ покупателя от " . date("d.m.Y"));
$active_sheet->getStyle("A1")->getFont()->setSize(14);
$active_sheet->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle("A1")->getFont()->setBold(true);

$active_sheet->mergeCells("A3:G3");
$active_sheet->setCellValue("A3", "1. Товары, готовые к отгрузке");

$headers = ['Артикул', 'Номенклатура', 'Вес нетто, кг', 'Объем, м3', 'Цена', 'Количество', 'Сумма'];
$charNumber = ord('A');
foreach ($headers as $header) {
    $cell = chr($charNumber) . '5';
    $active_sheet->setCellValue($cell, $header);
    $active_sheet->getStyle($cell)->applyFromArray(array('font' => array('bold' => true)));
    $active_sheet->getStyle($cell)->getFont()->getColor()->setRGB('008000');
    $active_sheet->getStyle($cell, $header)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $charNumber++;
}

$active_sheet->getColumnDimension("A")->setWidth(15);
$active_sheet->getColumnDimension("B")->setWidth(100);
$active_sheet->getColumnDimension("C")->setWidth(15);
$active_sheet->getColumnDimension("D")->setWidth(15);
$active_sheet->getColumnDimension("E")->setWidth(15);
$active_sheet->getColumnDimension("F")->setWidth(15);
$active_sheet->getColumnDimension("G")->setWidth(15);
$active_sheet->getColumnDimension("H")->setWidth(30);

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

$arItemsId = [];
foreach ($basket as $basketItem) {
    $productID = $basketItem->getField('PRODUCT_ID');
    $arItemsId[] = $productID;
}

$res = CIBlockElement::GetList(
    [],
    [
        "IBLOCK_ID" => $iblockID,
        'ID' => $arItemsId
    ],
    false,
    [],
    [
        'ID',
        'XML_ID',
        'PROPERTY_CML2_ARTICLE',
        'PROPERTY_VES_NETTO',
        'PROPERTY_OBEM'
    ]
);

$arItems = [];
$arXML_ID = [];
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    $arItems[$arFields['ID']] = [
        'ARTICLE'=>$arFields['PROPERTY_CML2_ARTICLE_VALUE'],
        'VES_NETTO'=>$arFields['PROPERTY_VES_NETTO_VALUE'],
        'OBEM'=>$arFields['PROPERTY_OBEM_VALUE'],
        'XML_ID'=>$arFields['XML_ID'],
    ];
    $arXML_ID[] = $arFields['XML_ID'];
}

foreach ($arItemsId as $itemId) {
    $arItems[$itemId]['QUANTITY'] = CCatalogProduct::GetByID($itemId)['QUANTITY'];
}

$RESERVY = Citfact\SiteCore\Rezervy\RezervyManager::getListByNomenclaturers($arXML_ID);

$arDeliveryDate = [];
foreach ($RESERVY as $balance) {
    $arDeliveryDate[$balance['UF_NOMENKLATURA']] = $balance['UF_DATAPRIKHODA'];
}

foreach ($arItems as &$item) {
    $item['DELIVERY_DATE'] = $arDeliveryDate[$item['XML_ID']];
}
unset($item);

foreach ($basket as $basketItem) {
    $productID = $basketItem->getField('PRODUCT_ID');

    $arItems[$productID]['NAME'] = $basketItem->getField('NAME');
    $arItems[$productID]['PRICE'] = $basketItem->getField('PRICE');
    if (array_search('TWO_PERCENT', $_SESSION['CATALOG_USER_COUPONS']) !== false) {
        $arItems[$productID]['PRICE'] *= 0.98;
    }
    $arItems[$productID]['BASKET_QUANTITY'] = $basketItem->getField('QUANTITY');
    $arItems[$productID]['SUM'] = $basketItem->getField('PRICE') * $basketItem->getField('QUANTITY');
    if (array_search('TWO_PERCENT', $_SESSION['CATALOG_USER_COUPONS']) !== false) {
        $arItems[$productID]['SUM'] *= 0.98;
    }
}

$totalOrderSum = 0;
$totalVolume = 0;
$totalWeight = 0;
$rowNumber = 6;
$tableStart = 'A5';

foreach ($arItems as $item) {
    if ($item['QUANTITY'] > 0) {
        $itemValues = [$item['ARTICLE'], $item['NAME'], $item['VES_NETTO'], $item['OBEM'], $item['PRICE'], $item['BASKET_QUANTITY'], $item['SUM']];

        $charNumber = ord('A');
        foreach ($itemValues as $value) {
            $cell = chr($charNumber) . "$rowNumber";
            $active_sheet->setCellValue($cell, $value);
            $active_sheet->getStyle($cell)->getFont()->getColor()->setRGB('008000');
            if ($cell[0] !== 'B') {
                $active_sheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $charNumber++;
        }

        $rowNumber++;
        $totalOrderSum += $item['SUM'];
        $totalVolume += $item['OBEM'];
        $totalWeight += $item['VES_NETTO'];

    }
}

$active_sheet->setCellValue('B' . $rowNumber, "Итого:");
$active_sheet->getStyle('B' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$active_sheet->getStyle('B' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('B' . $rowNumber)->getFont()->getColor()->setRGB('008000');
$active_sheet->setCellValue('C' . $rowNumber, $totalWeight);
$active_sheet->getStyle('C' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('C' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('C' . $rowNumber)->getFont()->getColor()->setRGB('008000');
$active_sheet->setCellValue('D' . $rowNumber, $totalVolume);
$active_sheet->getStyle('D' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('D' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('D' . $rowNumber)->getFont()->getColor()->setRGB('008000');
$active_sheet->setCellValue('G' . $rowNumber, $totalOrderSum);
$active_sheet->getStyle('G' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('G' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('G' . $rowNumber)->getFont()->getColor()->setRGB('008000');

$tableEnd = 'G' . $rowNumber;

$border = array(
    'borders'=>array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);

$active_sheet->getStyle($tableStart . ':' . $tableEnd)->applyFromArray($border);

$rowNumber += 2;

$active_sheet->mergeCells('A' . $rowNumber . ':' . 'H' . $rowNumber);
$active_sheet->setCellValue('A' . $rowNumber, "2. Сроки догрузки");

$rowNumber += 2;
$tableStart = 'A' . ($rowNumber);

$headers = ['Артикул', 'Номенклатура', 'Вес нетто, кг', 'Объем, м3', 'Цена', 'Количество', 'Сумма', 'Максимальный срок поставки'];
$charNumber = ord('A');
foreach ($headers as $header) {
    $cell = chr($charNumber) . $rowNumber;
    $active_sheet->setCellValue($cell, $header);
    $active_sheet->getStyle($cell)->applyFromArray(array('font' => array('bold' => true)));
    $active_sheet->getStyle($cell)->getFont()->getColor()->setRGB('0000ff');
    $active_sheet->getStyle($cell, $header)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $charNumber++;
}

$totalOrderSum = 0;
$totalVolume = 0;
$totalWeight = 0;
$rowNumber++;

foreach ($arItems as $item) {
    $dateNow = strtotime(date('Y-m-d'));
    $dateDelivery = strtotime($item['DELIVERY_DATE']);
    $deliveryDateFormat = date("d.m.Y", $dateDelivery);

    if (($item['QUANTITY'] <= 0 || empty($item['QUANTITY'])) && ($dateDelivery !== false && (($dateDelivery - $dateNow) < 21 * 24 * 60 * 60))) {
        $itemValues = [$item['ARTICLE'], $item['NAME'], $item['VES_NETTO'], $item['OBEM'], $item['PRICE'], $item['BASKET_QUANTITY'], $item['SUM'], $deliveryDateFormat];

        $charNumber = ord('A');
        foreach ($itemValues as $value) {
            $cell = chr($charNumber) . "$rowNumber";
            $active_sheet->setCellValue($cell, $value);
            $active_sheet->getStyle($cell)->getFont()->getColor()->setRGB('0000ff');
            if ($cell[0] !== 'B') {
                $active_sheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $charNumber++;
        }

        $rowNumber++;
        $totalOrderSum += $item['SUM'];
        $totalVolume += $item['OBEM'];
        $totalWeight += $item['VES_NETTO'];
    }
}

$active_sheet->setCellValue('B' . $rowNumber, "Итого:");
$active_sheet->getStyle('B' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$active_sheet->getStyle('B' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('B' . $rowNumber)->getFont()->getColor()->setRGB('0000ff');
$active_sheet->setCellValue('C' . $rowNumber, $totalWeight);
$active_sheet->getStyle('C' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('C' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('C' . $rowNumber)->getFont()->getColor()->setRGB('0000ff');
$active_sheet->setCellValue('D' . $rowNumber, $totalVolume);
$active_sheet->getStyle('D' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('D' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('D' . $rowNumber)->getFont()->getColor()->setRGB('0000ff');
$active_sheet->setCellValue('G' . $rowNumber, $totalOrderSum);
$active_sheet->getStyle('G' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('G' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('G' . $rowNumber)->getFont()->getColor()->setRGB('0000ff');

$tableEnd = 'H' . $rowNumber;

$active_sheet->getStyle($tableStart . ':' . $tableEnd)->applyFromArray($border);

$rowNumber += 2;

$active_sheet->mergeCells('A' . $rowNumber . ':' . 'H' . $rowNumber);
$active_sheet->setCellValue('A' . $rowNumber, "3. Товаров нет в наличии. Срок поставки более 21 дня");

$rowNumber += 2;
$tableStart = 'A' . ($rowNumber);

$headers = ['Артикул', 'Номенклатура', 'Вес нетто, кг', 'Объем, м3', 'Цена', 'Количество', 'Сумма', 'Максимальный срок поставки'];
$charNumber = ord('A');
foreach ($headers as $header) {
    $cell = chr($charNumber) . $rowNumber;
    $active_sheet->setCellValue($cell, $header);
    $active_sheet->getStyle($cell)->applyFromArray(array('font' => array('bold' => true)));
    $active_sheet->getStyle($cell)->getFont()->getColor()->setRGB('ff4500');
    $active_sheet->getStyle($cell, $header)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $charNumber++;
}

$totalOrderSum = 0;
$totalVolume = 0;
$totalWeight = 0;
$rowNumber++;

foreach ($arItems as $item) {
    $dateNow = strtotime(date('Y-m-d'));
    $dateDelivery = strtotime($item['DELIVERY_DATE']);
    if ($dateDelivery) {
        $deliveryDateFormat = date("d.m.Y", $dateDelivery);
    } else {
        $deliveryDateFormat = '';
    }

    if (($item['QUANTITY'] <= 0 || empty($item['QUANTITY'])) && ($dateDelivery === false || (($dateDelivery - $dateNow) >= 21 * 24 * 60 * 60))) {
        $itemValues = [$item['ARTICLE'], $item['NAME'], $item['VES_NETTO'], $item['OBEM'], $item['PRICE'], $item['BASKET_QUANTITY'], $item['SUM'], $deliveryDateFormat];

        $charNumber = ord('A');
        foreach ($itemValues as $value) {
            $cell = chr($charNumber) . "$rowNumber";
            $active_sheet->setCellValue($cell, $value);
            $active_sheet->getStyle($cell)->getFont()->getColor()->setRGB('ff4500');
            if ($cell[0] !== 'B') {
                $active_sheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $charNumber++;
        }

        $rowNumber++;
        $totalOrderSum += $item['SUM'];
        $totalVolume += $item['OBEM'];
        $totalWeight += $item['VES_NETTO'];
    }
}

$active_sheet->setCellValue('B' . $rowNumber, "Итого:");
$active_sheet->getStyle('B' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$active_sheet->getStyle('B' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('B' . $rowNumber)->getFont()->getColor()->setRGB('ff4500');
$active_sheet->setCellValue('C' . $rowNumber, $totalWeight);
$active_sheet->getStyle('C' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('C' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('C' . $rowNumber)->getFont()->getColor()->setRGB('ff4500');
$active_sheet->setCellValue('D' . $rowNumber, $totalVolume);
$active_sheet->getStyle('D' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('D' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('D' . $rowNumber)->getFont()->getColor()->setRGB('ff4500');
$active_sheet->setCellValue('G' . $rowNumber, $totalOrderSum);
$active_sheet->getStyle('G' . $rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$active_sheet->getStyle('G' . $rowNumber)->applyFromArray(array('font' => array('bold' => true)));
$active_sheet->getStyle('G' . $rowNumber)->getFont()->getColor()->setRGB('ff4500');

$tableEnd = 'H' . $rowNumber;

$active_sheet->getStyle($tableStart . ':' . $tableEnd)->applyFromArray($border);

//Сохраняем файл с помощью PHPExcel_IOFactory и указываем тип Excel
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//Отправляем заголовки с типом контекста и именем файла
header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=Заказ покупателя.xlsx");

//Отправляем файл
$objWriter->save('php://output');
