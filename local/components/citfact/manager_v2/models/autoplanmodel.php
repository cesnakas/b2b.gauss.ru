<?php


namespace Plan\Models;


abstract class AutoPlanModel
{
    protected $plan = 0;
    protected $fact = 0;
    protected $autoPlan = 0;
    protected $dz = 0;
    protected $pdz = 0;
    protected $isCalculated = false;
    protected $isExclude = false;

    /**
     * @return int
     */
    public function getPlan()
    {
        if ($this->autoPlan > 0) {
            return $this->autoPlan;
        }
        return $this->plan;
    }

    /**
     * @return int
     */
    public function getGeneralPlan()
    {
        return $this->plan;
    }

    /**
     * @param int $plan
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;
    }

    /**
     * @param int $autoPlan
     */
    public function setAutoPlan($autoPlan)
    {
        $this->autoPlan = $autoPlan;
    }

    /**
     * @return bool
     */
    public function isCalculated()
    {
        return $this->isCalculated;
    }

    /**
     * @param bool $isCalculated
     */
    public function setIsCalculated($isCalculated)
    {
        $this->isCalculated = $isCalculated;
    }

    /**
     * @param int $fact
     */
    public function setFact($fact)
    {
        $this->fact = $fact;
    }

    public function setPdz($pdzByKontragent)
    {
        $this->pdz = $pdzByKontragent;
    }

    public function getPdz()
    {
        return $this->pdz;
    }

    public function getDz()
    {
        return $this->dz;
    }

    public function setDz($dz)
    {
        $this->dz = $dz;
    }

    public function getFact()
    {
        return $this->fact;
    }

    public function getPercentFact()
    {
        if ($this->getPlan() != 0) {
            return round($this->getFact() / $this->getPlan() * 100);
        }
        return 0;
    }

    /**
     * @return bool
     */
    public function isExclude()
    {
        return $this->isExclude;
    }

    /**
     * @param bool $isExclude
     */
    public function setIsExclude($isExclude)
    {
        $this->isExclude = $isExclude;
    }

    public function isUsePortal()
    {
        return false;
    }

    public function getColorProgressLine()
    {
        $percent = $this->getPercentFact();
        if ($percent <= 25) {
            $color = 'red'; // красный
        } elseif ($percent <= 50) {
            $color = '#f7971d'; // оранжевый
        } elseif ($percent <= 75) {
            $color = 'yellow'; // желтый
        } else {
            $color = '#40d24a'; // зеленый
        }
        return $color;
    }

    abstract public function getName();
}