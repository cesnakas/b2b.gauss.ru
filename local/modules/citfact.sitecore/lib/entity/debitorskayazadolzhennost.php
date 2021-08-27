<?php
namespace Citfact\SiteCore\Entity;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class DebitorskayazadolzhennostTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_NAME string optional
 * <li> UF_XML_ID string optional
 * <li> UF_VERSION string optional
 * <li> UF_DESCRIPTION string optional
 * <li> UF_KONTRAGENT string optional
 * <li> UF_ZAKAZ string optional
 * <li> UF_SUMMA string optional
 * <li> UF_NOMER string optional
 * <li> UF_DATA string optional
 * <li> UF_SUMMAPROSROCHENO double optional
 * <li> UF_DNEYPROSROCHENO double optional
 * <li> UF_DATAOPLATY string optional
 * </ul>
 *
 * @package Bitrix\Debitorskayazadolzhennost
 **/

class DebitorskayazadolzhennostTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_debitorskayazadolzhennost';
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
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_ID_FIELD'),
            ),
            'UF_NAME' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_NAME_FIELD'),
            ),
            'UF_XML_ID' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_XML_ID_FIELD'),
            ),
            'UF_VERSION' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_VERSION_FIELD'),
            ),
            'UF_DESCRIPTION' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_DESCRIPTION_FIELD'),
            ),
            'UF_KONTRAGENT' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_KONTRAGENT_FIELD'),
            ),
            'UF_ZAKAZ' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_ZAKAZ_FIELD'),
            ),
            'UF_SUMMA' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_SUMMA_FIELD'),
            ),
            'UF_NOMER' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_NOMER_FIELD'),
            ),
            'UF_DATA' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_DATA_FIELD'),
            ),
            'UF_SUMMAPROSROCHENO' => array(
                'data_type' => 'float',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_SUMMAPROSROCHENO_FIELD'),
            ),
            'UF_DNEYPROSROCHENO' => array(
                'data_type' => 'float',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_DNEYPROSROCHENO_FIELD'),
            ),
            'UF_DATAOPLATY' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_DATAOPLATY_FIELD'),
            ),
            'UF_TIME' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('DEBITORSKAYAZADOLZHENNOST_ENTITY_UF_TIME'),
            ),
//            new \Bitrix\Main\Entity\ReferenceField(
//                'UF_ORDER',
//                '\Bitrix\Sale\Internals\OrderTable',
//                array('=this.UF_ZAKAZ' => 'ref.ID_1C')
//            )
        );
    }
}