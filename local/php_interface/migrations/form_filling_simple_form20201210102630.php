<?php

namespace Sprint\Migration;


class form_filling_simple_form20201210102630 extends Version
{
    protected $description = "Фразы по авторизации";

    protected $moduleVersion = "3.17.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_12', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SIMPLE_FORM_12"',
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
#TEXT# - Текст сообщения
#TEXT_RAW# - Текст сообщения (оригинальное значение)
#SIMPLE_QUESTION_499# - 
#SIMPLE_QUESTION_499_RAW# -  (оригинальное значение)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_12', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SIMPLE_FORM_12"',
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
#TEXT# - Текст сообщения
#TEXT_RAW# - Текст сообщения (original value)
#SIMPLE_QUESTION_499# - 
#SIMPLE_QUESTION_499_RAW# -  (original value)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_12', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => ' #EMAIL_MANAGER_ASSISTANT_BY_USER#',
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
		 Текст сообщения
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #TEXT#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp; &nbsp;Файлы: #SIMPLE_QUESTION_499#&nbsp; <br>
 <br>
		 &nbsp; &nbsp; &nbsp;Для скачивания файлов необходимо авторизоваться на сайте<br>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => 'n.kropotova@studiofact.ru',
  'REPLY_TO' => '',
  'CC' => 'test@b2b.gauss.ru',
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
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_12 ] Заполнена web-форма "SIMPLE_FORM_12"',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_11', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SIMPLE_FORM_11"',
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
#TEXT# - Текст отзыва
#TEXT_RAW# - Текст отзыва (оригинальное значение)
#SIMPLE_QUESTION_988# - Прикрепить файл
#SIMPLE_QUESTION_988_RAW# - Прикрепить файл (оригинальное значение)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_11', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SIMPLE_FORM_11"',
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
#TEXT# - Текст отзыва
#TEXT_RAW# - Текст отзыва (original value)
#SIMPLE_QUESTION_988# - Прикрепить файл
#SIMPLE_QUESTION_988_RAW# - Прикрепить файл (original value)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_11', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => ' #EMAIL_MANAGER_ASSISTANT_BY_USER#',
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
		 Сессия
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_STAT_SESSION_ID#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Текст отзыва
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #TEXT#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp; &nbsp;Файлы: #SIMPLE_QUESTION_988# <br>
 <br>
		 &nbsp; &nbsp; &nbsp;Для скачивания файлов необходимо авторизоваться на сайте<br>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => 'n.kropotova@studiofact.ru',
  'REPLY_TO' => '',
  'CC' => 'test@b2b.gauss.ru',
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
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_11 ] Заполнена web-форма "SIMPLE_FORM_11"',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_13', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SIMPLE_FORM_13"',
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
#TYPE# - Вид претензии
#TYPE_RAW# - Вид претензии (оригинальное значение)
#TEXT# - Текст сообщения
#TEXT_RAW# - Текст сообщения (оригинальное значение)
#FILE_1# - Прикрепить файл
#FILE_1_RAW# - Прикрепить файл (оригинальное значение)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_13', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SIMPLE_FORM_13"',
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
#TYPE# - Вид претензии
#TYPE_RAW# - Вид претензии (original value)
#TEXT# - Текст сообщения
#TEXT_RAW# - Текст сообщения (original value)
#FILE_1# - Прикрепить файл
#FILE_1_RAW# - Прикрепить файл (original value)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_13', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => ' #EMAIL_MANAGER_ASSISTANT_BY_USER#',
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
		 Вид претензии
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #TYPE#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Текст сообщения
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #TEXT#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp; &nbsp;Файлы: #FILE_1#<br>
 <br>
		 &nbsp; &nbsp; &nbsp;Для скачивания файлов необходимо авторизоваться на сайте<br>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => 'test@b2b.gauss.ru',
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
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_13 ] Заполнена web-форма "SIMPLE_FORM_13"',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_13', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => '#RS_USER_EMAIL#',
  'SUBJECT' => '#SERVER_NAME#: заявка успешно создана',
  'MESSAGE' => '<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
		 Заявка с претензией успешно создана
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
 <br>
		 Дата - #RS_DATE_CREATE#<br>
 <br>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => 'mzhuravlev@studiofact.ru',
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
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_13 ] Заполнена web-форма "SIMPLE_FORM_13"',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_10', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SIMPLE_FORM_10"',
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
#NUMBER# - Основание возврата товара (номер счета/накладной)
#NUMBER_RAW# - Основание возврата товара (номер счета/накладной) (оригинальное значение)
#COMMENT# - Комментарий
#COMMENT_RAW# - Комментарий (оригинальное значение)
#FILE_1# - Выбрать файл
#FILE_1_RAW# - Выбрать файл (оригинальное значение)
#FILE_2# - Выбрать файл
#FILE_2_RAW# - Выбрать файл (оригинальное значение)
#FILE_3# - Выбрать файл
#FILE_3_RAW# - Выбрать файл (оригинальное значение)
#FILE_4# - Выбрать файл
#FILE_4_RAW# - Выбрать файл (оригинальное значение)
#FILE_5# - Выбрать файл
#FILE_5_RAW# - Выбрать файл (оригинальное значение)
#FILE_6# - Выбрать файл
#FILE_6_RAW# - Выбрать файл (оригинальное значение)
#FILE_7# - Выбрать файл
#FILE_7_RAW# - Выбрать файл (оригинальное значение)
#FILE_8# - Выбрать файл
#FILE_8_RAW# - Выбрать файл (оригинальное значение)
#FILE_9# - Выбрать файл
#FILE_9_RAW# - Выбрать файл (оригинальное значение)
#FILE_10# - Выбрать файл
#FILE_10_RAW# - Выбрать файл (оригинальное значение)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_10', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SIMPLE_FORM_10"',
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
#NUMBER# - Основание возврата товара (номер счета/накладной)
#NUMBER_RAW# - Основание возврата товара (номер счета/накладной) (original value)
#COMMENT# - Комментарий
#COMMENT_RAW# - Комментарий (original value)
#FILE_1# - Выбрать файл
#FILE_1_RAW# - Выбрать файл (original value)
#FILE_2# - Выбрать файл
#FILE_2_RAW# - Выбрать файл (original value)
#FILE_3# - Выбрать файл
#FILE_3_RAW# - Выбрать файл (original value)
#FILE_4# - Выбрать файл
#FILE_4_RAW# - Выбрать файл (original value)
#FILE_5# - Выбрать файл
#FILE_5_RAW# - Выбрать файл (original value)
#FILE_6# - Выбрать файл
#FILE_6_RAW# - Выбрать файл (original value)
#FILE_7# - Выбрать файл
#FILE_7_RAW# - Выбрать файл (original value)
#FILE_8# - Выбрать файл
#FILE_8_RAW# - Выбрать файл (original value)
#FILE_9# - Выбрать файл
#FILE_9_RAW# - Выбрать файл (original value)
#FILE_10# - Выбрать файл
#FILE_10_RAW# - Выбрать файл (original value)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_10', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => 'vozvratgauss@varton.ru',
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
		 Основание возврата товара (номер счета/накладной)
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #NUMBER#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Причина возврата/обмена
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #REASON#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Комментарий
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #COMMENT#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp; &nbsp;Файлы: #FILE_1#<br>
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
  'CC' => '#EMAIL_ASSISTANT_BY_USER#',
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
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_10 ] Заполнена web-форма "SIMPLE_FORM_10"',
));
    }

    public function down()
    {
        //your code ...
    }
}
