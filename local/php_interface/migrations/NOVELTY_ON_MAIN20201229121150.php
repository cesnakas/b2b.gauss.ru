<?php

namespace Sprint\Migration;


class NOVELTY_ON_MAIN20201229121150 extends Version
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
        $iblockId = $helper->Iblock()->getIblockIdIfExists('main_catalog', '1c_catalog');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Отображать на главной',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'MAIN_NOVELTY',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
  'VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Да',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'Y',
    ),
  ),
));
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Дата начала нахождения на главной',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'NOVELTY_ACTIVE_FROM',
  'DEFAULT_VALUE' => NULL,
  'PROPERTY_TYPE' => 'S',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => 'DateTime',
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Дата окончания нахождения на главной',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'NOVELTY_ACTIVE_TO',
  'DEFAULT_VALUE' => NULL,
  'PROPERTY_TYPE' => 'S',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => 'DateTime',
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));

    }

    public function down()
    {
        //your code ...
    }
}
