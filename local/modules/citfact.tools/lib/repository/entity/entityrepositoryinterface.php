<?php

namespace Citfact\Tools\Repository\Entity;

use Bitrix\Main\Entity\DataManager;

interface EntityRepositoryInterface
{
    /**
     * @return DataManager
     */
    public function getEntity();
}
