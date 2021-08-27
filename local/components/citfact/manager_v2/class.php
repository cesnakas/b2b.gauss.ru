<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Citfact\SiteCore\UserDataManager\UserDataManager;
use Plan\PlanDbStory;

class MainProfile2Component extends CBitrixComponent
{
    /**
     * @var \Plan\PlanDataProvider
     */
    public $planDataProvider;

    public function executeComponent()
    {
        require_once __DIR__ . '/plandataprovider.php';
        Loader::includeModule('highloadblock');
        // выбираем период для отображения фактически данных, по умолчанию текущий месяц
        $period = $this->arParams['PERIOD'] ?? 'month_' . date('m') . '_' . date('Y');
        $isDefaultPeriod = $this->arParams['PERIOD'] ? false : true;
        $this->planDataProvider = new \Plan\PlanDataProvider($period, $isDefaultPeriod);
        $this->planDataProvider->setMainManagerId($this->getManagerIdFromFilterRequest());
        $this->planDataProvider->setExcludeManager($this->getExcludeManager());
        $this->planDataProvider->setExcludeKontragent($this->getExcludeKontragent());
        $this->planDataProvider->setSort($this->arParams['SORT'], $this->arParams['DIR']);
        $managerModel = $this->planDataProvider->getManagerModel();
        $this->arResult['IS_SEARCH_STRING'] = $this->planDataProvider->structureBuilder->isSearchString();
//        pre($managerModel);
        $this->arResult['MANAGER_MODEL'] = $managerModel;
        $this->arResult['USER_MANAGERS'] = $this->getManagersForSelect();
        $this->arResult['DEFAULT_PERIOD'] = $isDefaultPeriod;
        $this->arResult['CUR_MANAGER_ID'] = $this->planDataProvider->getCurManagerId();
        // получение ссылок для статистики на текущий год, квартал, месяц
        $this->arResult['CURRENT'] = $this->getLinksForCurrentPeriods();
        // получение описания текущего периода
        $this->arResult['PERIOD_DESC'] = $this->getPeriodDescription($period);
        // получение текущего периода
        $this->arResult['SELECTED_PERIOD'] = $this->getSelectedPeriod($period);
        // подключение шаблона компонента
        $request = Bitrix\Main\Context::getCurrent()->getRequest();
        if ($request->isAjaxRequest()) {
            \Bitrix\Iblock\Component\Base::sendJsonAnswer(json_decode(json_encode(
                $managerModel->getDataForAjax(!$this->arResult['IS_SEARCH_STRING'])), true));
        }
        // подключение шаблона компонента 
        $this->includeComponentTemplate();

        return $this->arResult;
    }

    /**
     * Формирует ссылки на статистику за текущий год, квартал и месяц
     * @return array
     */
    public function getLinksForCurrentPeriods()
    {
        $current['YEAR'] = '/personal/?period=year_' . date('Y');
        $current['MONTH'] = '/personal/?period=month_' . date('n') . '_' . date('Y');
        if (date('n') <= 3) {
            $quarter = 1;
        } elseif (date('n') <= 6) {
            $quarter = 2;
        } elseif (date('n') <= 9) {
            $quarter = 3;
        } else {
            $quarter = 4;
        }
        $current['QUARTER'] = '/personal/?period=quarter_' . $quarter . '_' . date('Y');

        return $current;
    }

    /**
     * Выбранный периода
     * @param string $period
     * @return string
     */
    public function getSelectedPeriod($period)
    {
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
     * @param string $period
     * @return string
     */
    public function getPeriodDescription($period)
    {
        $periodData = explode('_', $period);
        switch ($periodData[0]) {
            case 'year': // year_<год>
                $desc = $periodData[1] . ' - ' . ($periodData[1] + 1);
                break;
            case 'quarter': // quarter_<номер квартала>_<год>
                switch ($periodData[1]) {
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
                switch ($periodData[1]) {
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
                $month = $periodData[1]; // удалила добавление первого 0
                $desc = '01.' . $month . '.' . $periodData[2] . ' - ' . $days . '.' . $month . '.' . $periodData[2];
                break;
            case 'from': // from_<дата>_to_<дата>
                $desc = $periodData[1] . ' - ' . $periodData[3];
                break;
        }
        return $desc;
    }

    private function getManagersForSelect()
    {
        $levels = $this->planDataProvider->planDbStory->getManagerLevels();
        $managers = $this->planDataProvider->getManagers();
        $allowIdManagers = UserDataManager::getAllowIdManagers($this->planDataProvider->getCurManagerId());

        $levelManagers = [];
        foreach ($managers as $manager) {
            if (!in_array($manager['ID'], $allowIdManagers) ||
                $manager['ID'] == $this->planDataProvider->getCurManagerId()) {
                continue;
            }
            if (in_array($manager['ID'], $this->getAllManagerIdFromFilterRequest())) {
                $manager['isSelected'] = true;
            } else {
                $manager['isSelected'] = false;
            }
            if ($manager['UF_LEVEL_MANAGER'] == $levels[PlanDbStory::DIRECTOR]) {
                $levelManagers[0][$manager['ID']] = $manager;
            } else if ($manager['UF_LEVEL_MANAGER'] == $levels[PlanDbStory::RM]) {
                $levelManagers[1][$manager['ID']] = $manager;
            } else if ($manager['UF_LEVEL_MANAGER'] == $levels[PlanDbStory::TM]) {
                $levelManagers[2][$manager['ID']] = $manager;
            }
        }

        $allowIdManagerFilter = $this->getAllowManagerIdsFromFilterRequest();

        foreach ($managers as $manager) {
            if (!in_array($manager['ID'], $allowIdManagers) ||
                $manager['ID'] == $this->planDataProvider->getCurManagerId()) {
                continue;
            }
            if (!empty($allowIdManagerFilter[PlanDbStory::RM])) {
                if (!in_array($manager['ID'], $allowIdManagerFilter[PlanDbStory::RM])) {
                    unset($levelManagers[1][$manager['ID']]);
                }
            }
            if (!empty($allowIdManagerFilter[PlanDbStory::TM])) {
                if (!in_array($manager['ID'], $allowIdManagerFilter[PlanDbStory::TM])) {
                    unset($levelManagers[2][$manager['ID']]);
                }
            }
        }
        foreach ($levelManagers as &$level){
            uasort($level, function($a, $b){
                if ($a['UF_NAME'] == $b['UF_NAME']) {
                    return 0;
                }
                return ($a['UF_NAME'] < $b['UF_NAME']) ? -1 : 1;
            });
        }
        unset($level);
        return $levelManagers;
    }

    private function getAllowManagerIdsFromFilterRequest()
    {
        $result = [
            PlanDbStory::RM => [],
            PlanDbStory::TM => [],
        ];
        $director = $this->getManagerIdFromFilterRequestForLevel(PlanDbStory::DIRECTOR);
        if ($director) {
            $result[PlanDbStory::RM] = UserDataManager::getAllowIdManagers($director);
            $result[PlanDbStory::TM] = $result[PlanDbStory::RM];
        }

        $rm = $this->getManagerIdFromFilterRequestForLevel(PlanDbStory::RM);
        if ($rm) {
            $result[PlanDbStory::TM] = UserDataManager::getAllowIdManagers($rm);
        }

        return $result;
    }

    private function getManagerIdFromFilterRequest()
    {
        $result = $this->planDataProvider->getCurManagerId();
        if (isset($_REQUEST['managers-tm']) && !empty($_REQUEST['managers-tm'])) {
            $result = (int)$_REQUEST['managers-tm'];
        } else if (isset($_REQUEST['managers']) && !empty($_REQUEST['managers'])) {
            $result = (int)$_REQUEST['managers'];
        } else if (isset($_REQUEST['director']) && !empty($_REQUEST['director'])) {
            $result = (int)$_REQUEST['director'];
        }
        return $result;
    }

    private function getManagerIdFromFilterRequestForLevel($level)
    {
        $result = false;
        switch ($level) {
            case PlanDbStory::DIRECTOR:
                if (isset($_REQUEST['director']) && !empty($_REQUEST['director'])) {
                    $result = (int)$_REQUEST['director'];
                }
                break;
            case PlanDbStory::RM:
                if (isset($_REQUEST['managers']) && !empty($_REQUEST['managers'])) {
                    $result = (int)$_REQUEST['managers'];
                }
                break;
            case PlanDbStory::TM:
                if (isset($_REQUEST['managers-tm']) && !empty($_REQUEST['managers-tm'])) {
                    $result = (int)$_REQUEST['managers-tm'];
                }
                break;
        }
        return $result;
    }

    private function getAllManagerIdFromFilterRequest()
    {
        $result = [];
        if (isset($_REQUEST['managers-tm']) && !empty($_REQUEST['managers-tm'])) {
            $result[] = (int)$_REQUEST['managers-tm'];
        }
        if (isset($_REQUEST['managers']) && !empty($_REQUEST['managers'])) {
            $result[] = (int)$_REQUEST['managers'];
        }
        if (isset($_REQUEST['director']) && !empty($_REQUEST['director'])) {
            $result[] = (int)$_REQUEST['director'];
        }
        return $result;
    }

    private function getLevelsManagerFromFilterRequest()
    {
        $result = [];
        if (isset($_REQUEST['managers-tm']) && !empty($_REQUEST['managers-tm'])) {
            $result[] = PlanDbStory::TM;
        }
        if (isset($_REQUEST['managers']) && !empty($_REQUEST['managers'])) {
            $result[] = PlanDbStory::RM;
        }
        if (isset($_REQUEST['director']) && !empty($_REQUEST['director'])) {
            $result[] = PlanDbStory::DIRECTOR;
        }
        return $result;
    }

    public function getExcludeManager()
    {
        $result = [];
        if (isset($_REQUEST['excludeManager']) && !empty($_REQUEST['excludeManager'])) {
            $result = $_REQUEST['excludeManager'];
        }
        return $result;
    }

    public function getExcludeKontragent()
    {
        $result = [];
        if (isset($_REQUEST['excludeKontragent']) && !empty($_REQUEST['excludeKontragent'])) {
            $result = $_REQUEST['excludeKontragent'];
        }
        return $result;
    }

    public function isSetManagerFromFilterRequest()
    {
        $id = $this->getManagerIdFromFilterRequest();
        if ($id != $this->planDataProvider->getCurManagerId()) {
            return true;
        }
        return false;
    }

}
