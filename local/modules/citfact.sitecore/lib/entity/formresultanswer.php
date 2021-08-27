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

class FormResultAnswerTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_form_result_answer';
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
            new Entity\IntegerField('RESULT_ID', array(
                'primary' => true,
                'required' => true,
            )),
            new Entity\IntegerField('FORM_ID', array(
                'required' => false,
            )),
            new Entity\IntegerField('FIELD_ID', array(
                'primary' => true,
                'required' => false,
            )),
            new Entity\IntegerField('ANSWER_ID', array(
                'required' => false,
            )),
            new Entity\TextField('USER_TEXT_SEARCH', array(
                'required' => false,
            )),
            new Entity\StringField('ANSWER_VALUE', array(
                'required' => false,
            )),
            new Entity\StringField('ANSWER_VALUE_SEARCH', array(
                'required' => false,
            )),
            new Entity\ReferenceField('RESULT',
                'Citfact\\SiteCore\\Entity\\FormResultTable',
                [
                    '=this.RESULT_ID' => 'ref.ID',
                ]
            ),
        );
    }
}