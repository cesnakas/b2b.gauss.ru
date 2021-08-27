<?php

namespace Citfact\Tools\UserField;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\Validator\Length;

Loc::loadMessages(__FILE__);

class UserFieldEnumTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_user_field_enum';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => new IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('FIELD_ENUM_ENTITY_ID_FIELD'),
            )),
            'USER_FIELD_ID' => new IntegerField('USER_FIELD_ID', array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('FIELD_ENUM_ENTITY_USER_FIELD_ID_FIELD'),
            )),
            'VALUE' => new TextField('VALUE', array(
                'required' => true,
                'validation' => array(__CLASS__, 'validateValue'),
                'title' => Loc::getMessage('FIELD_ENUM_ENTITY_VALUE_FIELD'),
            )),
            'DEF' => new BooleanField('DEF', array(
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENUM_ENTITY_DEF_FIELD'),
            )),
            'SORT' => new IntegerField('SORT', array(
                'title' => Loc::getMessage('FIELD_ENUM_ENTITY_SORT_FIELD'),
            )),
            'XML_ID' => new TextField('XML_ID', array(
                'required' => true,
                'validation' => array(__CLASS__, 'validateXmlId'),
                'title' => Loc::getMessage('FIELD_ENUM_ENTITY_XML_ID_FIELD'),
            )),
            'USER_FIELD' => new ReferenceField(
                'USER_FIELD',
                'Bitrix\Main\UserFieldTable',
                array('=this.USER_FIELD_ID' => 'ref.ID')
            ),
        );
    }

    /**
     * Returns validators for VALUE field.
     *
     * @return array
     */
    public static function validateValue()
    {
        return array(
            new Length(null, 255),
        );
    }

    /**
     * Returns validators for XML_ID field.
     *
     * @return array
     */
    public static function validateXmlId()
    {
        return array(
            new Length(null, 255),
        );
    }
}
