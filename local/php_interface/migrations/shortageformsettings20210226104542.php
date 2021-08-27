<?php

namespace Sprint\Migration;


class shortageformsettings20210226104542 extends Version
{
    protected $description = "настройки прав доступа";

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
  'NAME' => 'Заявка на недостачу/пересорт',
  'SID' => 'SIMPLE_FORM_21',
  'BUTTON' => 'Отправить заявку',
  'C_SORT' => '200',
  'FIRST_SITE_ID' => NULL,
  'IMAGE_ID' => NULL,
  'USE_CAPTCHA' => 'N',
  'DESCRIPTION' => '',
  'DESCRIPTION_TYPE' => 'text',
  'FORM_TEMPLATE' => '',
  'USE_DEFAULT_TEMPLATE' => 'Y',
  'SHOW_TEMPLATE' => NULL,
  'MAIL_EVENT_TYPE' => 'FORM_FILLING_SIMPLE_FORM_21',
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
  'STAT_EVENT2' => 'shortage-form',
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
    'ru' => 'Заявка на недостачу/пересорт',
    'en' => 'shortage claim ',
  ),
  'arGROUP' => 
  array (
    'everyone' => '15',
    'RATING_VOTE' => '15',
    'RATING_VOTE_AUTHORITY' => '15',
    'ASSISTANT' => '15',
    'content_manager' => '15',
    'MANAGER' => '15',
    'MAIL_INVITED' => '15',
  ),
  'arMAIL_TEMPLATE' => 
  array (
    0 => '129',
  ),
));
        $formHelper->saveStatuses($formId, array (
  0 => 
  array (
    'CSS' => 'statusgreen',
    'C_SORT' => '100',
    'ACTIVE' => 'Y',
    'TITLE' => 'DEFAULT',
    'DESCRIPTION' => 'DEFAULT',
    'DEFAULT_VALUE' => 'Y',
    'HANDLER_OUT' => NULL,
    'HANDLER_IN' => NULL,
  ),
));
        $formHelper->saveFields($formId, array (
  0 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Номер накладной',
    'TITLE_TYPE' => 'text',
    'SID' => 'INVOICE_NUMBER',
    'C_SORT' => '100',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => NULL,
    'FILTER_TITLE' => 'Номер накладной',
    'RESULTS_TABLE_TITLE' => 'Номер накладной',
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
    'TITLE' => 'Прикрепите файл АКТ – Торг12 ',
    'TITLE_TYPE' => 'text',
    'SID' => 'SIMPLE_QUESTION_234',
    'C_SORT' => '200',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Прикрепите файл АКТ – Торг12 ',
    'RESULTS_TABLE_TITLE' => 'Прикрепите файл АКТ – Торг12 ',
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
  2 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Комментарий',
    'TITLE_TYPE' => 'text',
    'SID' => 'SIMPLE_QUESTION_904',
    'C_SORT' => '300',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'N',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Комментарий',
    'RESULTS_TABLE_TITLE' => 'Комментарий',
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
    'TITLE' => 'Прикрепите фото',
    'TITLE_TYPE' => 'text',
    'SID' => 'SIMPLE_QUESTION_267',
    'C_SORT' => '400',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'N',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => 'Прикрепите фото',
    'RESULTS_TABLE_TITLE' => 'Прикрепите фото',
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

