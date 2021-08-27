<?php

namespace Sprint\Migration;


class Plans_for_months20201112161622 extends Version
{
    protected $description = "";

    protected $moduleVersion = "3.17.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'PlanFactManager']
        ])->fetch();
        $hlblockId = $hlblock['ID'];

        $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_JANUARY',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за январь',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за январь',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за январь',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_FEBRUARY',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за февраль',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за февраль',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за февраль',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_MARCH',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за март',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за март',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за март',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_APRIL',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за апрель',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за апрель',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за апрель',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_MAY',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за май',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за май',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за май',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_JUNE',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за июнь',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за июнь',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за июнь',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_JULY',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за июль',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за июль',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за июль',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_AUGUST',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за август',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за август',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за август',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_SEPTEMBER',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за сентябрь',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за сентябрь',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за сентябрь',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_OCTOBER',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за октябрь',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за октябрь',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за октябрь',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_NOVEMBER',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за ноябрь',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за ноябрь',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за ноябрь',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    $helper->Hlblock()->saveField($hlblockId, array (
        'FIELD_NAME' => 'UF_DECEMBER',
        'USER_TYPE_ID' => 'double',
        'XML_ID' => '',
        'SORT' => '100',
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => 
        array (
            'PRECISION' => 4,
            'SIZE' => 20,
            'MIN_VALUE' => 0.0,
            'MAX_VALUE' => 0.0,
            'DEFAULT_VALUE' => '',
        ),
        'EDIT_FORM_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за декабрь',
        ),
        'LIST_COLUMN_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за декабрь',
        ),
        'LIST_FILTER_LABEL' => 
        array (
            'en' => '',
            'ru' => 'План за декабрь',
        ),
        'ERROR_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
        'HELP_MESSAGE' => 
        array (
            'en' => '',
            'ru' => '',
        ),
    ));
    }

    public function down()
    {
        $fields = ['UF_JANUARY', 'UF_FEBRUARY',  'UF_MARCH', 'UF_APRIL', 'UF_MAY', 'UF_JUNE', 'UF_JULY', 'UF_AUGUST', 'UF_SEPTEMBER', 'UF_OCTOBER', 'UF_NOVEMBER', 'UF_DECEMBER'];
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'PlanFactManager']
        ])->fetch();
        $entity = 'HLBLOCK_' . $hlblock['ID'];
        $rsData = \CUserTypeEntity::GetList([$by => $order], ['ENTITY_ID' => $entity]);
        $oUserTypeEntity = new \CUserTypeEntity();
        while ($arRes = $rsData->Fetch()) {
            if (in_array($arRes['FIELD_NAME'], $fields)) {
                $oUserTypeEntity->Delete($arRes['ID']);  
            }
        }
    }
}
