<?php

namespace Plan;

use Citfact\SiteCore\Core;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\SiteCore\UserDataManager\UserDataManager;

class PlanDataProvider
{
    protected $period;
    protected $isDefaultPeriod;
    /** @var HLBlock */
    protected $HLBlock;
    protected $curManagerXmlId;
    protected $curManagerId;
    /** @var UserDataManager */
    protected $userDataManager;
    /** @var StructureBuilder */
    public $structureBuilder;
    /**
     * @var PlanDbStory
     */
    public $planDbStory;
    protected $mainManagerId;
    private $excludeManagerId = [];
    private $excludeKontragentId = [];

    public function __construct($period, $isDefaultPeriod)
    {
        require_once __DIR__ . '/plandbstory.php';
        require_once __DIR__ . '/structurebuilder.php';
        require_once __DIR__ . '/models/autoplanmodel.php';
        require_once __DIR__ . '/models/managermodel.php';
        require_once __DIR__ . '/models/kontragentmodel.php';
        require_once __DIR__ . '/sort.php';
        $this->planDbStory = new PlanDbStory();
        $this->HLBlock = new HLBlock();
        $this->userDataManager = new UserDataManager();
        $this->period = $period;
        $this->isDefaultPeriod = $isDefaultPeriod;
        $this->curManagerXmlId = $this->getCurManagerXmlId();
        $this->setCurManagerId();
        $this->mainManagerId = $this->getCurManagerId();
        $this->structureBuilder = new StructureBuilder($this->curManagerId, $this->period, $this->isDefaultPeriod);
    }

    public function getManagerModel()
    {
        $this->structureBuilder->setMainManagerId($this->mainManagerId);
        $managerModel = $this->structureBuilder->getManagerModel();
        $managerModel = $this->structureBuilder->buildFactForManagers($managerModel);
        $managerModel = $this->structureBuilder->buildPdzForManagers($managerModel);
        $managerModel = $this->structureBuilder->buildDzForManagers($managerModel);
        $managerModel = $this->structureBuilder->buildAutoPlan($managerModel);
        $managerModel = $this->structureBuilder->getStructureForMainManager($managerModel);
        $managerModel = $this->structureBuilder->buildPlanFactExclude($managerModel);
        $managerModel = $this->structureBuilder->buildPdzDzExclude($managerModel);
        if (!empty($this->structureBuilder->getSearchString())) {
            $managerModel = $this->structureBuilder->searchManagers($managerModel);
            $managerModel = $this->structureBuilder->deleteEmptyManagers($managerModel);
        }
        $managerModel = $this->structureBuilder->sort($managerModel);

        return $managerModel;
    }

    protected function getCurManagerXmlId()
    {
        $rsUser = \CUser::GetByID(\CUser::GetID());
        $arUser = $rsUser->Fetch();
        $hlClassName = $this->HLBlock->getHlEntityByName('Menedzhery');
        $dbResult = $hlClassName::getList([
            'filter' => ['ID' => $arUser['UF_MANAGER_ID']]
        ]);
        if ($arData = $dbResult->Fetch()) {
            return $arData['UF_XML_ID'];
        }
        return false;
    }

    protected function setCurManagerId()
    {
        $rsUser = \CUser::GetByID(\CUser::GetID());
        $arUser = $rsUser->Fetch();
        $hlClassName = $this->HLBlock->getHlEntityByName('Menedzhery');
        $dbResult = $hlClassName::getList([
            'filter' => ['ID' => $arUser['UF_MANAGER_ID']]
        ]);
        if ($arData = $dbResult->Fetch()) {
            $this->curManagerId = $arData['ID'];
        } else {
            $this->curManagerId = false;
        }
    }

    public function getCurManagerId()
    {
        return $this->curManagerId;
    }

    public function getManagers()
    {
        return $this->structureBuilder->getManagers();
    }

    public function setMainManagerId($mainManagerId)
    {
        $this->mainManagerId = $mainManagerId;
        $this->structureBuilder->setMainManagerId($mainManagerId);
    }

    public function setExcludeManager(array $excludeManagerId)
    {
        $this->excludeManagerId = $excludeManagerId;
        $this->structureBuilder->setExcludeManager($excludeManagerId);
    }

    public function setExcludeKontragent(array $excludeKontragentId)
    {
        $this->excludeKontragentId = $excludeKontragentId;
        $this->structureBuilder->setExcludeKontragent($excludeKontragentId);
    }

    public function setSort($field, $dir)
    {
        $this->structureBuilder->setSort($field, $dir);
    }

}