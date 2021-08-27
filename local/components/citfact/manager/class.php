<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Citfact\SiteCore\UserDataManager\UserDataManager;
use Citfact\SiteCore\StatisticManager\StatisticManager;
use Citfact\SiteCore\Core;
use Citfact\Tools\Tools;

class MainProfileComponent extends CBitrixComponent
{
    const DIRECTOR = 'Руководитель';
    const RM = 'РМ';
    const TM = 'ТМ';

    private $period, $defaultPeriod, $plans, $generalPlans, $mapXmlIdManager, $mapIdXmlManager, $stackManager, $managersName, $calculateValuePlanBoss = [];
    private $setPlanClients = [], $setFactClients = [], $setDzClients = [], $setPdzClients = [], $modByKontragent = [];

    /**
     * Реализует жизненный цикл компонента
     *
     * @return void
     * @throws Exception
     */
    public function executeComponent()
    {
        Loader::includeModule('highloadblock');

        $managerId = UserDataManager::getIdManagerByUserId(CUser::GetID());
        $this->arResult['CURRENT_MANAGER'] = $managerId;
        // получение текущего менеджера
        $managerXmlId = UserDataManager::getManagerXmlId($managerId);

        // выбираем период для отображения фактически данных, по умолчанию текущий месяц
        $request = Tools::requestSpecialChars($_REQUEST);

        if (!empty($request['period'])) {
            $period = $request['period'];
        } else {
            $period = $this->arParams['PERIOD'] ?? 'month_' . date('m') . '_' . date('Y');
        }

        $defaultPeriod = false;
        $this->arResult['DEFAULT_PERIOD'] = $defaultPeriod;

        /**
         * Начало блока
         * Здесь в зависимости от установленного фильтра получаем подчиненных и иерархию их построения
         */
        // Руководитель
        if (!empty($request['director'])) {
            $directorFilter = $request['director'];
            $structureManagers[] = ['UF_ID_MANAGER' => $directorFilter, 'sub' => UserDataManager::getStructureManagers($directorFilter)];
        }
        // Менеджер
        if (!empty($request['managers'])) {
            $structureManagers = [];
            $managerFilter = $request['managers'];
            $structureManagers[] = ['UF_ID_MANAGER' => $managerFilter, 'sub' => UserDataManager::getStructureManagers($managerFilter)];
        }
        // ТМ
        if (!empty($request['managers-tm'])) {
            $structureManagers = [];
            $managerTMFilter = $request['managers-tm'];
            $structureManagers[] = ['UF_ID_MANAGER' => $managerTMFilter];
        }

        $structureManagersInfo = UserDataManager::getStructureManagersInfo($managerId);

        if (!isset($structureManagers) || empty($structureManagers)) {
            $structureManagersCurrent = UserDataManager::getStructureManagers($managerId);
            $this->stackManager = UserDataManager::getStackManager($managerId);
            if (empty($structureManagersCurrent)) {
                $structureManagers[] = ['UF_ID_MANAGER' => $managerId];
                $structureManagersInfo = UserDataManager::getStructureManagersInfo($managerId, false, $structureManagers);
            } else {
                $structureManagers[] = ['UF_ID_MANAGER' => $managerId, 'sub' => $structureManagersCurrent];
            }
        }
        /**
         * Конец блока
         */

        /**
         * Поиск по клиентам
         */
        if (!empty($request['q'])) {
            $this->arResult['CLIENT_FILTER'] = ['%=UF_NAME' => '%'.htmlspecialcharsBack($request['q']).'%'];
        }
        $this->arResult['FOUND_CLIENTS'] = [];
        // Имена менеджеров
        if (!empty($structureManagersInfo)) {
            $this->managersName = array_combine(array_column($structureManagersInfo, 'UF_XML_ID'), array_column($structureManagersInfo, 'UF_NAME'));
        } else {
            $this->managersName[$managerXmlId] = \CUser::GetFullName();
        }

        // получение плана
        $this->plans = StatisticManager::getPlans($period);

        //получение общего плана менеджера
        $this->generalPlans = StatisticManager::getGeneralPlans($period);

        $this->arResult['DEFAULT_PERIOD'] = $defaultPeriod;

        $this->arResult['ALL_DZ'] = 0;
        $this->arResult['ALL_PDZ'] = 0;
        $this->arResult['ALL_FACT'] = 0;
        $this->arResult['ALL_PLAN'] = 0;

        $this->arResult['EXCLUDE_KONTRAGENT'] = [];
        $this->arResult['EXCLUDE_MANAGER'] = [];
        $this->period = $period;
        $this->defaultPeriod = $defaultPeriod;
        $this->mapXmlIdManager = $this->getMapXmlIdManager($structureManagersInfo);
        $this->mapIdXmlManager = $this->getMapIdXmlManager($structureManagersInfo);

        /**
         * Получение id менеджеров и клиентов для исключения в расчетах
         */
        if (isset($request['excludeKontragent']) && !empty($request['excludeKontragent'])) {
            $this->arResult['EXCLUDE_KONTRAGENT'] = $request['excludeKontragent'];
        }
        if (isset($request['excludeManager']) && !empty($request['excludeManager'])) {
            $this->arResult['EXCLUDE_MANAGER'] = $request['excludeManager'];
        }

        // Массив данных
        $arResult['ITEMS'] = $this->recursionResult($structureManagers);

        $arResult['ITEMS'] = $this->setCalculateManagersNoSetForArray($arResult['ITEMS']);
        $arResult['ITEMS'] = $this->setCalculateManagersNoSetForArray($arResult['ITEMS']);
        $arResult['ITEMS'] = $this->setCalculateManagersNoSetForArray($arResult['ITEMS']);
        $arResult['ITEMS'] = $this->setCalculateManagersNoSetForArray($arResult['ITEMS']);
        $arResult['ITEMS'] = $this->setCalculateManagersNoSetForArray($arResult['ITEMS']);
//        pre($arResult['ITEMS']);
//        pre($this->modByKontragent);

        $arResult['ITEMS'] = $this->setCalculateManagersSaldo($arResult['ITEMS']);
        $arResult['ITEMS'] = $this->setCalculateManagerExclude($arResult['ITEMS']);
        if (!empty($this->arResult['CLIENT_FILTER'])) {
            $arResult['ITEMS'] = $this->excludeManagerNoClient($arResult['ITEMS']);
        }
        $this->setSummAll($arResult['ITEMS']);

        $arResult['ITEMS'] = $this->sortResult($arResult['ITEMS'], $this->arParams['SORT'], $this->arParams['DIR'], true);
        $this->arResult['ITEMS'] = $arResult['ITEMS'];

        // получение годов, за которые есть данные (для фильтра)
        $this->arResult['AVAILABLE_YEARS'] = $this->getAvailableYears($period);

        // получение ссылок для статистики на текущий год, квартал, месяц
        $this->arResult['CURRENT'] = $this->getLinksForCurrentPeriods();

        // получение описания текущего периода
        $this->arResult['PERIOD_DESC'] = $this->getPeriodDescription($period);

        // получение текущего периода
        $this->arResult['SELECTED_PERIOD'] = $this->getSelectedPeriod($period);

        $levels = $this->getLevelManager();
        $levelManagers = [];
        foreach ($structureManagersInfo as $manager) {
            if ($manager['ID'] == $managerId) {
                continue;
            }
            if ($manager['UF_LEVEL_MANAGER'] == $levels[0]) {
                $levelManagers[0][] = $manager;
            }
            if ($manager['UF_LEVEL_MANAGER'] == $levels[1]) {
                $levelManagers[1][] = $manager;
            }
            if ($manager['UF_LEVEL_MANAGER'] == $levels[2]) {
                $levelManagers[2][] = $manager;
            }
        }
        $this->arResult['USER_MANAGERS'] = $levelManagers;

        $this->arResult['DIRECTOR_FILTER'] = isset($directorFilter) ? $directorFilter : '';
        $this->arResult['MANAGER_FILTER'] = isset($managerFilter) ? $managerFilter : '';
        $this->arResult['MANAGER_TM_FILTER'] = isset($managerTMFilter) ? $managerTMFilter : '';
        // подключение шаблона компонента
        $request = Bitrix\Main\Context::getCurrent()->getRequest();
        if ($request->isAjaxRequest()) {
            \Bitrix\Iblock\Component\Base::sendJsonAnswer([
                'items' => $this->arResult['ITEMS'],
                'all' => [
                    'ALL_DZ' => $this->arResult['ALL_DZ'],
                    'ALL_PDZ' => $this->arResult['ALL_PDZ'],
                    'ALL_FACT' => $this->arResult['ALL_FACT'],
                    'ALL_PLAN' => $this->arResult['ALL_PLAN'],
                ]
            ]);
        }
        $this->includeComponentTemplate();

        return $this->arResult;
    }

    /**
     * Сбор массива $arResult
     *
     * @param array $kontragents - список контрагентов
     * @param array $countManagerNoSet - кол-во непосредственно подчиненных менеджеров у который не проставлен план
     * @param array $plans - план по контрагентам
     * @param array $generalPlans - общий план менеджера
     * @param string $managerXmlId - xml_ID текущего менеджера
     * @param array $facts - факт по контрагентам
     * @param array $pdz - просроченная дебиторская задолженность по контрагентам
     * @param array $dz - дебиторская задолженность по контрагентам
     * @param bool $excludeManager - менеджер, а значит и его контрагенты, не участвуют в расчетах
     *
     * @return array
     */
    public function getResult(array $kontragents, $countManagerNoSet, string $managerXmlId, array $plans, array $generalPlans, array $facts, array $pdz, array $dz, bool $excludeManager) {
        $result = [];
        // Получаем общий план менеджера
        $allPlan = $generalPlans[$managerXmlId] ?? 0;
        $allPdz = 0;
        $allFact = 0;
        $allDz = 0;
        $counterKontrAgentNoSetPlan= 0;
        $sum = 0;

        $result['ITEMS'] = [];
        foreach ($kontragents as $key => $kontragent) {
            $item['NAME'] = $kontragent['UF_NAME'];
            $item['UF_XML_ID'] = $kontragent['UF_XML_ID'];

            $item['EXCLUDE'] = false;

            if ($plans[$kontragent['UF_XML_ID']]) {
                $item['PLAN'] = $plans[$kontragent['UF_XML_ID']];
            } else {
                $item['PLAN'] = 0;
            }
            if($item['PLAN'] == 0){
                $counterKontrAgentNoSetPlan++;
            } else {
                $sum  += $item['PLAN'];
            }

            if ($facts[$kontragent['UF_XML_ID']]) {
                $item['FACT'] = $facts[$kontragent['UF_XML_ID']];
            } else {
                $item['FACT'] = 0;
            }
            if ($this->checkExclude($kontragent['ID'], $this->arResult['EXCLUDE_KONTRAGENT'])) {
                $item['EXCLUDE'] = true;
            }
            $allFact += $item['FACT'];

            if ($item['PLAN'] == 0) {
                $item['PERCENT'] = 0;
            } else {
                $item['PERCENT'] = round($item['FACT'] * 100 / $item['PLAN']);
            }

            if ($item['PERCENT'] <= 25) {
                $item['COLOR'] = 'red'; // красный
            } elseif ($item['PERCENT'] <= 50) {
                $item['COLOR'] = '#f7971d'; // оранжевый
            } elseif ($item['PERCENT'] <= 75) {
                $item['COLOR'] = 'yellow'; // желтый
            } else {
                $item['COLOR'] = '#40d24a'; // зеленый
            }

            $item['USE_PORTAL'] = $kontragent['USE_PORTAL'];

            $item['PDZ'] = $pdz[$kontragent['UF_XML_ID']] ?? 0;
            $allPdz += $item['PDZ'];

            $item['DZ'] = $dz[$kontragent['UF_XML_ID']] ?? 0;
            $allDz += $item['DZ'];
            $item['ID'] = $kontragent['ID'];
            $item['MENEDZHER'] = $kontragent['UF_MENEDZHER'];

            // ссылка на детальную страницу контрагента
            $item['URL'] = $this->arParams['FOLDER'] . $item['UF_XML_ID'] . '/';
            $result['ITEMS'][] = $item;
        }

        //Рассчитываем автоплан для контрагентов, у которых не проставлен план
        $autoPlan = 0;
        $allCountNoSetPlan = $countManagerNoSet + $counterKontrAgentNoSetPlan;
        if ($allCountNoSetPlan > 0) {
            $mod = ($allPlan - $sum) % $allCountNoSetPlan;
            $autoPlan = ($allPlan - $sum) / $allCountNoSetPlan;
        }

        $sumRealAutoPlan = 0;
        $sumRealAutoPlanWithoutMod = 0;
        $iteration = 0;
        $countItems = count($result['ITEMS']);
        foreach ($result['ITEMS'] as $key =>$val) {
            $iteration++;
            if($result['ITEMS'][$key]['PLAN'] == 0) {
                if ($mod > 0 && $countItems == $iteration) {
                    $result['ITEMS'][$key]['AUTOPLAN'] = $autoPlan;
                    $sumRealAutoPlan += $mod;
                } else {
                    $result['ITEMS'][$key]['AUTOPLAN'] = $autoPlan;
                    $sumRealAutoPlan += $autoPlan;
                }
                $sumRealAutoPlanWithoutMod += $autoPlan;
                if ($result['ITEMS'][$key]['AUTOPLAN'] != 0) {
                    $result['ITEMS'][$key]['PERCENT'] = round($result['ITEMS'][$key]['FACT'] / $result['ITEMS'][$key]['AUTOPLAN'] * 100);
                }
            }
            $this->setPlanClients[$val['ID']] = $result['ITEMS'][$key]['PLAN'] == 0 ? $result['ITEMS'][$key]['AUTOPLAN'] : $result['ITEMS'][$key]['PLAN'];
            $this->setFactClients[$val['ID']] = $result['ITEMS'][$key]['FACT'];
            $this->setDzClients[$val['ID']] = $result['ITEMS'][$key]['DZ'];
            $this->setPdzClients[$val['ID']] = $result['ITEMS'][$key]['PDZ'];
        }

        if ($allCountNoSetPlan > 0) {
            $this->modByKontragent[$managerXmlId] = $mod;
        }

        $result['ALL_PLAN'] = $allPlan;
        $result['ALL_PDZ'] = $allPdz;
        $result['ALL_DZ'] = $allDz;
        $result['ALL_FACT'] = $allFact;
        $result['ALL_AUTOPLAN'] = ($allPlan-$sum);

        if ($allPlan != 0) {
            $result['ALL_PERCENT'] = round($allFact * 100 / $allPlan);
        } else {
            $result['ALL_PERCENT'] = 0;
        }

        if ($result['ALL_PERCENT'] <= 25) {
            $result['COLOR_ALL'] = 'red'; // красный
        } elseif ($result['ALL_PERCENT'] <= 50) {
            $result['COLOR_ALL'] = '#f7971d'; // оранжевый
        } elseif ($result['ALL_PERCENT'] <= 75) {
            $result['COLOR_ALL'] = 'yellow'; // желтый
        } else {
            $result['COLOR_ALL'] = '#40d24a'; // зеленый
        }
        return $result;
    }

    /**
     * Получить года, за которые есть данные
     * @param $period
     *
     * @return array
     *
     * @throws Exception если highloadBlock План-факт менеджера не будет найден
     */
    public function getAvailableYears($period) {
        $periodData = explode('_', $period);
        if ($periodData[0] == 'year') {
            $selected = $periodData[1];
        } elseif ($periodData[0] == 'from') {
            $selected = date('Y', strtotime($periodData[1]));
        } else {
            $selected = $periodData[2];
        }
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'PlanFactManager']
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            $years = [];
            $dbResult = $hlClassName::getList([
                'select' => ['UF_YEAR'],
            ]);
            while ($arData = $dbResult->Fetch()) {
                if ($arData['UF_YEAR'] == $selected) {
                    $years[$arData['UF_YEAR']] = 1;
                } else {
                    $years[$arData['UF_YEAR']] = 0;
                }
            }
            krsort($years);
            return $years;
        } else {
            throw new Exception('HighloadBlock \'PlanFactManager\' not found');
        }
    }

    /**
     * Формирует ссылки на статистику за текущий год, квартал и месяц
     *
     * @return array
     */
    public function getLinksForCurrentPeriods() {
        global $APPLICATION;
        $current['YEAR'] =  $APPLICATION->GetCurPageParam('period=year_' . date('Y'), ['period'], false);
        $current['MONTH'] =  $APPLICATION->GetCurPageParam('period=month_' . date('n') . '_' . date('Y'), ['period'], false);
        if (date('n') <= 3) {
            $quarter = 1;
        } elseif (date('n') <= 6) {
            $quarter = 2;
        } elseif (date('n') <= 9) {
            $quarter = 3;
        } else {
            $quarter = 4;
        }
        $current['QUARTER'] =  $APPLICATION->GetCurPageParam('period=quarter_' . $quarter . '_' . date('Y'), ['period'], false);
        return $current;
    }

    /**
     * Выбранный периода
     *
     * @param string $period
     *
     * @return string
     */
    public function getSelectedPeriod($period) {
        $periodData = explode('_', $period);
        $selected = 'month';
        switch ($periodData[0]) {
            case 'year':
                $selected = 'year';
                break;
            case 'quarter':
                $selected = 'quarter';
                break;
            case 'month':
                $selected = 'month';
                break;
        }

        return $selected;
    }

    /**
     * Описание периода
     *
     * @param string $period
     *
     * @return string
     */
    public function getPeriodDescription($period) {
        $periodData = explode('_', $period);
        switch ($periodData[0]) {
            case 'year': // year_<год>
                $desc = $periodData[1] . ' - ' . ($periodData[1] + 1);
                break;
            case 'quarter': // quarter_<номер квартала>_<год>
                switch($periodData[1]) {
                    case '1':
                        $desc = '01.01.' . $periodData[2] . ' - 31.03.' . $periodData[2];
                        break;
                    case '2':
                        $desc = '01.04.' . $periodData[2] . ' - 30.06.' . $periodData[2];
                        break;
                    case '3':
                        $desc = '01.07.' . $periodData[2] . ' - 30.09.' . $periodData[2];
                        break;
                    case '4':
                        $desc = '01.10.' . $periodData[2] . ' - 31.12.' . $periodData[2];
                        break;
                }
                break;
            case 'month': // month_<номер месяца>_<год>
                // кол-во дней в месяце
                switch($periodData[1]) {
                    case '2':
                        $days = 28;
                        break;
                    case '1':
                    case '3':
                    case '5':
                    case '7':
                    case '8':
                    case '10':
                    case '12':
                        $days = 31;
                        break;
                    case '4':
                    case '6':
                    case '9':
                    case '11':
                        $days = 30;
                        break;
                }
                $periodData[1] = (int)$periodData[1];
                $month = $periodData[1] >= 10 ? $periodData[1] : '0' . $periodData[1]; // добавление первого 0
                $desc = '01.' . $month . '.' . $periodData[2] . ' - ' . $days . '.' . $month . '.' . $periodData[2];
                break;
            case 'from': // from_<дата>_to_<дата>
                $desc = $periodData[1] . ' - '  . $periodData[3];
                break;
        }
        return $desc;
    }

    /**
     * Сортировка таблицы с результатом
     *
     * @param array $result - неотсортированный массив
     * @param string $sort - поле сортировки
     * @param string $dir - направление сортировки
     * @param bool $sortManager
     *
     * @return array
     */
    public function sortResult(array $result, $sort, $dir, bool $sortManager = false) {
        if (!$dir) {
            // Сортировка по умолчанию
            $dir = 'desc';
        }
        // Сортировка по умолчанию - по плану
        if (!$sort && !$sortManager) {
            $field = 'PLAN';
        } else {
            $field = 'ALL_PLAN';
        }
        if ($sortManager) {
            switch ($sort) {
                case 'plan':
                    $field = 'ALL_PLAN';
                    break;
                case 'fact':
                    $field = 'ALL_FACT';
                    break;
                case 'percent':
                    $field = 'ALL_PERCENT';
                    break;
                case 'pdz':
                    $field = 'ALL_PDZ';
                    break;
                default:
                    $field = 'ALL_PLAN';
            }
        } else {
            switch ($sort) {
                case 'client':
                    $field = 'NAME';
                    break;
                case 'portal':
                    $field = 'USE_PORTAL';
                    break;
                case 'plan':
                    $field = 'PLAN';
                    break;
                case 'fact':
                    $field = 'FACT';
                    break;
                case 'percent':
                    $field = 'PERCENT';
                    break;
                case 'pdz':
                    $field = 'PDZ';
                    break;
                default:
                    $field = 'PLAN';
            }
        }

        $copyForSort = [];
        foreach($result as $key => $item){
            $copyForSort[$key] = $item[$field];
        }
        array_multisort($copyForSort, $result);

        if ($dir == 'desc') {
            $result = array_reverse($result);
        }
        return $result;
    }

    /**
     * Возвращает ID должности
     *
     * @return array
     *
     * @throws Exception если highload-блок "LevelManager" не будет найден
     */
    public function getLevelManager() {
        $levels = [];
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => Core::HLBLOCK_CODE_LEVEL_MANAGER]
        ])->fetch();

        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();

            $dbResult = $hlClassName::getList([
                'select' => ['ID', 'UF_NAME']
            ]);
            while ($arData = $dbResult->Fetch()) {
                if ($arData['UF_NAME'] == self::DIRECTOR) {
                    $levels[0] = $arData['ID'];
                }
                if ($arData['UF_NAME'] == self::RM) {
                    $levels[1] = $arData['ID'];
                }
                if ($arData['UF_NAME'] == self::TM) {
                    $levels[2] = $arData['ID'];
                }
            }

            return $levels;
        } else {
            throw new Exception('HighloadBlock \'LevelManager\' not found');
        }
    }

    /**
     * Сбор данных по конкретному менеджеру
     * @param $manager
     * @param $period
     * @param $defaultPeriod
     * @param $plans
     * @param $generalPlans
     * @param $managerId
     *
     * @return mixed
     * @throws Exception
     */
    private function getTempResult($manager, $period, $defaultPeriod, $plans, $generalPlans, $managerId, $countManagerNoSet = 0)
    {
        // получение контрагентов
        $kontragents = UserDataManager::getKontragents([$manager]);
        if ($this->arResult['CLIENT_FILTER']) {
            $clientsByFilter = UserDataManager::getKontragents([$manager], $this->arResult['CLIENT_FILTER'], ['ID']);
            if (!empty($clientsByFilter)) {
                $this->arResult['FOUND_CLIENTS'] = array_merge($this->arResult['FOUND_CLIENTS'], $clientsByFilter);
            }
        }
        // получение факта
        $facts = StatisticManager::getFacts($period, $kontragents);

        // получение просроченной дебиторской задолженности
        $pdz = StatisticManager::getPdz($period, $kontragents, $defaultPeriod);

        // получение дебиторской задолженности
        $dz = StatisticManager::getDz($period, $kontragents, $defaultPeriod);

        // сбор массива результатов
        if ($this->checkExclude($managerId, $this->arResult['EXCLUDE_MANAGER'])) {
            $arResult = $this->getResult($kontragents, $countManagerNoSet, $manager, $plans, $generalPlans, $facts, $pdz, $dz, true);
            $arResult['EXCLUDE'] = true;
        } else {
            $arResult = $this->getResult($kontragents, $countManagerNoSet, $manager, $plans, $generalPlans, $facts, $pdz, $dz, false);
        }

        // сортировка результатов
        $arResult['ITEMS'] = $this->sortResult($arResult['ITEMS'], $this->arParams['SORT'], $this->arParams['DIR']);

        $arResult['NAME'] = isset($this->managersName[$manager]) ? $this->managersName[$manager] : '';

        $arResult['ID'] = $managerId;

        return $arResult;
    }

    /**
     * @param $structureManagers
     *
     * @return array
     * @throws Exception
     */
    public function recursionResult($structureManagers) {
        $period = $this->period;
        $defaultPeriod = $this->defaultPeriod;
        $plans = $this->plans;
        $generalPlans = $this->generalPlans;

        $result = [];
        foreach ($structureManagers as $manager) {
            if (isset($manager['UF_ID_SUB_MANAGER']) && !empty($manager['UF_ID_SUB_MANAGER'])) {
                $managerId = $manager['UF_ID_SUB_MANAGER'];
            } else {
                $managerId = $manager['UF_ID_MANAGER'];
            }
            $managerXml = array_search($managerId, $this->mapXmlIdManager);

            $result[$managerXml] = $this->getTempResult($managerXml, $period, $defaultPeriod, $plans, $generalPlans, $managerId);

            if (!empty($manager['sub'])) {
                $result[$managerXml]['SUB'] = $this->recursionResult($manager['sub']);
                $result[$managerXml]['SUB'] = $this->sortResult($result[$managerXml]['SUB'], $this->arParams['SORT'], $this->arParams['DIR'], true);
            }
        }

        return $result;
    }

    public function getTemplateRow($idManager, $subManager, $firstLevel = false)
    {
        require $_SERVER['DOCUMENT_ROOT'] . $this->__template->__folder . '/_row.php';
    }

    public function getTemplateRowSaldo($saldo)
    {
        require $_SERVER['DOCUMENT_ROOT'] . $this->__template->__folder . '/_row_saldo.php';
    }

    public function showKontragent($kontragents)
    {
        require $_SERVER['DOCUMENT_ROOT'] . $this->__template->__folder . '/_showKontragent.php';
    }

    /**
     * Исключение из расчетов
     * @param $id
     * @param $excludeArray
     *
     * @return bool
     */
    private function checkExclude($id, $excludeArray)
    {
        if (in_array($id, $excludeArray)) {
            return true;
        }

        return false;
    }

    /**
     * По подчиненным менеджерам и своим контрагентам вычисляем автоплан.
     * @param $structureManagers
     *
     * @return array
     */
    private function calculateManagerNoSet($structureManagers)
    {
        $result = [];
        $sumPlanSetManager = 0;
        $countManagerNoSetPlan = 0;
        $countKontragentNoSet = $this->countKontragentNoSet($structureManagers);

        foreach ($structureManagers['SUB'] as $manager) {
            if ($manager['ALL_PLAN'] > 0) {
                $sumPlanSetManager += $manager['ALL_PLAN'];
            }
            if ($manager['ALL_PLAN'] == 0) {
                $countManagerNoSetPlan++;
            }
        }
        $sumPlanContragentManagerBoss = 0;
        foreach ($structureManagers['ITEMS'] as $contragent) {
            $sumPlanContragentManagerBoss += $contragent['PLAN'];
        }
        $sumManagerNoSetPlan = $structureManagers['ALL_PLAN'] - $sumPlanSetManager - $sumPlanContragentManagerBoss;
        if ($countManagerNoSetPlan > 0) {
            $xmlIdCurManager = $this->mapIdXmlManager[$structureManagers['ID']];
            //остаток нераспределенной суммы контрагентов менеджера, если они не делились без остатка
            $modKontragent = ($this->modByKontragent[$xmlIdCurManager]) ? $this->modByKontragent[$xmlIdCurManager] : 0;

            $sumManagerNoSetPlanOne = ($sumManagerNoSetPlan) / ($countManagerNoSetPlan + $countKontragentNoSet);
            $mod = ($sumManagerNoSetPlan) % ($countManagerNoSetPlan + $countKontragentNoSet);
            for ($i = 0; $i < $countManagerNoSetPlan; $i++) {
                $result[] = $sumManagerNoSetPlanOne;
            }
            if ($mod > 0) {
                $result[0] += $mod;
                $result[0] -= $modKontragent;
            }
        }

        return $result;
    }

    /**
     * Установка не проставленных планов менеджеров по приоритетам
     * @param $structureManagers
     * @param false $debug
     *
     * @return mixed
     * @throws Exception
     */
    private function setCalculateManagersNoSet($structureManagers, $debug = false)
    {
        $calculateValue = $this->calculateManagerNoSet(
            $structureManagers
        );
        $id = 0;

        foreach ($structureManagers['SUB'] as $idManager => &$manager) {
            $manager['priority'] = 1;
            if ($manager['ALL_PLAN'] == 0) { // Проверка №1 - установлено из БД
                if (!empty($calculateValue) && $calculateValue[$id] > 0) { // Проверка №2 - рассчитано из вышестоящего менеджера
                    $manager['ALL_PLAN'] = $calculateValue[$id];
                    $manager['priority'] = 2;
                    if ($manager['ALL_PLAN'] != 0) {
                        $managerSub = $manager['SUB'];
                        $countManagerNoSet = $this->countManagerNoSet($manager);
                        $manager = $this->getTempResult(
                            $idManager,
                            $this->period,
                            $this->defaultPeriod,
                            $this->plans,
                            [$idManager=>$manager['ALL_PLAN']],
                            $manager['ID'],
                            $countManagerNoSet
                        );
                        $manager['SUB'] = $managerSub;
                    }
                } else { // Проверка №3 - расчет плана менеджера по подчиненным менеджерам и клиентам
                    $manager['ALL_PLAN'] = $this->getPlanManagerByPriority3($manager);
                    $manager['priority'] = 3;
                }
                $id++;
            }
        }


        if ($structureManagers['ALL_PLAN'] == 0) {
            $structureManagers['ALL_PLAN'] = $this->getPlanBoss($structureManagers['ID']);
            if ($structureManagers['ALL_PLAN'] == 0) {
                $structureManagers['ALL_PLAN'] = $this->getPlanManagerByPriority3($structureManagers);
                $structureManagers['priority'] = 3;
            } else {
                $structureManagers['priority'] = 2;
            }
        }

        return $structureManagers;
    }

    /**
     * Получение плана по вышестоящим менеджерам
     * @param $employeeId
     *
     * @return int|mixed
     * @throws Exception
     */
    private function getPlanBoss($employeeId)
    {
        $result = 0;
        $structureBoss = UserDataManager::getStructureBossManagers($employeeId);

        foreach ($structureBoss as $managerId) {
            $structureManagers = [];

            $managerXml = array_search($managerId, $this->mapXmlIdManager);
            if ($managerXml === false) {
                $managerXml = UserDataManager::getManagerXmlId($managerId);
                $this->mapXmlIdManager[$managerXml] = $managerId;
            }

            if (!isset($this->calculateValuePlanBoss[$managerXml])) {
                $structureManagers[] = ['UF_ID_MANAGER' => $managerId, 'sub' => UserDataManager::getStructureManagers($managerId)];
                $arResult = $this->recursionResult($structureManagers);

                // Если у вышестоящего менеджера не установлен план, то расчет плана менеджера по подчиненным менеджерам и клиентам
                if ($structureManagers['ALL_PLAN'] == 0) {
                    $arResult['ALL_PLAN'] = $this->getPlanManagerByPriority3($arResult[$managerXml]);
                }

                $calculateValue = $this->calculateManagerNoSet(
                    $arResult[$managerXml]
                );
                $this->calculateValuePlanBoss[$managerXml] = $calculateValue;
            } else {
                $calculateValue = $this->calculateValuePlanBoss[$managerXml];
            }
            if (!empty($calculateValue)) {
                return $calculateValue[0];
            }
        }

        return $result;
    }

    /**
     * Пересчет результата для заполнения не проставленных планов
     * @param $structureManagers
     *
     * @return mixed
     * @throws Exception
     */
    private function setCalculateManagersNoSetForArray($structureManagers)
    {
        foreach ($structureManagers as $key => $manager) {
            $structureManagers[$key] = $this->setCalculateManagersNoSet($manager);
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                $structureManagers[$key]['SUB'][$key2] = $this->setCalculateManagersNoSet($manager2);
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    $structureManagers[$key]['SUB'][$key2]['SUB'][$key3] = $this->setCalculateManagersNoSet($manager3);
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    foreach ($manager3['SUB'] as $key4 => $manager4) {
                        $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4] = $this->setCalculateManagersNoSet($manager4);
                    }
                }
            }
        }

        return $structureManagers;
    }

    /**
     * Подсчет суммы плана для исключения
     * @param $structureManagers
     * @param false $exclude
     *
     * @return int|mixed
     */
    private function getPlanManagerByPriority3($structureManagers, $exclude = false)
    {
        $plan = 0;

        foreach ($structureManagers['SUB'] as $manager) {
            $temp = $manager['ALL_PLAN'] == 0 ? $manager['ALL_AUTOPLAN'] : $manager['ALL_PLAN'];
            if (!$exclude) {
                $plan += $temp;
            } else {
                if($this->checkExclude($manager['ID'], $this->arResult['EXCLUDE_MANAGER'])) {
                    $plan += $temp;
                } else {
                    $plan += $this->getSummExludeEmploys($manager, 0);
                }
            }
        }

        foreach ($structureManagers['ITEMS'] as $contragent) {
            $temp = $contragent['PLAN'] == 0 ? $contragent['AUTOPLAN'] : $contragent['PLAN'];
            if (!$exclude) {
                $plan += $temp;
            } elseif($this->checkExclude($contragent['ID'], $this->arResult['EXCLUDE_KONTRAGENT'])) {
                $plan += $temp;
            }

        }

        return $plan;
    }

    /**
     * Для подсчета вложенности. Вызов из getPlanManagerByPriority3
     * @param $manager
     * @param $plan
     *
     * @return int|mixed
     */
    private function getSummExludeEmploys($manager, $plan)
    {
        $isBoss = false;
        foreach ($this->arResult['EXCLUDE_MANAGER'] as $excludeManager) {
            $stack = $this->stackManager[$excludeManager];
            if (in_array($manager['ID'], $stack)) {
                $isBoss = true;
                break;
            }
        }

        if ($isBoss || $manager['ITEMS']) {
            $plan += $this->getPlanManagerByPriority3($manager, true);
        }
        return $plan;
    }

    /**
     * Получение факта, ПДЗ, ДЗ менеджера по его клиентам и подчиненным
     * @param $structureManagers
     * @param false $exclude
     *
     * @return int|mixed
     */
    private function getFactPdzDzManager($structureManagers, $exclude = false)
    {
        $fact = 0;
        $pdz = 0;
        $dz = 0;

        foreach ($structureManagers['SUB'] as $manager) {
            if(!$this->checkExclude($manager['ID'], $this->arResult['EXCLUDE_MANAGER'])) {
                $fact += $manager['ALL_FACT'];
                $pdz += $manager['ALL_PDZ'];
                $dz += $manager['ALL_DZ'];
            }
        }

        foreach ($structureManagers['ITEMS'] as $contragent) {
            if(!$this->checkExclude($contragent['ID'], $this->arResult['EXCLUDE_KONTRAGENT'])) {
                $fact += $contragent['FACT'];
                $pdz += $contragent['PDZ'];
                $dz += $contragent['DZ'];
            }
        }

        return [
            'FACT' => $fact,
            'PDZ' => $pdz,
            'DZ' => $dz
        ];
    }

    /**
     * Сопоставление ID и XML_ID менеджера
     * @param $structureManagersInfo
     *
     * @return array
     */
    private function getMapXmlIdManager($structureManagersInfo)
    {
        $result = [];

        foreach ($structureManagersInfo as $manager) {
            $result[$manager['UF_XML_ID']] = $manager['ID'];
        }

        return $result;
    }

    /**
     * Сопоставление ID и XML_ID менеджера
     * @param $structureManagersInfo
     *
     * @return array
     */
    private function getMapIdXmlManager($structureManagersInfo)
    {
        $result = [];

        foreach ($structureManagersInfo as $manager) {
            $result[$manager['ID']] = $manager['UF_XML_ID'];
        }

        return $result;
    }

    /**
     * Пересчет массива с учетом исключенных менеджеров и контрагентов
     * @param $structureManagers
     *
     * @return mixed
     */
    private function setCalculateManagerExclude($structureManagers)
    {
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    foreach ($manager3['SUB'] as $key4 => $manager4) {
                        $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]['ALL_PLAN'] = $manager4['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager4, true);
                        $temp = $this->getFactPdzDzManager($manager4, true);
                        $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]['ALL_FACT'] = $temp['FACT'];
                        $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]['ALL_PDZ'] = $temp['PDZ'];
                        $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]['ALL_DZ'] = $temp['DZ'];
                    }
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['ALL_PLAN'] = $manager3['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager3, true);
                    $temp = $this->getFactPdzDzManager($manager3, true);
                    $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['ALL_FACT'] = $temp['FACT'];
                    $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['ALL_PDZ'] = $temp['PDZ'];
                    $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['ALL_DZ'] = $temp['DZ'];
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                $structureManagers[$key]['SUB'][$key2]['ALL_PLAN'] = $manager2['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager2, true);
                $temp = $this->getFactPdzDzManager($manager2, true);
                $structureManagers[$key]['SUB'][$key2]['ALL_FACT'] = $temp['FACT'];
                $structureManagers[$key]['SUB'][$key2]['ALL_PDZ'] = $temp['PDZ'];
                $structureManagers[$key]['SUB'][$key2]['ALL_DZ'] = $temp['DZ'];
            }
        }
        foreach ($structureManagers as $key => $manager) {
            $structureManagers[$key]['ALL_PLAN'] = $manager['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager, true);
            $temp = $this->getFactPdzDzManager($manager, true);
            $structureManagers[$key]['ALL_FACT'] = $temp['FACT'];
            $structureManagers[$key]['ALL_PDZ'] = $temp['PDZ'];
            $structureManagers[$key]['ALL_DZ'] = $temp['DZ'];
        }

        return $structureManagers;
    }

    /**
     * Подсчет не распределенного плана
     * @param $structureManagers
     *
     * @return mixed
     */
    private function setCalculateManagersSaldo($structureManagers)
    {
        foreach ($structureManagers as $key => $manager) {
            $structureManagers[$key]['SALDO'] = $manager['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager);
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                $structureManagers[$key]['SUB'][$key2]['SALDO'] = $manager2['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager2);
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SALDO'] = $manager3['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager3);
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    foreach ($manager3['SUB'] as $key4 => $manager4) {
                        $structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]['SALDO'] = $manager4['ALL_PLAN'] - $this->getPlanManagerByPriority3($manager4);
                    }
                }
            }
        }
        return $structureManagers;
    }

    /**
     * Если установлен поиск по клиентам и нет результатов, то исключаем этого менеджера из результата
     * @param $structureManagers
     *
     * @return mixed
     */
    private function excludeManagerNoClient($structureManagers)
    {
        // Флаг, что ни одного клиента не найдено
        $noFoundClients = empty($this->arResult['FOUND_CLIENTS']);
        if (!$noFoundClients) {
            $this->arResult['FOUND_CLIENTS'] = array_column($this->arResult['FOUND_CLIENTS'], 'ID');
        }

        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    foreach ($manager3['SUB'] as $key4 => $manager4) {
                        if (!$noFoundClients) {
                            // если установлен поиск по клиентам и нет результатов, то исключаем этого менеджера
                            $countClient = $this->countClientManager($structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]);
                            $delete = $countClient == 0;
                        } else {
                            $delete = true;
                        }
                        if ($delete) {
                            unset($structureManagers[$key]['SUB'][$key2]['SUB'][$key3]['SUB'][$key4]);
                        }
                    }
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                foreach ($manager2['SUB'] as $key3 => $manager3) {
                    if (!$noFoundClients) {
                        // если установлен поиск по клиентам и нет результатов, то исключаем этого менеджера
                        $countClient = $this->countClientManager($structureManagers[$key]['SUB'][$key2]['SUB'][$key3]);
                        $delete = $countClient == 0;
                    } else {
                        $delete = true;
                    }
                    if ($delete) {
                        unset($structureManagers[$key]['SUB'][$key2]['SUB'][$key3]);
                    }
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            foreach ($manager['SUB'] as $key2 => $manager2) {
                if (!$noFoundClients) {
                    // если установлен поиск по клиентам и нет результатов, то исключаем этого менеджера
                    $countClient = $this->countClientManager($structureManagers[$key]['SUB'][$key2]);
                    $delete = $countClient == 0;
                } else {
                    $delete = true;
                }
                if ($delete) {
                    unset($structureManagers[$key]['SUB'][$key2]);
                }
            }
        }
        foreach ($structureManagers as $key => $manager) {
            if (!$noFoundClients) {
                // если установлен поиск по клиентам и нет результатов, то исключаем этого менеджера
                $countClient = $this->countClientManager($structureManagers[$key]);
                $delete = $countClient == 0;
            } else {
                $delete = true;
            }
            if ($delete) {
                unset($structureManagers[$key]);
            }
        }

        return $structureManagers;
    }

    /**
     * Количество клиентов менеджера и его подчиненных
     * @param $structureManagers
     * @param int $count
     * @param false $debug
     *
     * @return int|mixed
     */
    private function countClientManager(&$structureManagers, $count = 0, $debug = false)
    {
        foreach ($structureManagers['ITEMS'] as $idClient => $client) {
            if (in_array($client['ID'], $this->arResult['FOUND_CLIENTS'])) {
                $count++;
            } else {
                unset($structureManagers['ITEMS'][$idClient]);
            }
        }

        if (isset($structureManagers['SUB']) && !empty($structureManagers['SUB'])) {
            foreach ($structureManagers['SUB'] as $manager) {
                $count += $this->countClientManager($manager, $count, $debug);
            }
        }

        return $count;
    }

    private function setSummAll($structureManagers)
    {
        // По текущему менеджеру считаем если нет ни одного ограничения
        if (empty($this->arResult['CLIENT_FILTER']) && empty($this->arResult['EXCLUDE_KONTRAGENT']) && empty($this->arResult['EXCLUDE_MANAGER'])) {
            foreach ($structureManagers as $key => $manager) {
                $this->arResult['ALL_PLAN'] += $structureManagers[$key]['ALL_PLAN'];
                $this->arResult['ALL_DZ'] += $structureManagers[$key]['ALL_DZ'];
                $this->arResult['ALL_PDZ'] += $structureManagers[$key]['ALL_PDZ'];
                $this->arResult['ALL_FACT'] += $structureManagers[$key]['ALL_FACT'];
            }

            return;
        }
        $clients = [];
        foreach ($structureManagers as $manager) {
            foreach ($manager['SUB'] as $manager2) {
                foreach ($manager2['SUB'] as $manager3) {
                    foreach ($manager3['SUB'] as $manager4) {
                        if (!$manager4['EXCLUDE']) {
                            $this->arResult['ALL_PLAN'] += $manager4['ALL_PLAN'];
                        }
                        if (!empty($manager4['ITEMS'])) {
                            $clients = array_merge($clients, array_column($manager4['ITEMS'], 'ID'));
                        }
                    }
                }
            }
        }
        foreach ($structureManagers as $manager) {
            foreach ($manager['SUB'] as $manager2) {
                foreach ($manager2['SUB'] as $manager3) {
                    if (!$manager3['EXCLUDE']) {
                        $this->arResult['ALL_PLAN'] += $manager3['ALL_PLAN'];
                    }
                    if (!empty($manager3['ITEMS'])) {
                        $clients = array_merge($clients, array_column($manager3['ITEMS'], 'ID'));
                    }
                }
            }
        }
        foreach ($structureManagers as $manager) {
            foreach ($manager['SUB'] as $manager2) {
                if (!$manager2['EXCLUDE']) {
                    $this->arResult['ALL_PLAN'] += $manager2['ALL_PLAN'];
                }
                if (!empty($manager2['ITEMS'])) {
                    $clients = array_merge($clients, array_column($manager2['ITEMS'], 'ID'));
                }
            }
        }
        foreach ($structureManagers as $manager) {
            if (!empty($manager['ITEMS'])) {
                $clients = array_merge($clients, array_column($manager['ITEMS'], 'ID'));
            }
        }

        if (!empty($clients)) {
            foreach ($clients as $clientId) {
                if (!$this->checkExclude($clientId, $this->arResult['EXCLUDE_KONTRAGENT'])) {
                    $this->arResult['ALL_PLAN'] += $this->setPlanClients[$clientId];
                    $this->arResult['ALL_FACT'] += $this->setFactClients[$clientId];
                    $this->arResult['ALL_PDZ'] += $this->setPdzClients[$clientId];
                    $this->arResult['ALL_DZ'] += $this->setDzClients[$clientId];
                }
            }
        }
    }

    private function countManagerNoSet($structureManagers)
    {
        $count = 0;
        if (isset($structureManagers['SUB']) && !empty($structureManagers['SUB'])) {
            foreach ($structureManagers['SUB'] as $manager) {
                if ($manager['ALL_PLAN'] == 0) {
                    $count++;
                }
            }
        }

        return $count;
    }

    private function countKontragentNoSet($structureManagers)
    {
        $count = 0;
        foreach ($structureManagers['ITEMS'] as $idClient => $client) {
            if ($client['PLAN'] == 0) {
                $count++;
            }
        }

        return $count;
    }
}
