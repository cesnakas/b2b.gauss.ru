<?php

namespace Sprint\Migration;


class webform620210416113635 extends Version
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
  'NAME' => 'Если у вас остались вопросы, задайте их нам',
  'SID' => 'SIMPLE_FORM_6',
  'BUTTON' => 'Написать',
  'C_SORT' => '100',
  'FIRST_SITE_ID' => NULL,
  'IMAGE_ID' => NULL,
  'USE_CAPTCHA' => 'N',
  'DESCRIPTION' => '',
  'DESCRIPTION_TYPE' => 'html',
  'FORM_TEMPLATE' => '',
  'USE_DEFAULT_TEMPLATE' => 'Y',
  'SHOW_TEMPLATE' => NULL,
  'MAIL_EVENT_TYPE' => 'FORM_FILLING_SIMPLE_FORM_6',
  'SHOW_RESULT_TEMPLATE' => NULL,
  'PRINT_RESULT_TEMPLATE' => NULL,
  'EDIT_RESULT_TEMPLATE' => NULL,
  'FILTER_RESULT_TEMPLATE' => NULL,
  'TABLE_RESULT_TEMPLATE' => NULL,
  'USE_RESTRICTIONS' => 'N',
  'RESTRICT_USER' => '0',
  'RESTRICT_TIME' => '0',
  'RESTRICT_STATUS' => '',
  'STAT_EVENT1' => 'form',
  'STAT_EVENT2' => 'feedback_form',
  'STAT_EVENT3' => '',
  'LID' => NULL,
  'C_FIELDS' => '0',
  'QUESTIONS' => '4',
  'STATUSES' => '1',
  'arSITE' => 
  array (
    0 => 's1',
  ),
  'arMENU' => 
  array (
    'ru' => 'Форма обратной связи',
    'en' => 'Форма обратной связи',
  ),
  'arGROUP' => 
  array (
  ),
  'arMAIL_TEMPLATE' => 
  array (
    0 => '119',
  ),
));
        $formHelper->saveFields($formId, array (
  0 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Ваше имя',
    'TITLE_TYPE' => 'text',
    'SID' => 'SIMPLE_QUESTION_984',
    'C_SORT' => '100',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Ваше имя',
    'RESULTS_TABLE_TITLE' => 'Ваше имя',
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
    'ACTIVE' => 'Y',
    'TITLE' => 'Ваш телефон',
    'TITLE_TYPE' => 'text',
    'SID' => 'PHONE',
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
  2 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'E-mail',
    'TITLE_TYPE' => 'text',
    'SID' => 'SIMPLE_QUESTION_312',
    'C_SORT' => '300',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'E-mail',
    'RESULTS_TABLE_TITLE' => 'E-mail',
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
  3 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Текст сообщения',
    'TITLE_TYPE' => 'text',
    'SID' => 'COMMENT',
    'C_SORT' => '400',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Текст сообщения',
    'RESULTS_TABLE_TITLE' => 'Текст сообщения',
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
));
    }

    public function down()
    {
        //your code ...
    }
}

