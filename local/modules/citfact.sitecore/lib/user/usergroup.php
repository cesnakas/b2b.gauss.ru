<?php
namespace Citfact\SiteCore\User;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class GroupTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> GROUP_ID int mandatory
 * <li> DATE_ACTIVE_FROM datetime optional
 * <li> DATE_ACTIVE_TO datetime optional
 * </ul>
 *
 * @package Bitrix\User
 **/

class UserGroupTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_user_group';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'USER_ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'title' => Loc::getMessage('GROUP_ENTITY_USER_ID_FIELD'),
            ),
            'GROUP_ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'title' => Loc::getMessage('GROUP_ENTITY_GROUP_ID_FIELD'),
            ),
            'DATE_ACTIVE_FROM' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('GROUP_ENTITY_DATE_ACTIVE_FROM_FIELD'),
            ),
            'DATE_ACTIVE_TO' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('GROUP_ENTITY_DATE_ACTIVE_TO_FIELD'),
            ),
        );
    }
}