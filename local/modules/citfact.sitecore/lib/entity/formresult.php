<?php
namespace Citfact\SiteCore\Entity;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Entity;


Loc::loadMessages(__FILE__);

/**
 * Class DebitorskayazadolzhennostTable
 *
 * @package Bitrix\Debitorskayazadolzhennost
 **/

class FormResultTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_form_result';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'required' => true,
            )),
            new Entity\IntegerField('USER_ID', array(
                'primary' => true,
                'required' => false,
            )),
            new Entity\IntegerField('FORM_ID', array(
                'required' => false,
            )),
            new Entity\StringField('TIMESTAMP_X', array(
                'required' => false,
            )),
            new Entity\StringField('DATE_CREATE', array(
                'required' => false,
            )),
            new Entity\StringField('USER_AUTH', array(
                'required' => false,
            )),
        );
    }
}