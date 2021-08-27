<?php

namespace Plan\Models;


class KontragentModel extends AutoPlanModel
{
    public $id;
    public $info;

    public function getName()
    {
        return $this->info['UF_NAME'];
    }

    public function getUrl()
    {
        return '/personal/detail/' . $this->info['UF_XML_ID'] .'/';
    }

    public function isUsePortal()
    {
        return $this->info['USE_PORTAL'];
    }
}