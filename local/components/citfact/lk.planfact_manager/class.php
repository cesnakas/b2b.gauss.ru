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

class PlanFactComponent extends \CBitrixComponent
{
    const MONTHS_RU = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
    const MONTHS_EN = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];

    public static $groupCodes = [
        'MANAGER',
        'ASSISTANT',
    ];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $request = Context::getCurrent()->getRequest();
        if (!empty($request->getPost('operation') && $request->getPost('operation') == 'save_data')) {
            $this->savePlanFactData($request);
        }
        if (!empty($request->getPost('operation_default') && $request->getPost('operation_default') == 'save_default_data')) {
            $this->saveDefaultPlanFactData($request);
        }

        $this->arResult['MONTHS'] = $this->getAllMonths();
        // Получаем ID менеджера для текущего пользователя
        $curManagerId = UserDataManager::getIdManagerByUserId(CUser::GetID());
        $this->arResult['CUR_MANAGER_ID'] = $curManagerId;
        if (!empty($request['managers'])) {
            $managerXmlId = UserDataManager::getUserManagerStructureXmlId($request['managers']);
            $this->arResult['CURRENT_MANAGER_FILTER'] = $request['managers'];
            if ($managerXmlId) {
                $this->arResult['CONTRAGENTS'] = UserDataManager::getKontragents($managerXmlId);
            }
        } else {
            $this->arResult['CURRENT_MANAGER_FILTER'] = $curManagerId;
        }
        if (!empty($request['filter_manager_plan'])) {
            $this->arResult['FILTER_MANAGER_PLAN'] = $request['filter_manager_plan'];
        } else if(!empty($request['setFilterManagerPlan'])) {
            $this->arResult['FILTER_MANAGER_PLAN'] = $request['setFilterManagerPlan'];
        }

        $this->arResult['USER_MANAGERS'] = UserDataManager::getStructureManagersInfo($curManagerId);

        $this->getPlanFactData($request);
        $this->getDefaultPlanFactData($request);

        $this->IncludeComponentTemplate();
    }


    public function getPlanFactData($request)
    {
        if (empty($this->arResult['CONTRAGENTS']) && empty($request['managers'])) {
            $contragents = UserDataManager::getContragentsList([], true);
            $this->arResult['CONTRAGENTS'] = $contragents;
        } else {
            $contragents = $this->arResult['CONTRAGENTS'];
        }
        $contragent = null;

        if (!empty($request['contragents'])) {
            foreach ($contragents as $arrContragent) {
                if ($arrContragent['UF_XML_ID'] === $request['contragents']) {
                    $contragent = $arrContragent['UF_XML_ID'];
                }
            }
        }

        if (null === $contragent) {
            $contragent = $contragents[0]['UF_XML_ID'];
        }

        $this->arResult['CURRENT_CONTRAGENT'] = $contragent;

        if (!empty($request['year'])) {
            $year = $request['year'];
        } else {
            $year = date('Y');
        }

        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_PLAN_FACT_MANAGER);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => [
                'UF_CONTRAGENT' => $contragent,
                'UF_YEAR' => $year
            ],
        ));
        $this->arResult['contragent'] = $contragent;
        $this->arResult['year'] = $year;
        if ($el = $rsData->fetch()) {
            $this->arResult['first_quarter'] = $el['UF_FIRST_QUARTER'];
            $this->arResult['second_quarter'] = $el['UF_SECOND_QUARTER'];
            $this->arResult['third_quarter'] = $el['UF_THIRD_QUARTER'];
            $this->arResult['fourth_quarter'] = $el['UF_FOURTH_QUARTER'];
            $this->arResult['plan_for_year'] = $el['UF_PLAN_FOR_YEAR'];
            foreach (PlanFactComponent::MONTHS_EN as $month) {
                $field = 'UF_' . mb_strtoupper($month);
                $this->arResult[$month] = $el[$field];
            }
        }
    }

    public function getDefaultPlanFactData($request)
    {
        global $USER;
        //Записываем в массив $arResult xml код текущего менеджера или ассистента
        $manager = UserDataManager::getUserManagerXmlId();

        $assistant = UserDataManager::getUserAssistantXmlId();

        $arGroups = $USER->GetUserGroupArray();
        $core = Core::getInstance();

        $isUserManager = !empty(array_intersect($arGroups, $core->GetGroupByCode($core::USER_GROUP_MANAGER)));
        $isUserAssistant = !empty(array_intersect($arGroups, $core->GetGroupByCode($core::USER_GROUP_ASSISTANT)));
        $default = null;
        
        if (isset($request['filter_manager_plan_form2']) && !empty($request['filter_manager_plan_form2'])) {
            $default = UserDataManager::getManagerXmlId($request['filter_manager_plan_form2']);
        } elseif (isset($request['filter_manager_plan']) && !empty($request['filter_manager_plan'])) {
            $default = UserDataManager::getManagerXmlId($request['filter_manager_plan']);
        } elseif($isUserManager) {
            $this->arResult['MANAGER'] = $manager;
            $default= $manager;
        } elseif ($isUserAssistant){
            $this->arResult['ASSISTANT'] = $assistant;
            $default= $assistant[0];
        } else{
            return [];
        }
        $this->arResult['CURRENT_MANAGER'] = $default;
        $managerId = UserDataManager::getIdManagerByXml($default);
        $this->arResult['CURRENT_MANAGER_ID'] = $managerId;


        if (!empty($request['year'])) {
            $year = $request['year'];
        } else {
            $year = date('Y');
        }

        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_PLAN_FACT_GENERAL);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $this->arResult['general_plan']= [];
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => [
                '=UF_MANAGER' => $default,
                '=UF_YEAR' => $year
            ],
        ));
        $this->arResult['general_plan']['year'] = $year;


        if ($el = $rsData->fetch()) {

            $this->arResult['general_plan']['first_quarter'] = $el['UF_FIRST_QUARTER'];
            $this->arResult['general_plan']['second_quarter'] = $el['UF_SECOND_QUARTER'];
            $this->arResult['general_plan']['third_quarter'] = $el['UF_THIRD_QUARTER'];
            $this->arResult['general_plan']['fourth_quarter'] = $el['UF_FOURTH_QUARTER'];
            $this->arResult['general_plan']['plan_for_year'] = $el['UF_PLAN_FOR_YEAR'];
            foreach (PlanFactComponent::MONTHS_EN as $month) {
                $field = 'UF_' . mb_strtoupper($month);
                $this->arResult['general_plan'][$month] = $el[$field];
            }
        }
    }

    public function savePlanFactData($request)
    {
        $contragentGuid = $request['contragents'];
        $year = (integer)$request['year'];

        //месяцы
        $mothPlans = $editedRequest = [];
        foreach (PlanFactComponent::MONTHS_EN as $month) {
            $editedRequest[$month] = (int)str_replace(' ', '', $request[$month]);
            $mothPlans[$month] = $editedRequest[$month];
        }
        // расчет за кварталы
        $firstQuarter = $editedRequest['january'] + $editedRequest['february'] + $editedRequest['march'];
        $secondQuarter = $editedRequest['april'] + $editedRequest['may'] + $editedRequest['june'];
        $thirdQuarter = $editedRequest['july'] + $editedRequest['august'] + $editedRequest['september'];
        $fourthQuarter = $editedRequest['october'] + $editedRequest['november'] + $editedRequest['december'];

        // расчет за год
        $planForYear = $firstQuarter + $secondQuarter + $thirdQuarter + $fourthQuarter;

        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_PLAN_FACT_MANAGER);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        //вначале удаляем старые данные, если есть
        $rsData = $entity_data_class::getList(array(
            'select' => ['ID'],
            'filter' => [
                'UF_CONTRAGENT' => $contragentGuid,
                'UF_YEAR' => $year
            ],
        ));
        while ($el = $rsData->fetch()) {
            $ID = $el['ID'];
            $entity_data_class::Delete($ID);
        }

        //Записываем новые данные
        $data = [
            'UF_CONTRAGENT' => $contragentGuid,
            'UF_YEAR' => $year,
            'UF_FIRST_QUARTER' => $firstQuarter,
            'UF_SECOND_QUARTER' => $secondQuarter,
            'UF_THIRD_QUARTER' => $thirdQuarter,
            'UF_FOURTH_QUARTER' => $fourthQuarter,
            'UF_PLAN_FOR_YEAR' => $planForYear
        ];
        foreach (PlanFactComponent::MONTHS_EN as $month) {
            $field = 'UF_' . mb_strtoupper($month);
            $data[$field] = $mothPlans[$month];
        }

        $result = $entity_data_class::add($data);
        $ID = $result->getId();
        if ($result->isSuccess()) {
            $this->arResult['PLAN_FACT_SAVE'] = 'SUCCESS';
        } else {
            $this->arResult['PLAN_FACT_SAVE'] = 'FAIL';
            $this->arResult['ERROR'] = 'Ошибка добавления записи в HL-блок.';
        }
    }

    public function saveDefaultPlanFactData($request)
    {
        if (isset($request['filter_manager_plan']) && !empty($request['filter_manager_plan'])) {
            $defaultGuid = UserDataManager::getManagerXmlId($request['managers']);
        } else {
            $defaultGuid = $request['manager_default'];
        }
        $year = (integer)$request['year'];

        //месяцы
        $mothPlans = $editedRequest = [];
        foreach (PlanFactComponent::MONTHS_EN as $month) {
            $editedRequest[$month] = (int)str_replace(' ', '', $request[$month]);
            $mothPlans[$month] = $editedRequest[$month];
        }
        // расчет за кварталы
        $firstQuarter = $editedRequest['january'] + $editedRequest['february'] + $editedRequest['march'];
        $secondQuarter = $editedRequest['april'] + $editedRequest['may'] + $editedRequest['june'];
        $thirdQuarter = $editedRequest['july'] + $editedRequest['august'] + $editedRequest['september'];
        $fourthQuarter = $editedRequest['october'] + $editedRequest['november'] + $editedRequest['december'];

        // расчет за год
        $planForYear = $firstQuarter + $secondQuarter + $thirdQuarter + $fourthQuarter;

        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_PLAN_FACT_GENERAL);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        //вначале удаляем старые данные, если есть
        $rsData = $entity_data_class::getList(array(
            'select' => ['ID'],
            'filter' => [
                'UF_MANAGER' => $defaultGuid,
                'UF_YEAR' => $year
            ],
        ));
        while ($el = $rsData->fetch()) {
            $ID = $el['ID'];
            $entity_data_class::Delete($ID);
        }

        //Записываем новые данные
        $data = [
            'UF_MANAGER' => $defaultGuid,
            'UF_YEAR' => $year,
            'UF_FIRST_QUARTER' => $firstQuarter,
            'UF_SECOND_QUARTER' => $secondQuarter,
            'UF_THIRD_QUARTER' => $thirdQuarter,
            'UF_FOURTH_QUARTER' => $fourthQuarter,
            'UF_PLAN_FOR_YEAR' => $planForYear
        ];
        foreach (PlanFactComponent::MONTHS_EN as $month) {
            $field = 'UF_' . mb_strtoupper($month);
            $data[$field] = $mothPlans[$month];
        }

        $result = $entity_data_class::add($data);
        $ID = $result->getId();
        if ($result->isSuccess()) {
            $this->arResult['PLAN_FACT_SAVE_MANAGER'] = 'SUCCESS';
        } else {
            $this->arResult['PLAN_FACT_SAVE_MANAGER'] = 'FAIL';
            $this->arResult['ERROR'] = 'Ошибка добавления записи в HL-блок.';
        }
    }

    /**
     * Возвращает заголовки, имена и id для волей ввода планов по месяцам
     *
     * @return array
     */
    public function getAllMonths() {
        $result['TITLES'] = PlanFactComponent::MONTHS_RU;
        $result['NAMES_IDS'] = PlanFactComponent::MONTHS_EN;
        return $result;
    }
}


