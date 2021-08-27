<?php

namespace Plan;


use Citfact\SiteCore\CacheProvider\LkCacheManager;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\SiteCore\UserDataManager\UserDataManager;
use Plan\Models\AutoPlanModel;
use Plan\Models\KontragentModel;
use Plan\Models\ManagerModel;

class StructureBuilder
{
    /** @var HLBlock */
    protected $HLBlock;
    protected $planDbStory;
    protected $managerIds = [];
    protected $managers = [];
    protected $generalPlans = [];
    protected $facts = [];
    protected $idStructureManagers = [];
    protected $kontragents = [];
    protected $plans = [];
    protected $pdz;
    protected $dz;
    protected $mainManagerId;
    private $excludeManagerId = [];
    private $excludeKontragentId = [];
    /**
     * @var Sort
     */
    protected $sort;
    private $mainBoss;
    private $curAuthManagerId;
    private $searchString;

    public function __construct($managerId, $period, $isDefaultPeriod)
    {
        $this->HLBlock = new HLBlock();
        $this->planDbStory = new PlanDbStory();
        $this->mainBoss = $this->getMainBoss($managerId);
        if (!empty($_REQUEST['q'])) {
            $this->setSearchString($_REQUEST['q']);
        }
        $this->idStructureManagers = $this->getIdStructureManagersCache($this->mainBoss);
        $this->managerIds = $this->extractManagerIdsFromStructure();
        $this->managers = $this->getManagersByIds($this->managerIds);
        $this->kontragents = $this->getKontragentsFromDBCache($this->getManagerXmlIds());
        $this->facts = $this->planDbStory->getFactsCache($period, $this->kontragents);
        $this->plans = $this->planDbStory->getPlansCache($period);
        $this->generalPlans = $this->planDbStory->getGeneralPlansCache($period);
        $this->pdz = $this->planDbStory->getPdzCache($period, $this->kontragents, $isDefaultPeriod);
        $this->dz = $this->planDbStory->getDzCache($period, $this->kontragents, $isDefaultPeriod);
        $this->setMainManagerId($managerId);
        $this->curAuthManagerId = $this->getCurAuthManagerId();
    }

    /**
     * Дерево id-ов подчиненности менеджеров
     * @param $managerId
     * @return array
     */
    protected function getIdStructureManagers($managerId)
    {
        $hlClassName = $this->HLBlock->getHlEntityByName(Core::HLBLOCK_CODE_STRUCTURE_MANAGERS);
        $recursion = function ($managerId, $hlClassName) use (&$recursion) {
            $dbResult = $hlClassName::getList([
                'filter' => ['UF_ID_MANAGER' => $managerId],
                'select' => ['UF_ID_MANAGER', 'UF_ID_SUB_MANAGER']
            ]);
            $result = [];
            if (empty($result)) {
                $result = [
                    'ID' => $managerId
                ];
            }
            while ($arData = $dbResult->Fetch()) {
                $result['sub'][$arData['UF_ID_SUB_MANAGER']] =
                    $recursion($arData['UF_ID_SUB_MANAGER'], $hlClassName);
            }
            $this->managerIds[$managerId] = $managerId;

            return $result;
        };
        return $recursion($managerId, $hlClassName);
    }

    /**
     * Дерево id-ов подчиненности менеджеров из кеша
     * @param $managerId
     * @return array
     */
    protected function getIdStructureManagersCache($managerId)
    {
        $obCache = new \CPHPCache;
        $cacheId = LkCacheManager::getCacheId('getIdStructureManagersCache' . $managerId);
        $cacheDir = '/' . $cacheId;
        if ($obCache->initCache(LkCacheManager::getCacheTime(), $cacheId, $cacheDir)) {
            $arResult = $obCache->getVars();
        } else if ($obCache->startDataCache()) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cacheDir);
            $CACHE_MANAGER->RegisterTag(LkCacheManager::getTag());
            $CACHE_MANAGER->EndTagCache();
            $arResult = $this->getIdStructureManagers($managerId);
            $obCache->endDataCache($arResult);
        }

        return $arResult;
    }

    public function getAllowIdManagers()
    {
        return UserDataManager::getAllowIdManagers($this->curAuthManagerId);
    }

    protected function getCurAuthManagerId()
    {
        $rsUser = \CUser::GetByID(\CUser::GetID());
        $arUser = $rsUser->Fetch();
        $hlClassName = $this->HLBlock->getHlEntityByName('Menedzhery');
        $dbResult = $hlClassName::getList([
            'filter' => ['ID' => $arUser['UF_MANAGER_ID']]
        ]);
        if ($arData = $dbResult->Fetch()) {
            $this->curAuthManagerId = $arData['ID'];
        } else {
            $this->curAuthManagerId = false;
        }
        return $this->curAuthManagerId;
    }

    /**
     * @return array
     */
    public function getManagerIds()
    {
        return $this->managerIds;
    }


    /**
     * Дерево подчиненности менеджеров детально
     * @return ManagerModel
     */
    public function getManagerModel()
    {
        if (!$this->checkAccessByMainManager()) {
            return new ManagerModel();
        }
        $recursion = function ($idStructureManagers) use (&$recursion) {
            $info = $this->managers[$idStructureManagers['ID']];
            $result = new ManagerModel();
            $result->id = $idStructureManagers['ID'];
            $result->info = $info;
            $result->setPlan($this->getGeneralPlansByManager($info['UF_XML_ID']));
            $result->kontragents = $this->getKontragentsByManager($info['UF_XML_ID']);
            if (in_array($result->id, $this->excludeManagerId)) {
                $result->setIsExclude(true);
            }
            if ($result->getPlan() <= 0) {
                $result->setIsCalculated(true);
            }
            foreach ($idStructureManagers['sub'] as $id => $item) {
                $result->sub[$id] = $recursion($item);
            }
            return $result;
        };

        return $recursion($this->idStructureManagers);
    }

    protected function getGeneralPlansByManager($idXml)
    {
        if (isset($this->generalPlans[$idXml])) {
            return $this->generalPlans[$idXml];
        }
        return 0;
    }

    protected function getPlansByKontragent($idXml)
    {
        if (isset($this->plans[$idXml])) {
            return $this->plans[$idXml];
        }
        return 0;
    }

    protected function getManagersByIds($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $result = $this->getAllManagers();
        foreach ($result as $k => $v) {
            if (!in_array($k, $ids)) {
                unset($result[$k]);
            }
        }
        return $result;
    }

    protected function getAllManagers()
    {
        $obCache = new \CPHPCache;
        $cacheId = LkCacheManager::getCacheId('getAllManagers');
        $cacheDir = '/' . $cacheId;
        if ($obCache->initCache(LkCacheManager::getCacheTime(), $cacheId, $cacheDir)) {
            $arResult = $obCache->getVars();
        } else if ($obCache->startDataCache()) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cacheDir);
            $CACHE_MANAGER->RegisterTag(LkCacheManager::getTag());
            $CACHE_MANAGER->EndTagCache();

            $result = [];
            $hlClassName = $this->HLBlock->getHlEntityByName('Menedzhery');
            $dbResult = $hlClassName::getList([
                'filter' => [],
                'order' => ['ID' => 'asc']
            ]);
            while ($arData = $dbResult->Fetch()) {
                $result[$arData['ID']] = $arData;
            }

            $arResult = $result;
            $obCache->endDataCache($arResult);
        }

        return $arResult;
    }

    public function getManagerXmlIds()
    {
        return array_column($this->managers, 'UF_XML_ID');
    }

    /**
     * @param $xmlIdManager
     * @return KontragentModel[]
     */
    protected function getKontragentsByManager($xmlIdManager)
    {
        $result = [];
        foreach ($this->kontragents as $kontragent) {
            if ($xmlIdManager == $kontragent['UF_MENEDZHER']) {
                $kontragentModel = new KontragentModel();
                $kontragentModel->id = $kontragent['ID'];
                $kontragentModel->info = $kontragent;
                $kontragentModel->setPlan($this->getPlansByKontragent($kontragent['UF_XML_ID']));
                $kontragentModel->setFact($this->getFactByKontragent($kontragent['UF_XML_ID']));
                $kontragentModel->setPdz($this->getPdzByKontragent($kontragent['UF_XML_ID']));
                $kontragentModel->setDz($this->getDzByKontragent($kontragent['UF_XML_ID']));
                if (in_array($kontragentModel->id, $this->excludeKontragentId)) {
                    $kontragentModel->setIsExclude(true);
                }
                if ($kontragentModel->getPlan() <= 0) {
                    $kontragentModel->setIsCalculated(true);
                }
                $result[$kontragent['ID']] = $kontragentModel;
            }
        }
        return $result;
    }

    public function buildAutoPlan(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel, ManagerModel $ManagerBossModel = null) use (&$recursion, $structureManagers) {
            //логика просчета автоплана
            if ($managerModel->getPlan() <= 0 && $managerModel->isCalculated()) {
                //вычисляем автоплан менеджера по жестко заданным планам его подчиненных
                if ($managerModel->existHardSetPlanBySubordinates()) {
                    $autoplan = $managerModel->getSumHardPlanBySubordinates();
                    $managerModel->setAutoPlan($autoplan);
                }
                //вычисляем автоплан по вышестоящему менеджеру, распределяя его автоплан равномерно на всех
                if (!is_null($ManagerBossModel)) {
                    $autoplan = $ManagerBossModel->getCalculatedAutoplan();
                    if (!$managerModel->existHardSetPlanBySubordinates()) {
                        $managerModel->setAutoPlan($autoplan);
                    }
                    foreach ($ManagerBossModel->kontragents as $kontragent) {
                        if ($kontragent->getPlan() <= 0 && $kontragent->isCalculated()) {
                            $kontragent->setAutoPlan($autoplan);
                        }
                    }
                }
            }
            //конец логики просчета автоплана
            foreach ($managerModel->sub as $manager) {
                $recursion($manager, $managerModel);
            }
            //если нет подчиненных менеджеров, то вычисляем автоплан его контрагентов
            if (count($managerModel->sub) <= 0) {
                $autoplanAgent = $managerModel->getCalculatedAutoplan();
                foreach ($managerModel->kontragents as $kontragent) {
                    if ($kontragent->getPlan() <= 0 && $kontragent->isCalculated()) {
                        $kontragent->setAutoPlan($autoplanAgent);
                    }
                }
            }
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    public function buildFactForManagers(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            $sumFact = $sumFactSub = 0;
            foreach ($managerModel->sub as $manager) {
                $recursion($manager);
                $sumFactSub += $manager->getFact();
            }
            foreach ($managerModel->kontragents as $kontragent) {
                $sumFact += $kontragent->getFact();
            }
            $sumFact += $sumFactSub;
            $managerModel->setFact($sumFact);
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    public function buildPdzForManagers(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            $sumPdz = $sumPdzSub = 0;
            foreach ($managerModel->sub as $manager) {
                $recursion($manager);
                $sumPdzSub += $manager->getPdz();
            }
            foreach ($managerModel->kontragents as $kontragent) {
                $sumPdz += $kontragent->getPdz();
            }
            $sumPdz += $sumPdzSub;
            $managerModel->setPdz($sumPdz);
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    public function buildDzForManagers(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            $sumDz = $sumDzSub = 0;
            foreach ($managerModel->sub as $manager) {
                $recursion($manager);
                $sumDzSub += $manager->getDz();
            }
            foreach ($managerModel->kontragents as $kontragent) {
                $sumDz += $kontragent->getDz();
            }
            $sumDz += $sumDzSub;
            $managerModel->setDz($sumDz);
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    protected function getKontragentsFromDB($managerXmlId)
    {
        if (!empty($this->searchString)) {
//            $filter = ['%=UF_NAME' => '%' . htmlspecialcharsBack($this->searchString) . '%'];
        }
        $filter['UF_MENEDZHER'] = $managerXmlId;
        $hlClassName = $this->HLBlock->getHlEntityByName('Kontragenty');
        $dbResult = $hlClassName::getList([
            'filter' => $filter
        ]);
        while ($arData = $dbResult->Fetch()) {
            $arData['USE_PORTAL'] = $this->checkUsePortal($arData['ID']);
            $kontragentsList[] = $arData;
        }
        return $kontragentsList;
    }

    protected function getKontragentsFromDBCache($managerXmlId)
    {
        $obCache = new \CPHPCache;
        $managerIds = $this->managerIds;
        sort($managerIds);
        $cacheId = LkCacheManager::getCacheId('getKontragents' . implode('', $managerIds));
        $cacheDir = '/getKontragentsFromDBCache';
        if ($obCache->initCache(LkCacheManager::getCacheTime(), $cacheId, $cacheDir)) {
            $arResult = $obCache->getVars();
        } else if ($obCache->startDataCache()) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cacheDir);
            $CACHE_MANAGER->RegisterTag(LkCacheManager::getTag());
            $CACHE_MANAGER->EndTagCache();
            $arResult = $this->getKontragentsFromDB($managerXmlId);
            $obCache->endDataCache($arResult);
        }

        return $arResult;
    }


    /**
     * Использует ли контрагент портал
     * @param int $id - id элемента в HL блоке 'Контрагенты'
     * @return boolean
     */
    protected function checkUsePortal($id)
    {
        $result = \Bitrix\Main\UserTable::getList([
            'select' => ['ID'],
            'limit' => 1,
            'filter' => ['UF_CONTRAGENT_IDS' => $id, 'ACTIVE' => 'Y']
        ]);

        if ($arUser = $result->Fetch()) {
            return true;
        }
        return false;
    }

    protected function getPdzByKontragent($xmlId)
    {
        if (isset($this->pdz[$xmlId])) {
            return $this->pdz[$xmlId];
        }
        return 0;
    }

    private function getDzByKontragent($xmlId)
    {
        if (isset($this->dz[$xmlId])) {
            return $this->dz[$xmlId];
        }
        return 0;
    }

    protected function getFactByKontragent($xmlId)
    {
        if (isset($this->facts[$xmlId])) {
            return $this->facts[$xmlId];
        }
        return 0;
    }

    public function getManagers()
    {
        return $this->managers;
    }

    public function setMainManagerId($mainManagerId)
    {
        $this->mainManagerId = $mainManagerId;
    }

    public function getStructureForMainManager(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            if ($managerModel->id == $this->mainManagerId) {
                return $managerModel;
            } else {
                foreach ($managerModel->sub as $manager) {
                    $result = $recursion($manager);
                    if ($result !== false) {
                        return $result;
                    }
                }
            }
            return false;
        };

        return $recursion($structureManagers);
    }

    public function sort(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {

            $sortFun = function (AutoPlanModel $f1, AutoPlanModel $f2) {
                $value1 = $value2 = 0;
                switch ($this->sort->getField()) {
                    case 'plan':
                        $value1 = $f1->getPlan();
                        $value2 = $f2->getPlan();
                        break;
                    case 'fact':
                        $value1 = $f1->getFact();
                        $value2 = $f2->getFact();
                        break;
                    case 'portal':
                        $value1 = $f1->isUsePortal();
                        $value2 = $f2->isUsePortal();
                        break;
                    case 'percent':
                        $value1 = $f1->getPercentFact();
                        $value2 = $f2->getPercentFact();
                        break;
                    case 'pdz':
                        $value1 = $f1->getPdz();
                        $value2 = $f2->getPdz();
                        break;
                    case 'client':
                        if ($f1 instanceof KontragentModel) {
                            $value1 = $f1->getName();
                            $value2 = $f2->getName();
                        }
                        break;
                }

                if ($this->sort->getDir() == 'desc') {
                    if ($value1 < $value2) return -1;
                    elseif ($value1 > $value2) return 1;
                    else return 0;
                } else if ($this->sort->getDir() == 'asc') {
                    if ($value1 < $value2) return 1;
                    elseif ($value1 > $value2) return -1;
                    else return 0;
                }
            };
            uasort($managerModel->sub, $sortFun);
            uasort($managerModel->kontragents, $sortFun);
            foreach ($managerModel->sub as $k => $manager) {
                $managerModel->sub[$k] = $recursion($manager);
            }
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    public function setExcludeManager(array $excludeManagerId)
    {
        $this->excludeManagerId = $excludeManagerId;
    }

    public function setExcludeKontragent(array $excludeKontragentId)
    {
        $this->excludeKontragentId = $excludeKontragentId;
    }

    public function buildPlanFactExclude(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            foreach ($managerModel->sub as $manager) {
                $recursion($manager);
            }
            $planNoExclude = $factNoExclude = $planExclude = $factExclude = 0;
            foreach ($managerModel->sub as $manager) {
                if ($manager->isExclude()) {
                    $planExclude += $manager->getPlan();
                    $factExclude += $manager->getFact();
                } else {
                    $planNoExclude += $manager->getPlan();
                    $factNoExclude += $manager->getFact();

                    $planExclude += $manager->getExcludeSumBySubordinates();
                }
            }
            foreach ($managerModel->kontragents as $kontragent) {
                if ($kontragent->isExclude()) {
                    $planExclude += $kontragent->getPlan();
                    $factExclude += $kontragent->getFact();
                } else {
                    $planNoExclude += $kontragent->getPlan();
                    $factNoExclude += $kontragent->getFact();
                }
            }
            if ($managerModel->isExclude()) {
                $managerModel->setAutoPlan($managerModel->getPlan());
            } else {
                $managerModel->setAutoPlan($managerModel->getPlan() - $planExclude);
            }
            $managerModel->setExcludeSumBySubordinates($planExclude);
            if (!$managerModel->isExclude()) {
                $managerModel->setFact($factNoExclude);
            }
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    public function buildPdzDzExclude(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            foreach ($managerModel->sub as $manager) {
                $recursion($manager);
            }
            $pdzNoExclude = $dzNoExclude = $pdzExclude = $dzExclude = 0;
            foreach ($managerModel->sub as $manager) {
                if (!$manager->isExclude()) {
                    $pdzNoExclude += $manager->getPdz();
                    $dzNoExclude += $manager->getDz();
                } else {
                    $pdzExclude += $manager->getPdz();
                    $dzExclude += $manager->getDz();
                }
            }
            foreach ($managerModel->kontragents as $kontragent) {
                if (!$kontragent->isExclude()) {
                    $pdzNoExclude += $kontragent->getPdz();
                    $dzNoExclude += $kontragent->getDz();
                } else {
                    $pdzExclude += $kontragent->getPdz();
                    $dzExclude += $kontragent->getDz();
                }
            }

            $managerModel->setPdz($pdzNoExclude);
            $managerModel->setDz($dzNoExclude);
            return $managerModel;
        };

        return $recursion($structureManagers);
    }

    public function setSort($field, $dir)
    {
        $this->sort = new Sort($field, $dir);
    }

    private function getMainBoss($managerId)
    {
        $hlClassName = $this->HLBlock->getHlEntityByName(Core::HLBLOCK_CODE_STRUCTURE_MANAGERS);
        $recursion = function ($managerId, $hlClassName) use (&$recursion) {
            $dbResult = $hlClassName::getList([
                'filter' => ['UF_ID_SUB_MANAGER' => $managerId],
                'select' => ['UF_ID_MANAGER', 'UF_ID_SUB_MANAGER']
            ]);
            if ($arData = $dbResult->Fetch()) {
                return $recursion($arData['UF_ID_MANAGER'], $hlClassName);
            } else {
                return $managerId;
            }
        };

        return $recursion($managerId, $hlClassName);
    }

    private function checkAccessByMainManager()
    {
        if (in_array($this->mainManagerId, $this->getAllowIdManagers())) {
            return true;
        }
        return false;
    }

    public function deleteEmptyManagers(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            foreach ($managerModel->sub as $k => $manager) {
                $count = $recursion($manager);
                if ($count <= 0) {
                    unset($managerModel->sub[$k]);
                }
            }

            return count($managerModel->kontragents) + count($managerModel->sub);
        };
        $recursion($structureManagers);

        return $structureManagers;
    }

    public function searchManagers(ManagerModel $structureManagers)
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            foreach ($managerModel->sub as $k => $manager) {
                $recursion($manager);
            }
            foreach ($managerModel->kontragents as $k => $kontragents) {
                if (strpos(strtolower($kontragents->getName()), strtolower($this->searchString), 0) === false) {
                    unset($managerModel->kontragents[$k]);
                }
            }
        };
        $recursion($structureManagers);

        return $structureManagers;
    }

    public function setSearchString($q)
    {
        $this->searchString = $q;
    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    public function isSearchString()
    {
        return (bool)$this->searchString;
    }

    protected function extractManagerIdsFromStructure()
    {
        $recursion = function ($idStructureManagers) use (&$recursion) {
            $result[] = $idStructureManagers['ID'];
            foreach ($idStructureManagers['sub'] as $id => $item) {
                $result = array_merge($result, $recursion($item));
            }
            return array_unique($result);
        };

        return $recursion($this->idStructureManagers);
    }
}