<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\User\UserRepository;
use Citfact\SiteCore\ContragentHelper\ContragentHelper;
use Citfact\SiteCore\UserDataManager\UserDataManager;

Loc::loadMessages(__FILE__);

class PlanFactManagerComponent extends \CBitrixComponent
{
    /**
     * {@inheritdoc}
     */

    public function executeComponent()
    {

        $request = Context::getCurrent()->getRequest();

        $this->getPlanFactData($request);

        $this->IncludeComponentTemplate();
    }

    public function getPlanFactData($request)
    {
        if(!empty($this->arParams['XML_ID'])){
            $contragent = $this->arParams['XML_ID'];
            UserDataManager::checkContragentXmlIdByStructure($this->arParams['XML_ID']);

        } else {
            $contragent = UserDataManager::getUserContragentXmlID();
        }
        if (!empty($request['year']))
            $year = $request['year'];
        else
            $year = date('Y');

        $this->getPlanData($contragent, $year);
        $this->getFactData($contragent, $year);
        $this->arResult['contragent'] = $contragent;
        $this->arResult['year'] = $year;

        $activeTab = false;
        if (!empty($request['active_tab'])) {
            $activeTab = $request['active_tab'];
        }

        $this->arResult['active_tab'] = $activeTab;
    }

    public function getPlanData($contragent, $year)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_PLAN_FACT_MANAGER);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $data = [];
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => [
                "UF_CONTRAGENT" => $contragent,
            ],
        ));
        while ($row = $rsData->fetch()) {
            $data[] = $row;
            $this->arResult['PLAN_DATA_FOR_YEAR'][$row['UF_YEAR']] = (float)$row['UF_PLAN_FOR_YEAR'];

            $this->arResult['PLAN_DATA_BY_QUARTERS'][$row['UF_YEAR']] = [
                1 => (float)$row['UF_FIRST_QUARTER'],
                2 => (float)$row['UF_SECOND_QUARTER'],
                3 => (float)$row['UF_THIRD_QUARTER'],
                4 => (float)$row['UF_FOURTH_QUARTER'],
            ];
        }
    }

    public function getFactData($contragent, $year)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_PLAN_FACT);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $dateFrom = new \Bitrix\Main\Type\DateTime("01.01.$year 00:00:00");
        $dateTo = new \Bitrix\Main\Type\DateTime("31.12.$year 23:59:59");
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => [
                "UF_KONTRAGENT" => $contragent,
//                "<UF_DATA" => $dateFrom,
//                ">UF_DATA" => $dateTo,
            ],
        ));

        $data = [];
        while ($el = $rsData->fetch()) {
            $data[] = $el;
        }
        $this->processFactData($data);
    }

    //обработка данных, приведение к виду ['год']['номер_месяца'=> 'данные']
    function processFactData($data)
    {
        $processedData = [];
        foreach ($data as $key => $row) {
            $timestamp = strtotime($row['UF_DATA']);
            $month = (integer)date('m', $timestamp);
            $year = date('Y', $timestamp);
            $processedData['MONTHS'][$month] = (float) $row['UF_SUMMA'];

            if ($key === count($data) - 1) {
                $this->arResult['FACT_DATA_FOR_YEAR'][$year] = (float) array_sum($processedData['MONTHS']);

                ksort($processedData['MONTHS']);

                $this->arResult['FACT_DATA_BY_QUARTERS'][$year] = [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                ];

                foreach ($processedData['MONTHS'] as $num => $sum) {

                    if ($num >= 1 && $num <= 3) {
                        $this->arResult['FACT_DATA_BY_QUARTERS'][$year][1] += (float) $sum;
                    } elseif ($num >= 4 && $num <= 6) {
                        $this->arResult['FACT_DATA_BY_QUARTERS'][$year][2] += (float) $sum;
                    } elseif ($num >= 7 && $num <= 9) {
                        $this->arResult['FACT_DATA_BY_QUARTERS'][$year][3] += (float) $sum;
                    } elseif ($num >= 10 && $num <= 12) {
                        $this->arResult['FACT_DATA_BY_QUARTERS'][$year][4] += (float) $sum;
                    }

                }

                $this->arResult['FACT_DATA_BY_MONTHS'][$year] = $processedData['MONTHS'];

            }
        }

        $this->fillWithZeroes();
    }

    //заполняет данные по месяцам нулями, если данные отсутсвуют. На случай если данные есть не по всем месяцам
    function fillWithZeroes()
    {
        //заполняем месяцы
        foreach ($this->arResult['FACT_DATA_BY_MONTHS'] as $year => $value) {
            foreach (range(1, 12) as $monthNumber) {
                if (empty($this->arResult['FACT_DATA_BY_MONTHS'][$year][$monthNumber])) {
                    $this->arResult['FACT_DATA_BY_MONTHS'][$year][$monthNumber] = 0;
                }
            }
            ksort($this->arResult['FACT_DATA_BY_MONTHS'][$year]);
        }

        //заполняем кварталы
        foreach ($this->arResult['FACT_DATA_BY_QUARTERS'] as $year => $value) {
            foreach (range(1, 4) as $quartNumber) {
                if (empty($this->arResult['FACT_DATA_BY_QUARTERS'][$year][$quartNumber])) {
                    $this->arResult['FACT_DATA_BY_QUARTERS'][$year][$quartNumber] = 0;
                }
            }
            ksort($this->arResult['FACT_DATA_BY_QUARTERS'][$year]);
        }
    }


}


