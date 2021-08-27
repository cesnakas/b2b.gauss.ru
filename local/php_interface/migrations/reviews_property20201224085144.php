<?php

namespace Sprint\Migration;


class reviews_property20201224085144 extends Version
{
    protected $description = "обзоры - свойство для разделов ";

    protected $moduleVersion = "3.17.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_1c_catalog:main_catalog_SECTION',
  'FIELD_NAME' => 'UF_SECTION_REVIEW',
  'USER_TYPE_ID' => 'hlblock',
  'XML_ID' => '',
  'SORT' => '100',
  'MULTIPLE' => 'Y',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 5,
    'HLBLOCK_ID' => 'Reviews',
    'HLFIELD_ID' => 'UF_LINK',
    'DEFAULT_VALUE' => 0,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Video review link',
    'ru' => 'Ссылка на видео-обзор ',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
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
        //your code ...
    }
}
