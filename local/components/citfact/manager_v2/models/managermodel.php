<?php

namespace Plan\Models;


class ManagerModel extends AutoPlanModel
{
    public $id;
    public $info;
    /**
     * @var ManagerModel[]
     */
    public $sub = [];
    /**
     * @var KontragentModel[]
     */
    public $kontragents = [];
    private $excludeSumBySubordinates = 0;

    /**
     *Кол-во непосредственно прикрепленных контракгентов и менеджеров без плана
     */
    public function countSubordinatesWithoutPlan()
    {
        $count = 0;
        foreach ($this->sub as $managerModel) {
            if ($managerModel->isCalculated() && !$managerModel->existHardSetPlanBySubordinates()) {
                $count++;
            }
        }
        foreach ($this->kontragents as $kontragent) {
            if ($kontragent->isCalculated()) {
                $count++;
            }
        }
        return $count;
    }

    public function getSumPlanSubordinatesWithPlan()
    {
        $sum = 0;
        foreach ($this->sub as $managerModel) {
            if (!$managerModel->isCalculated() || $managerModel->existHardSetPlanBySubordinates()) {
                $sum += $managerModel->getPlan();
            }
        }
        foreach ($this->kontragents as $kontragent) {
            if (!$kontragent->isCalculated()) {
                $sum += $kontragent->getPlan();
            }
        }
        return $sum;
    }

    public function getCalculatedAutoplan()
    {
        if ($this->isCalculated()) {
            $planBoss = $this->getPlan();
        } else {
            $planBoss = $this->getGeneralPlan();
        }
        $countSubordinatesWithoutPlanBoss = $this->countSubordinatesWithoutPlan();
        $planSubordinatesWithPlanBoss = $this->getSumPlanSubordinatesWithPlan();
        $autoplan = ($planBoss - $planSubordinatesWithPlanBoss) / $countSubordinatesWithoutPlanBoss;

        return $autoplan;
    }

    public function getName()
    {
        return $this->info['UF_NAME'];
    }

    public function getSumHardPlanBySubordinates()
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            $sum = 0;
            foreach ($managerModel->kontragents as $kontragent) {
                if ($kontragent->getPlan() > 0 && !$kontragent->isCalculated()) {
                    $sum += $kontragent->getPlan();
                }
            }
            foreach ($managerModel->sub as $manager) {
                if ($manager->getPlan() > 0 && !$manager->isCalculated()) {
                    $sum += $manager->getPlan();
                } else {
                    $sum += $recursion($manager);
                }
            }
            return $sum;
        };

        return $recursion($this);
    }

    public function getSaldo()
    {
        $isCalculated = false;
        $sumNotExclude = $sumExclude = 0;
        foreach ($this->kontragents as $kontragent) {
            if ($kontragent->isCalculated()) {
                $isCalculated = true;
            }
            if (!$kontragent->isExclude()) {
                $sumNotExclude += $kontragent->getPlan();
            } else {
                $sumExclude += $kontragent->getPlan();
            }
        }
        foreach ($this->sub as $manager) {
            if ($manager->isCalculated()) {
                $isCalculated = true;
            }
            if (!$manager->isExclude()) {
                $sumNotExclude += $manager->getPlan();
            } else {
                $sumExclude += $manager->getPlan();
            }
        }
        if (!$isCalculated) {
            if (($sumExclude + $sumNotExclude) == $sumExclude) {
                return $this->getPlan() - $sumExclude;
            } else {
                return $this->getPlan() - $sumNotExclude;
            }
        }
        return 0;
    }

    public function getDataForAjax($showSaldo = true)
    {
        $kontragents = $managers = [];
        $recursion = function (ManagerModel $managerModel) use (&$recursion, &$kontragents, &$managers, &$showSaldo) {
            foreach ($managerModel->kontragents as $kontragent) {
                $kontragents[$kontragent->id] = [
                    'plan' => $kontragent->getPlan(),
                    'fact' => $kontragent->getFact(),
                    'percent' => $kontragent->getPercentFact()
                ];
            }
            $managers[$managerModel->id] = [
                'plan' => $managerModel->getPlan(),
                'fact' => $managerModel->getFact(),
                'percent' => $managerModel->getPercentFact(),
                'saldo' => (($showSaldo) ? $managerModel->getSaldo() : 0)
            ];
            foreach ($managerModel->sub as $manager) {
                $recursion($manager);
            }
            return [
                'managers' => $managers,
                'kontragent' => $kontragents,
                'planChart' => $managerModel->getPlan(),
                'factChart' => $managerModel->getFact(),
                'pdzChart' => $managerModel->getPdz(),
                'dzChart' => $managerModel->getDz(),
            ];
        };

        return $recursion($this);
    }

    public function setExcludeSumBySubordinates($planExclude)
    {
        $this->excludeSumBySubordinates = $planExclude;
    }

    public function getExcludeSumBySubordinates()
    {
        return $this->excludeSumBySubordinates;
    }

    public function existHardSetPlanBySubordinates()
    {
        $recursion = function (ManagerModel $managerModel) use (&$recursion) {
            foreach ($managerModel->kontragents as $kontragent) {
                if (!$kontragent->isCalculated()) {
                    return true;
                }
            }
            foreach ($managerModel->sub as $manager) {
                $existSub = $recursion($manager);
                if ($existSub) {
                    return true;
                }
                if (!$manager->isCalculated()) {
                    return true;
                }
            }
            return false;
        };

        return $recursion($this);
    }

    /**
     * @return int
     */
    public function getPlan()
    {
        if ($this->autoPlan > 0 || $this->getExcludeSumBySubordinates() > 0) {
            return $this->autoPlan;
        }
        return $this->plan;
    }
}