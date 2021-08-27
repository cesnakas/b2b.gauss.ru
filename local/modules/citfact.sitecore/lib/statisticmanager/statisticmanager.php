<?php

namespace Citfact\SiteCore\StatisticManager;

use Exception;
use Bitrix\Highloadblock\HighloadBlockTable;

class StatisticManager
{
    /**
     * Получить поле плана по месяцу
     *
     * @param int $month - номер месяца
     *
     * @return string
     */
    public static function getMonthFieldName(int $month) {
        switch($month) {
            case '1':
                $field = 'UF_JANUARY';
                break;
            case '2':
                $field = 'UF_FEBRUARY';
                break;
            case '3':
                $field = 'UF_MARCH';
                break;
            case '4':
                $field = 'UF_APRIL';
                break;
            case '5':
                $field = 'UF_MAY';
                break;
            case '6':
                $field = 'UF_JUNE';
                break;
            case '7':
                $field = 'UF_JULY';
                break;
            case '8':
                $field = 'UF_AUGUST';
                break;
            case '9':
                $field = 'UF_SEPTEMBER';
                break;
            case '10':
                $field = 'UF_OCTOBER';
                break;
            case '11':
                $field = 'UF_NOVEMBER';
                break;
            case '12':
                $field = 'UF_DECEMBER';
                break;
            default:
                LocalRedirect('/personal');
        }
        return $field;
    }

    /**
     * Возвращает данные периода 'от' и 'до'
     *
     * @param string $period
     *
     * @return array
     */
    public static function getFromAndTo($period) {
        $periodData = explode('_', $period);
        switch($periodData[0]) {
            case 'year':
                $result['FROM'] = '01.01.' . $periodData[1];
                $result['TO'] = '31.12.' . $periodData[1];
                break;
            case 'quarter':
                switch($periodData[1]) {
                    case '1':
                        $result['FROM'] = '01.01.' . $periodData[2];
                        $result['TO'] = '31.03.' . $periodData[2];
                        break;
                    case '2':
                        $result['FROM'] = '01.04.' . $periodData[2];
                        $result['TO'] = '30.06.' . $periodData[2];
                        break;
                    case '3':
                        $result['FROM'] = '01.07.' . $periodData[2];
                        $result['TO'] = '30.09.' . $periodData[2];
                        break;
                    case '4':
                        $result['FROM'] = '01.10.' . $periodData[2];
                        $result['TO'] = '31.12.' . $periodData[2];
                        break;
                }
                break;
            case 'month':
                $result['FROM'] = '01.' . $periodData[1] . '.' . $periodData[2];
                $result['TO'] = '31.' . $periodData[1] . '.' . $periodData[2];
                break;
            case 'from':
                $result['FROM'] = $periodData[1];
                $result['TO'] = $periodData[3];
                break;
            default:
                LocalRedirect('/personal');
        }
        return $result;
    }

    /**
     * Возвращает план за период
     *
     * @param string $period - период, за который запрашивается план
     * @return array
     *
     * @throws Exception если highloadBlock План-факт менеджера не будет найден
     */
    public static function getPlans(string $period) {
        $plans = [];
        $periodData = explode('_', $period);
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'PlanFactManager']
        ])->fetch();

        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();

            switch ($periodData[0]) {
                case 'year': // year_<год>
                    $dbResult = $hlClassName::getList([
                        'filter' => ['UF_YEAR' => $periodData[1]]
                    ]);
                    while ($arData = $dbResult->Fetch()) {
                        $plans[$arData['UF_CONTRAGENT']] = $arData['UF_PLAN_FOR_YEAR'];
                    }
                    break;
                case 'quarter': // quarter_<номер квартала>_<год>
                    switch($periodData[1]) {
                        case '1':
                            $field = 'UF_FIRST_QUARTER';
                            break;
                        case '2':
                            $field = 'UF_SECOND_QUARTER';
                            break;
                        case '3':
                            $field = 'UF_THIRD_QUARTER';
                            break;
                        case '4':
                            $field = 'UF_FOURTH_QUARTER';
                            break;
                        default:
                            LocalRedirect('/personal');
                    }
                    $dbResult = $hlClassName::getList([
                        'filter' => ['UF_YEAR' => $periodData[2]],
                        'select' => ['UF_CONTRAGENT', $field, 'UF_YEAR']
                    ]);
                    while ($arData = $dbResult->Fetch()) {
                        $plans[$arData['UF_CONTRAGENT']] = $arData[$field] ;
                    }
                    break;
                case 'month': // month_<номер месяца>_<год>
                    $field = self::getMonthFieldName($periodData[1]);
                    $dbResult = $hlClassName::getList([
                        'filter' => ['UF_YEAR' => $periodData[2]],
                        'select' => ['UF_CONTRAGENT', $field, 'UF_YEAR']
                    ]);
                    while ($arData = $dbResult->Fetch()) {
                        $plans[$arData['UF_CONTRAGENT']] = $arData[$field] ;
                    }
                    break;
                case 'from': // from_<дата>_to_<дата>
                    if ($periodData[2] == 'to') {
                        $monthFrom = date('m', strtotime($periodData[1])); // месяц начала периода
                        $monthTo = date('m', strtotime($periodData[3])); // месяц конца периода
                        $yearFrom = date('Y', strtotime($periodData[1])); // год начала периода
                        $yearTo = date('Y', strtotime($periodData[3])); // год конца периода

                        $arYears = [];
                        // собираем массив годов
                        for ($i = $yearFrom ; $i<=$yearTo; $i++) {
                            $arYears[] = $i;
                        }
                        if ($monthFrom === false || $monthTo === false || empty($arYears)) {
                            LocalRedirect('/personal');
                        } else {
                            $dataForYears = [];
                            $dbResult = $hlClassName::getList([
                                'filter' => ['UF_YEAR' => $arYears],
                                'select' => ['*']
                            ]);
                            while ($arData = $dbResult->Fetch()) {
                                for ($i = 1; $i <=12; $i++) {
                                    // собираем массив с данными по годам и месяцам
                                    $forMonthPlan = $arData[self::getMonthFieldName($i)];
                                    if (empty($forMonthPlan)) {
                                        $forMonthPlan = 0;
                                    }
                                    $dataForYears[$arData['UF_CONTRAGENT']][$arData['UF_YEAR']][$i] = $forMonthPlan;
                                }
                            }

                            foreach ($dataForYears as $xmlId => $kontragentData) {
                                $from = strtotime($periodData[1]);
                                $to = strtotime($periodData[3]);
                                $plans[$xmlId] = 0;
                                // складываем данные пока даты не сравняются
                                while ((date('Y', $from) != date('Y', $to)) || (date('m', $from) != date('m', $to))) {
                                    $plans[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)];
                                    $from = strtotime('+1 month', $from);
                                }
                                $plans[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)]; // последний месяц
                            }
                        }
                    } else {
                        LocalRedirect('/personal');
                    }
                    break;
            }
            return $plans;
        } else {
            throw new Exception('HighloadBlock \'PlanFactManager\' not found');
        }
    }

    /**
     * Возвращает план менеджера за период
     *
     * @param string $period - период, за который запрашивается план
     * @return array
     * @throws Exception если highloadBlock План-факт менеджера не будет найден
     */
    public static function getGeneralPlans(string $period) {
        $generalPlans = [];
        $periodData = explode('_', $period);
        $hlBlockGeneral = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'PlanFactGeneral']
        ])->fetch();

        if ($hlBlockGeneral) {
            $hlClassNameGeneral = (HighloadBlockTable::compileEntity($hlBlockGeneral))->getDataClass();
            switch ($periodData[0]) {
                case 'year': // year_<год>
                    $dbResult = $hlClassNameGeneral::getList([
                        'filter' => ['UF_YEAR' => $periodData[1]]
                    ]);
                    while ($arData = $dbResult->Fetch()) {
                        $generalPlans[$arData['UF_MANAGER']] = $arData['UF_PLAN_FOR_YEAR'];

                    }
                    break;
                case 'quarter': // quarter_<номер квартала>_<год>
                    switch($periodData[1]) {
                        case '1':
                            $field = 'UF_FIRST_QUARTER';
                            break;
                        case '2':
                            $field = 'UF_SECOND_QUARTER';
                            break;
                        case '3':
                            $field = 'UF_THIRD_QUARTER';
                            break;
                        case '4':
                            $field = 'UF_FOURTH_QUARTER';
                            break;
                        default:
                            LocalRedirect('/personal');
                    }
                    $dbResult = $hlClassNameGeneral::getList([
                        'filter' => ['UF_YEAR' => $periodData[2]],
                        'select' => ['UF_MANAGER', $field, 'UF_YEAR']
                    ]);
                    while ($arData = $dbResult->Fetch()) {
                        $generalPlans[$arData['UF_MANAGER']] = $arData[$field] ;
                    }
                    break;
                case 'month': // month_<номер месяца>_<год>
                    $field = self::getMonthFieldName($periodData[1]);
                    $dbResult = $hlClassNameGeneral::getList([
                        'filter' => ['UF_YEAR' => $periodData[2]],
                        'select' => ['UF_MANAGER', $field, 'UF_YEAR']
                    ]);
                    while ($arData = $dbResult->Fetch()) {
                        $generalPlans[$arData['UF_MANAGER']] = $arData[$field] ;
                    }
                    break;
                case 'from': // from_<дата>_to_<дата>
                    if ($periodData[2] == 'to') {
                        $monthFrom = date('m', strtotime($periodData[1])); // месяц начала периода
                        $monthTo = date('m', strtotime($periodData[3])); // месяц конца периода
                        $yearFrom = date('Y', strtotime($periodData[1])); // год начала периода
                        $yearTo = date('Y', strtotime($periodData[3])); // год конца периода

                        $arYears = [];
                        // собираем массив годов
                        for ($i = $yearFrom ; $i<=$yearTo; $i++) {
                            $arYears[] = $i;
                        }
                        if ($monthFrom === false || $monthTo === false || empty($arYears)) {
                            LocalRedirect('/personal');
                        } else {
                            $generalDataForYears = [];
                            $dbResult = $hlClassNameGeneral::getList([
                                'filter' => ['UF_YEAR' => $arYears],
                                'select' => ['*']
                            ]);
                            while ($arData = $dbResult->Fetch()) {
                                for ($i = 1; $i <=12; $i++) {
                                    // собираем массив с данными по годам и месяцам
                                    $generalForMonthPlan = $arData[self::getMonthFieldName($i)];
                                    if (empty($generalForMonthPlan)) {
                                        $generalForMonthPlan = 0;
                                    }
                                    $generalDataForYears[$arData['UF_MANAGER']][$arData['UF_YEAR']][$i] = $generalForMonthPlan;
                                }
                            }

                            foreach ($generalDataForYears as $xmlId => $managerData) {
                                $from = strtotime($periodData[1]);
                                $to = strtotime($periodData[3]);
                                $generalPlans[$xmlId] = 0;
                                // складываем данные пока даты не сравняются
                                while ((date('Y', $from) != date('Y', $to)) || (date('m', $from) != date('m', $to))) {
                                    $generalPlans[$xmlId] += $managerData[date('Y', $from)][date('n', $from)];
                                    $from = strtotime('+1 month', $from);
                                }
                                $generalPlans[$xmlId] += $managerData[date('Y', $from)][date('n', $from)]; // последний месяц
                            }
                        }
                    } else {
                        LocalRedirect('/personal');
                    }
                    break;
            }
            return $generalPlans;
        } else {
            throw new Exception('HighloadBlock \'PlanFactManager\' not found');
        }
    }

    /**
     * Возвращает план по контрагентам
     *
     * @param string $period - период запроса
     * @param array $kontragents - массив котнрагентов
     *
     * @return array
     *
     * @throws Exception если highloadBlock 'FaktOtchet' менеджера не будет найден
     */
    public static function getFacts($period, $kontragents) {
        // собираем xml_id контрагентов для фильтра
        $kontragentsXmlIds = [];
        foreach ($kontragents as $kontragent) {
            $kontragentsXmlIds[] = $kontragent['UF_XML_ID'];
        }

        $periodData = self::getFromAndTo($period);

        $facts = [];
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'FaktOtchet']
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            $arPlanData = [];
            $dbResult = $hlClassName::getList([
                'filter' => ['=UF_KONTRAGENT' => $kontragentsXmlIds],
                'select' => ['UF_SUMMA', 'UF_KONTRAGENT', 'UF_DATA']
            ]);
            while ($arData = $dbResult->Fetch()) {
                $date = new \Bitrix\Main\Type\DateTime($arData['UF_DATA'], 'Y-m-d');
                $year = $date->format('Y');
                $month = $date->format('n');
                $day = $date->format('d');
                if (empty($arPlanData[$arData['UF_KONTRAGENT']][$year][$month])) {
                    $arPlanData[$arData['UF_KONTRAGENT']][$year][$month][$day] = $arData['UF_SUMMA'];
                } else {
                    $arPlanData[$arData['UF_KONTRAGENT']][$year][$month][$day] += $arData['UF_SUMMA'];
                }
            }
            foreach ($arPlanData as $xmlId => $kontragentData) {
                $from = strtotime($periodData['FROM']);
                $to = strtotime($periodData['TO']);
                $facts[$xmlId] = 0;
                while(date('d.m.Y', $from) != date('d.m.Y', $to)) {
                    $facts[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)][date('d', $from)];
                    $from = strtotime('+1 day', $from);
                }
                $facts[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)][date('d', $from)]; // последний день
            }
        } else {
            throw new Exception('HighloadBlock \'FaktOtchet\' not found');
        }
        return $facts;
    }

    /**
     * Возвращает просроченную дебиторскую задолженность по контрагентам
     *
     * @param string $period
     * @param array $kontragents
     * @param bool $defaultPeriod
     *
     * @return array
     *
     * @throws Exception если highloadBlock 'Дебиторская задолженность' не будет найден
     */
    public static function getPdz($period, $kontragents, $defaultPeriod) {
        $pzd = [];
        $minDate = new \Bitrix\Main\Type\DateTime();
        $kontragentsXmlIds = [];
        foreach ($kontragents as $kontragent) {
            $kontragentsXmlIds[] = $kontragent['UF_XML_ID'];
        }

        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'DebitorskayaZadolzhennost']
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            $dbResult = $hlClassName::getList([
                'filter' => ['UF_KONTRAGENT' => $kontragentsXmlIds],
                'order' => ['UF_DATAOPLATY' => 'ASC'],
                'select' => ['UF_SUMMAPROSROCHENO', 'UF_KONTRAGENT', 'UF_DATAOPLATY']
            ]);

            while ($arData = $dbResult->Fetch()) {
                $date = new \Bitrix\Main\Type\DateTime($arData['UF_DATAOPLATY'], 'Y-m-d');

                if($date < $minDate)
                    $minDate = $date;

                $year = $date->format('Y');
                $month = $date->format('n');
                $day = $date->format('d');
                if (empty($pdzData[$arData['UF_KONTRAGENT']][$year][$month])) {
                    $pdzData[$arData['UF_KONTRAGENT']][$year][$month][$day] = $arData['UF_SUMMAPROSROCHENO'];
                } else {
                    $pdzData[$arData['UF_KONTRAGENT']][$year][$month][$day] += $arData['UF_SUMMAPROSROCHENO'];
                }
            }

            if($defaultPeriod){
                $periodData = ['FROM' => $minDate->format('d.m.Y'), 'TO' => date('d.m.Y')];
            } else {
                $periodData = self::getFromAndTo($period);
            }

            foreach ($pdzData as $xmlId => $kontragentData) {
                $from = strtotime($periodData['FROM']);
                $to = strtotime($periodData['TO']);
                $pzd[$xmlId] = 0;
                while(date('d.m.Y', $from) != date('d.m.Y', $to)) {
                    $pzd[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)][date('d', $from)];
                    $from = strtotime('+1 day', $from);
                }
                $pzd[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)][date('d', $from)]; // последний день
            }


            return $pzd;
        } else {
            throw new Exception('HighloadBlock \'DebitorskayaZadolzhennost\' not found');
        }
    }

    /**
     * Получение дебиторской задолженности
     *
     * @param array $kontragents - массив контрагентов
     * @param string $period - массив контрагентов
     *
     * @return array
     *
     *  @throws Exception если highloadBlock 'Дебиторская задолженность' менеджера не будет найден
     */
    public static function getDz($period, $kontragents, $defaultPeriod) {
        $kontragentsXmlIds = [];
        foreach ($kontragents as $kontragent) {
            $kontragentsXmlIds[] = $kontragent['UF_XML_ID'];
        }

        $dz = [];
        $minDate = new \Bitrix\Main\Type\DateTime();

        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'DebitorskayaZadolzhennost']
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            $dbResult = $hlClassName::getList([
                'filter' => ['UF_KONTRAGENT' => $kontragentsXmlIds],
                'select' => ['UF_SUMMA', 'UF_KONTRAGENT', 'UF_DATA']
            ]);

            while ($arData = $dbResult->Fetch()) {
                $date = new \Bitrix\Main\Type\DateTime($arData['UF_DATA'], 'd.m.Y');

                if($date < $minDate)
                    $minDate = $date;

                $year = $date->format('Y');
                $month = $date->format('n');
                $day = $date->format('d');
                if (empty($dzData[$arData['UF_KONTRAGENT']][$year][$month])) {
                    $dzData[$arData['UF_KONTRAGENT']][$year][$month][$day] = $arData['UF_SUMMA'];
                } else {
                    $dzData[$arData['UF_KONTRAGENT']][$year][$month][$day] += $arData['UF_SUMMA'];
                }
            }

            if($defaultPeriod){
                $periodData = ['FROM' => $minDate->format('d.m.Y'), 'TO' => date('d.m.Y')];
            } else {
                $periodData = self::getFromAndTo($period);
            }

            foreach ($dzData as $xmlId => $kontragentData) {
                $from = strtotime($periodData['FROM']);
                $to = strtotime($periodData['TO']);
                $dz[$xmlId] = 0;
                while(date('d.m.Y', $from) != date('d.m.Y', $to)) {
                    $dz[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)][date('d', $from)];
                    $from = strtotime('+1 day', $from);
                }
                $dz[$xmlId] += $kontragentData[date('Y', $from)][date('n', $from)][date('d', $from)]; // последний день
            }

            return $dz;
        } else {
            throw new Exception('HighloadBlock \'DebitorskayaZadolzhennost\' not found');
        }
    }
}