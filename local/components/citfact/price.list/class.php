<?

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

class PriceListComponent extends \CBitrixComponent
{
    private $additionalPriceId = 0;
    private $core;

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        global $APPLICATION;
        $request = Application::getInstance()->getContext()->getRequest();
        $requestData = $request->getQueryList()->toArray();

        $this->connectSections();
        if ($requestData['PRICE_LIST_SECTION_ID'] > 0) {
            $this->connectItems($requestData['PRICE_LIST_SECTION_ID']);
            $headers = array(
                'ARTICLE' => Loc::getMessage('ARTICLE'),
                'NAME' => Loc::getMessage('NAME'),
            );
            if ($this->arParams['ACCOUNT'] == 'Y') {
                $headers = array_merge($headers, array(
                    'PRICE' => Loc::getMessage('PRICE_OPT'),
                ));
            } else {
                $headers = array_merge($headers, array(
                    'PRICE_RZ_0' => Loc::getMessage('PRICE'),
                ));
            }

            $APPLICATION->RestartBuffer();
            if ($requestData['DOWNLOAD'] == 'Y') {
                $this->displayExcel($headers, $this->arResult['ITEMS'], $requestData);
            } else {
                $this->displayTable($headers, $this->arResult['ITEMS']);
            }
            die();
        }

        $this->IncludeComponentTemplate();
    }

    private function connectItems($sectionId)
    {
        $cElement = new \CIBlockElement();
        $cCore = Core::getInstance();
        $filter = array(
            'IBLOCK_ID' => IBLOCK_ID_TSTN_CATALOG,
            'SECTION_ID' => $sectionId,
            'INCLUDE_SUBSECTIONS' => 'Y',
            'ACTIVE' => 'Y',
        );

        $rz0Price = $this->getRZ0PriceId();
        if ($this->arParams['ACCOUNT'] == 'Y') {
            $priceFilter = array(
                'LOGIC' => 'OR',
                '>CATALOG_PRICE_' . $cCore->Region->getPrices()->priceId => 0,
            );
            if ($rz0Price) {
                $priceFilter['>CATALOG_PRICE_' . $rz0Price] = 0;
            }
            $filter[] = $priceFilter;
        } elseif ($rz0Price) {
            $filter['>CATALOG_PRICE_' . $rz0Price] = 0;
        }
        $select = array(
            'ID',
            'NAME',
            'PROPERTY_CML2_ARTICLE',
        );

        $res = $cElement->GetList(array('PROPERTY_CML2_ARTICLE' => 'ASC'), $filter, false, false, $select);
        $pricesByGuid = $cCore->Region->getPrices();
        $currentPriceCode = $pricesByGuid->getCurrentPriceCode();
        while ($item = $res->Fetch()) {
            $prices = $pricesByGuid->getAllRegionPrices($item['ID']);
            $webServicePostfix = $_SESSION['web_service_price_postfix'];
            if (
                $this->arParams['ACCOUNT'] == 'Y' &&
                $webServicePostfix
            ) {
                $price = ($prices[$webServicePostfix]['PRICE']) ?: $prices['RZ_0']['PRICE'];
                $this->arResult['ITEMS'][] = array(
                    'ARTICLE' => $item['PROPERTY_CML2_ARTICLE_VALUE'],
                    'NAME' => $item['NAME'],
                    'PRICE' => $price,
                );
            } else {
                if (!$prices[$currentPriceCode]['PRICE'] && !$prices['RZ_0']['PRICE'] && !$prices['RZ_1']['PRICE'] && !$prices['RZ_2']['PRICE']) {
                    continue;
                }
                $this->arResult['ITEMS'][] = array(
                    'ARTICLE' => $item['PROPERTY_CML2_ARTICLE_VALUE'],
                    'NAME' => $item['NAME'],
                    'PRICE_RZ_0' => ($prices['RZ_0']['PRICE']) ?: $prices[$currentPriceCode]['PRICE'],
                    'PRICE_RZ_1' => ($prices['RZ_1']['PRICE']) ?: $prices[$currentPriceCode]['PRICE'],
                    'PRICE_RZ_2' => ($prices['RZ_2']['PRICE']) ?: $prices[$currentPriceCode]['PRICE'],
                );
            }
        }
    }

    private function getRZ0PriceId()
    {
        if ($this->additionalPriceId) {
            return $this->additionalPriceId;
        }
        $cGroup = new \CCatalogGroup();
        $cCore = Core::getInstance();
        $priceTypes = $cCore->Region->getPrices()->priceTypes;
        $priceType = '';
        foreach ($priceTypes as $name) {
            if (strpos($name, 'RZ_') !== false) {
                $priceType = $name;
            }
        }
        if (!$priceType) {
            $this->additionalPriceId = 0;
            return 0;
        }
        if (strpos($priceType, 'RZ_0') !== false) {
            return $cCore->Region->getPrices()->additionalPriceId;
        }

        $dbPriceType = $cGroup->GetList(
            array('SORT' => 'ASC'),
            array('NAME' => substr($priceType, 0, strlen($priceType) - 1) . '0'),
            false,
            false,
            array('ID')
        );
        if ($item = $dbPriceType->Fetch()) {
            $this->additionalPriceId = $item['ID'];
            return $item['ID'];
        }
        $this->additionalPriceId = 0;
        return 0;
    }

    private function connectSections()
    {
        $core = Core::getInstance();

        $cSection = new \CIBlockSection();
        $filter = [
            'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
            'ACTIVE' => 'Y',
            '>ELEMENT_CNT' => 0,
        ];

        $select = [
            'ID',
            'NAME',
            'IBLOCK_SECTION_ID',
            'DEPTH_LEVEL',
            'ELEMENT_CNT',
            'CODE',
        ];

        $order = ['DEPTH_LEVEL' => 'ASC', 'SORT' => 'DESC', 'ID' => 'ASC'];

        $sections = [];
        $res = $cSection->GetList($order, $filter, true, $select);
        while ($item = $res->Fetch()) {
            if (!in_array($item['CODE'], Core::IBLOCK_SECTION_CODES_PROMO)) {

                $sections[$item['ID']] = $item;

            }
        }

        foreach ($sections as $item) {
            if ($item['DEPTH_LEVEL'] == 1) {
                $this->arResult['SECTIONS'][$item['ID']]['NAME'] = $item['NAME'];
            } elseif ($item['DEPTH_LEVEL'] == 2) {
                $this->arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['SECTIONS'][$item['ID']]['NAME'] = $item['NAME'];
            } elseif ($item['DEPTH_LEVEL'] == 3) {
                foreach ($this->arResult['SECTIONS'] as $sectionId => $section) {
                    foreach ($section['SECTIONS'] as $innerSectionId => $innerSection) {
                        if ($item['IBLOCK_SECTION_ID'] == $innerSectionId) {
                            $this->arResult['SECTIONS'][$sectionId]['SECTIONS'][$innerSectionId]['SECTIONS'][$item['ID']]['NAME'] = $item['NAME'];
                            $this->arResult['SECTIONS_LEVEL_3'][$item['ID']]['NAME'] = $item['NAME'];
                            break;
                        }
                    }
                }
            }
        }

    }

    /**
     * @param array $headers
     * @param array $items
     * @param array $requestData
     */
    private function displayExcel($headers, $items, $requestData)
    {
        $objPHPExcel = new PHPExcel();

        $fileName = ($this->arResult['SECTIONS_LEVEL_3'][$requestData['PRICE_LIST_SECTION_ID']]['NAME']) ?: Loc::getMessage('DEFAULT_FILE_NAME');
        $fileName = str_replace(',', '', $fileName);
        $fileName = str_replace(':', '', $fileName);
        $fileName = str_replace(' ', '_', $fileName);
        $fileName = trim(substr($fileName, 0, 30));

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle($fileName);

        $charNumber = ord('A');
        foreach ($headers as $item) {
            $sheet->setCellValue(chr($charNumber) . '1', $item);
            $charNumber++;
        }

        $j = 2;
        foreach ($items as $item) {
            $charNumber = ord('A');
            foreach ($headers as $code => $name) {
                $sheet->setCellValue(chr($charNumber) . $j, $item[$code]);
                $charNumber++;
            }
            $j++;
        }

        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
        ob_end_clean();
        $objWriter->save('php://output');

        exit();
    }

    /**
     * @param array $aVisibleHeaders
     * @param array $aRows
     */
    private static function displayTable($aVisibleHeaders, $aRows)
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;
        echo '
        <html>
        <head>
        <title>' . $APPLICATION->GetTitle() . '</title>
        <meta http-equiv="Content-Type" content="text/html; charset=' . LANG_CHARSET . '">
        <style>
            td {mso-number-format:\@;}
            .number0 {mso-number-format:0;}
            .number2 {mso-number-format:Fixed;}
        </style>
        </head>
        <body>';

        echo "<table border=\"1\">";
        echo "<tr>";

        foreach ($aVisibleHeaders as $headerProps) {
            echo '<td>';
            echo $headerProps;
            echo '</td>';
        }
        echo "</tr>";

        foreach ($aRows as $row) {
            echo "<tr>";
            foreach ($aVisibleHeaders as $id => $headerProps) {
                $val = htmlspecialcharsex($row[$id]);
                echo '<td';
                if (preg_match("/^([0-9.,]+)\$/", $val))
                    echo ' style="mso-number-format:0"';
                echo '>';
                echo($val <> "" ? $val : '&nbsp;');
                echo '</td>';
            }
            echo "</tr>";
        }

        echo "</table>";
        echo '</body></html>';
    }
}

