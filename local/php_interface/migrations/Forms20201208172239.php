<?php

namespace Sprint\Migration;


class Forms20201208172239 extends Version
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
        $formHelper = $helper->Form();
        $formId = $formHelper->saveForm(array (
  'NAME' => 'Заявка на возврат/обмен товара',
  'SID' => 'SIMPLE_FORM_10',
  'BUTTON' => 'Отправить заявку',
  'C_SORT' => '100',
  'FIRST_SITE_ID' => NULL,
  'IMAGE_ID' => NULL,
  'USE_CAPTCHA' => 'N',
  'DESCRIPTION' => NULL,
  'DESCRIPTION_TYPE' => 'html',
  'FORM_TEMPLATE' => NULL,
  'USE_DEFAULT_TEMPLATE' => 'Y',
  'SHOW_TEMPLATE' => NULL,
  'MAIL_EVENT_TYPE' => 'FORM_FILLING_SIMPLE_FORM_10',
  'SHOW_RESULT_TEMPLATE' => NULL,
  'PRINT_RESULT_TEMPLATE' => NULL,
  'EDIT_RESULT_TEMPLATE' => NULL,
  'FILTER_RESULT_TEMPLATE' => NULL,
  'TABLE_RESULT_TEMPLATE' => NULL,
  'USE_RESTRICTIONS' => 'N',
  'RESTRICT_USER' => '0',
  'RESTRICT_TIME' => '0',
  'RESTRICT_STATUS' => NULL,
  'STAT_EVENT1' => 'form',
  'STAT_EVENT2' => 'purchase_returns_form',
  'STAT_EVENT3' => NULL,
  'LID' => NULL,
  'C_FIELDS' => '0',
  'QUESTIONS' => '13',
  'STATUSES' => '1',
  'arSITE' => 
  array (
    0 => 's1',
  ),
  'arMENU' => 
  array (
    'ru' => 'Заявка на возврат/обмен товара',
    'en' => 'Заявка на возврат/обмен товара',
  ),
  'arGROUP' => 
  array (
  ),
  'arMAIL_TEMPLATE' => 
  array (
    0 => '95',
  ),
));
        $formHelper->saveFields($formId, array (
  0 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Основание возврата товара (номер счета/накладной)',
    'TITLE_TYPE' => 'text',
    'SID' => 'NUMBER',
    'C_SORT' => '100',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Основание возврата товара (номер счета/накладной)',
    'RESULTS_TABLE_TITLE' => 'Основание возврата товара (номер счета/накладной)',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'text',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  1 => 
  array (
    'ACTIVE' => 'N',
    'TITLE' => 'Укажите причину возврата/обмена',
    'TITLE_TYPE' => 'text',
    'SID' => 'REASON',
    'C_SORT' => '200',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'textarea',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  2 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Комментарий',
    'TITLE_TYPE' => 'text',
    'SID' => 'COMMENT',
    'C_SORT' => '300',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'N',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'textarea',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  3 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_1',
    'C_SORT' => '400',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  4 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_2',
    'C_SORT' => '500',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  5 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_3',
    'C_SORT' => '600',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  6 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_4',
    'C_SORT' => '700',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  7 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_5',
    'C_SORT' => '800',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  8 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_6',
    'C_SORT' => '900',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  9 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_7',
    'C_SORT' => '1000',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  10 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_8',
    'C_SORT' => '1100',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  11 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_9',
    'C_SORT' => '1200',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  12 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Выбрать файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_10',
    'C_SORT' => '1300',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'N',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => NULL,
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => NULL,
    'RESULTS_TABLE_TITLE' => NULL,
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => NULL,
        'FIELD_TYPE' => 'file',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
));
    }

    public function down()
    {
        //your code ...
    }
}

