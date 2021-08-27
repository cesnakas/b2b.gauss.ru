<?php

namespace Sprint\Migration;


class webform620210416170018 extends Version
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
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_6', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SIMPLE_FORM_6"',
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
#PHONE# - Ваш телефон
#PHONE_RAW# - Ваш телефон (оригинальное значение)
#COMMENT# - Текст сообщения
#COMMENT_RAW# - Текст сообщения (оригинальное значение)
#NAME# - Ваше имя
#NAME_RAW# - Ваше имя (оригинальное значение)
#EMAIL# - E-mail
#EMAIL_RAW# - E-mail (оригинальное значение)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('FORM_FILLING_SIMPLE_FORM_6', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SIMPLE_FORM_6"',
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
#PHONE# - Ваш телефон
#PHONE_RAW# - Ваш телефон (original value)
#COMMENT# - Текст сообщения
#COMMENT_RAW# - Текст сообщения (original value)
#NAME# - Ваше имя
#NAME_RAW# - Ваше имя (original value)
#EMAIL# - E-mail
#EMAIL_RAW# - E-mail (original value)
',
  'SORT' => '100',
));
        $helper->Event()->saveEventMessage('FORM_FILLING_SIMPLE_FORM_6', array (
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
		 Вашe имя
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Ваш телефон
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #PHONE#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 EMAIL
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #EMAIL#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Текст сообщения
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
		 Для просмотра воспользуйтесь <a href=" http://#SERVER_NAME#/bitrix/admin/form_result_view.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#">ссылкой</a>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '#DEFAULT_EMAIL_FROM#',
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
  'EVENT_TYPE' => '[ FORM_FILLING_SIMPLE_FORM_6 ] Заполнена web-форма "SIMPLE_FORM_6"',
));
    }

    public function down()
    {
        //your code ...
    }
}
