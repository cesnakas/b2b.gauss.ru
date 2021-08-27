<?php

namespace Sprint\Migration;


class shortage_template20201230171919 extends Version
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
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_21', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SIMPLE_FORM_21"',
  'DESCRIPTION' => '#RS_FORM_ID# - ID формы
#RS_FORM_NAME# - Имя формы
#RS_FORM_SID# - SID формы
#RS_RESULT_ID# - ID результата
#RS_DATE_CREATE# - Дата заполнения формы
#RS_USER_ID# - ID пользователя
#RS_USER_EMAIL# - EMail пользователя
#RS_USER_NAME# - Фамилия, имя пользователя
#RS_USER_AUTH# - Пользователь был авторизован?
#RS_STAT_GUEST_ID# - ID посетителя
#RS_STAT_SESSION_ID# - ID сессии
#SIMPLE_QUESTION_439# - Номер накладной
#SIMPLE_QUESTION_439_RAW# - Номер накладной (оригинальное значение)
#SIMPLE_QUESTION_234# - Прикрепите файл АКТ – Торг12 
#SIMPLE_QUESTION_234_RAW# - Прикрепите файл АКТ – Торг12  (оригинальное значение)
#SIMPLE_QUESTION_904# - Комментарий
#SIMPLE_QUESTION_904_RAW# - Комментарий (оригинальное значение)
#SIMPLE_QUESTION_267# - Прикрепите фото
#SIMPLE_QUESTION_267_RAW# - Прикрепите фото (оригинальное значение)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_21', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SIMPLE_FORM_21"',
  'DESCRIPTION' => '#RS_FORM_ID# - Form ID
#RS_FORM_NAME# - Form name
#RS_FORM_SID# - Form SID
#RS_RESULT_ID# - Result ID
#RS_DATE_CREATE# - Form filling date
#RS_USER_ID# - User ID
#RS_USER_EMAIL# - User e-mail
#RS_USER_NAME# - First and last user names
#RS_USER_AUTH# - User authorized?
#RS_STAT_GUEST_ID# - Visitor ID
#RS_STAT_SESSION_ID# - Session ID
#SIMPLE_QUESTION_439# - Номер накладной
#SIMPLE_QUESTION_439_RAW# - Номер накладной (original value)
#SIMPLE_QUESTION_234# - Прикрепите файл АКТ – Торг12 
#SIMPLE_QUESTION_234_RAW# - Прикрепите файл АКТ – Торг12  (original value)
#SIMPLE_QUESTION_904# - Комментарий
#SIMPLE_QUESTION_904_RAW# - Комментарий (original value)
#SIMPLE_QUESTION_267# - Прикрепите фото
#SIMPLE_QUESTION_267_RAW# - Прикрепите фото (original value)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_21', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => 'n.kropotova@studiofact.ru',
  'SUBJECT' => '#SERVER_NAME#: заполнена web-форма [#RS_FORM_ID#] #RS_FORM_NAME#',
  'MESSAGE' => '<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
		 Заполнена web-форма: [#RS_FORM_ID#] #RS_FORM_NAME#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="font-size: 14px;border-spacing: 0;padding-left:40px;padding-right:40px;padding-bottom:50px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Дата
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_DATE_CREATE#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Результат
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_RESULT_ID#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Пользователь
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 [#RS_USER_ID#] #RS_USER_NAME# #RS_USER_AUTH#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Посетитель
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_STAT_GUEST_ID#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Сессия
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_STAT_SESSION_ID#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Название контрагента
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #CONTRAGENT_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Номер накладной
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #SIMPLE_QUESTION_439#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Комментарий
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #SIMPLE_QUESTION_904#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp; &nbsp;Файлы: #SIMPLE_QUESTION_234# &nbsp;&nbsp;#SIMPLE_QUESTION_267# <br>
              <br>
		 &nbsp; &nbsp; &nbsp;Для скачивания файлов необходимо авторизоваться на сайте<br>
	</td>
</tr>
</tbody>
</table>
 <br>',
  'BODY_TYPE' => 'html',
  'BCC' => 'n.kropotova@studiofact.ru',
  'REPLY_TO' => '',
  'CC' => '#EMAIL_MANAGER_ASSISTANT_BY_USER#',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => 'email_theme',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => '',
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_21 ] Заполнена web-форма "SIMPLE_FORM_21"',
));
    }

    public function down()
    {
        //your code ...
    }
}
