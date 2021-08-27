<?php
namespace Citfact\DataCache\UserData;

use \Citfact\DataCache\DataID,
    \Bitrix\Main\GroupTable;

class UserGroupID extends DataID
{
    protected $codeCache = 'usergroup';

    /**
     * return $dataByCode = array('CODE' => 'ID')
     */
    protected function setData()
    {
        $groupCodeID = array();

        $resGroup = GroupTable::getList(array(
            'filter' => array('ACTIVE' => 'Y'),
            'select' => array('ID', 'STRING_ID'),
        ));
        while ($arGroup = $resGroup->fetch()) {
            $groupCodeID[$arGroup['STRING_ID']] = $arGroup['ID'];
        }

        return $groupCodeID;
    }
}



