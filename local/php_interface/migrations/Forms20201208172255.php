<?php

namespace Sprint\Migration;


class Forms20201208172255 extends Version
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
  'NAME' => 'Обратная связь - Претензия',
  'SID' => 'SIMPLE_FORM_13',
  'BUTTON' => 'Отправить',
  'C_SORT' => '100',
  'FIRST_SITE_ID' => NULL,
  'IMAGE_ID' => NULL,
  'USE_CAPTCHA' => 'N',
  'DESCRIPTION' => NULL,
  'DESCRIPTION_TYPE' => 'html',
  'FORM_TEMPLATE' => NULL,
  'USE_DEFAULT_TEMPLATE' => 'Y',
  'SHOW_TEMPLATE' => NULL,
  'MAIL_EVENT_TYPE' => 'FORM_FILLING_SIMPLE_FORM_13',
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
  'STAT_EVENT2' => 'feedback_claim_form',
  'STAT_EVENT3' => NULL,
  'LID' => NULL,
  'C_FIELDS' => '0',
  'QUESTIONS' => '3',
  'STATUSES' => '1',
  'arSITE' => 
  array (
    0 => 's1',
  ),
  'arMENU' => 
  array (
    'ru' => 'Обратная связь - Претензия',
    'en' => 'Обратная связь - Претензия',
  ),
  'arGROUP' => 
  array (
  ),
  'arMAIL_TEMPLATE' => 
  array (
    0 => '98',
    1 => '100',
  ),
));
        $formHelper->saveFields($formId, array (
  0 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Вид претензии',
    'TITLE_TYPE' => 'text',
    'SID' => 'TYPE',
    'C_SORT' => '100',
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
        'MESSAGE' => 'По работе портала',
        'VALUE' => '',
        'FIELD_TYPE' => 'radio',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
      1 => 
      array (
        'MESSAGE' => 'По товарам',
        'VALUE' => '',
        'FIELD_TYPE' => 'radio',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
      2 => 
      array (
        'MESSAGE' => 'По работе менеджера',
        'VALUE' => '',
        'FIELD_TYPE' => 'radio',
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
    'TITLE' => 'Текст сообщения',
    'TITLE_TYPE' => 'text',
    'SID' => 'TEXT',
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
    'TITLE' => 'Прикрепить файл',
    'TITLE_TYPE' => 'text',
    'SID' => 'FILE_1',
    'C_SORT' => '300',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'N',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Прикрепить файл',
    'RESULTS_TABLE_TITLE' => 'Прикрепить файл',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
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

