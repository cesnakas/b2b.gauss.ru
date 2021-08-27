<?php
namespace Citfact\Sitecore\Location;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class LocTypeTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> CODE string(30) mandatory
 * <li> SORT int optional default 100
 * <li> DISPLAY_SORT int optional default 100
 * </ul>
 *
 * @package Bitrix\Sale
 **/

class LocTypeTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_sale_loc_type';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('LOC_TYPE_ENTITY_ID_FIELD'),
            ),
            'CODE' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateCode'),
                'title' => Loc::getMessage('LOC_TYPE_ENTITY_CODE_FIELD'),
            ),
            'SORT' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('LOC_TYPE_ENTITY_SORT_FIELD'),
            ),
            'DISPLAY_SORT' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('LOC_TYPE_ENTITY_DISPLAY_SORT_FIELD'),
            ),
        );
    }

    /**
     * Returns validators for CODE field.
     *
     * @return array
     * @throws Main\ArgumentTypeException
     */
    public static function validateCode()
    {
        return array(
            new Main\Entity\Validator\Length(null, 30),
        );
    }
}