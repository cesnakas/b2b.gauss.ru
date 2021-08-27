<?php


namespace Citfact\Sitecore\CatalogHelper;

use Citfact\SiteCore\Core;
use Bitrix\Highloadblock\HighloadBlockTable;


class ListWaitHelper
{
    public function checkProductinListWait($productId)
    {
        global $USER;
        $currentUser = $USER->GetID();
        $core = Core::getInstance();
        $userProducts = [];

        $hlId = $core->getHlBlockId($core::HL_BLOCK_CODE_LIST_WAIT);
        $hlblock = HighloadBlockTable::getById($hlId)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $checkedData = $entity_data_class::getList(array(
            "select" => array("*"),
            'filter' => [
                'UF_USER_ID' => $currentUser,
                'UF_PRODUCT_ID'=>$productId
            ],
        ));
        while ($el = $checkedData->Fetch()) {
            $userProducts[] = $el['UF_PRODUCT_ID'];
        }
          return $userProducts;
    }
}