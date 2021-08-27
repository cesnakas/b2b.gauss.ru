<?php
namespace Citfact\DataCache\PaymentData;

use Bitrix\Main\Loader,
    Citfact\DataCache\DataID;

class PaymentId extends DataID
{
    protected $codeCache = 'payment';

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        if (!Loader::includeModule('sale'))
            return array();

        $arPaySystems = array();
        $db_ptype = \CSalePaySystem::GetList(Array(), Array("ACTIVE"=>"Y"), false, false, array('ID', 'CODE'));
        while ($ptype = $db_ptype->Fetch())
        {
            $arPaySystems[$ptype['CODE']] = $ptype['ID'];
        }

        return $arPaySystems;
    }
}
