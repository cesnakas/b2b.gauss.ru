<?php

namespace Sprint\Migration;


class Field_NOT_EXIST_INN20201106165634 extends Version
{
    protected $description = "Добавление поля для выведения информации о том что пользователь с данным инн не существует";

    protected $moduleVersion = "3.17.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('NEW_USER', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Зарегистрировался новый пользователь',
  'DESCRIPTION' => '#USER_ID# - ID пользователя
#LOGIN# - Логин
#EMAIL# - EMail
#NAME# - Имя
#LAST_NAME# - Фамилия
#USER_IP# - IP пользователя
#USER_HOST# - Хост пользователя
#UF_ACTIVATE_PROFILE# - Ссылка на активацию личного кабинета пользователя
#UF_TIN# - ИНН
#PERSONAL_PHONE# - Телефон пользователя
#UF_REGION_NAME# - Название региона
#UF_COMPANY_NAME# - Название компании
#UF_COMPANY_PHONE# - Телефон компании
#EMAIL_MANAGER_ASSISTANT_BY_USER# - Адреса эл. почты менеджера и ассистентов
#NOT_EXIST_INN# - Существование контрагента в базе данных',
  'SORT' => '1',
));
        $helper->Event()->saveEventType('NEW_USER', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'New user was registered',
  'DESCRIPTION' => '#USER_ID# - User ID
#LOGIN# - Login
#EMAIL# - EMail
#NAME# - Name
#LAST_NAME# - Last Name
#USER_IP# - User IP
#USER_HOST# - User Host
#UF_ACTIVATE_PROFILE# - Link to the activation of the user\'s personal account
#UF_TIN# - TIN
#PERSONAL_PHONE# - User phone
#UF_REGION_NAME# - Region name
#UF_COMPANY_NAME# - Company name
#UF_COMPANY_PHONE# - Company phone
#EMAIL_MANAGER_ASSISTANT_BY_USER# - Адреса эл. почты менеджера и ассистентов
#NOT_EXIST_INN# - The existence of a counterparty in the database',
  'SORT' => '1',
));
        $helper->Event()->saveEventMessage('NEW_USER', array (
  'LID' =>
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => '#EMAIL_MANAGER_ASSISTANT_BY_USER#',
  'SUBJECT' => '#SITE_NAME#: Зарегистрировался новый пользователь',
  'MESSAGE' => '<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
		 Информационное сообщение сайта #SITE_NAME#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 На сайте #SERVER_NAME# успешно зарегистрирован новый пользователь.
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="font-size: 14px;border-spacing: 0;padding-left:40px;padding-right:40px;padding-bottom:50px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Данные пользователя:
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Имя
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Фамилия
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #LAST_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 E-Mail
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #EMAIL#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Телефон
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #PERSONAL_PHONE#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Login
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #LOGIN#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Регион
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_REGION_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Название компании
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_COMPANY_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 ИНН
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_TIN#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Телефон компании
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_COMPANY_PHONE#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp;&nbsp;#NOT_EXIST_INN#<br>
		 &nbsp; &nbsp; Ссылка на активацию личного кабинета пользователя - #UF_ACTIVATE_PROFILE# <br>
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
  'LANGUAGE_ID' => 'ru',
  'EVENT_TYPE' => '[ NEW_USER ] Зарегистрировался новый пользователь',
));
    }

    public function down()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('NEW_USER', array (
            'LID' => 'ru',
            'EVENT_TYPE' => 'email',
            'NAME' => 'Зарегистрировался новый пользователь',
            'DESCRIPTION' => '#USER_ID# - ID пользователя
#LOGIN# - Логин
#EMAIL# - EMail
#NAME# - Имя
#LAST_NAME# - Фамилия
#USER_IP# - IP пользователя
#USER_HOST# - Хост пользователя
#UF_ACTIVATE_PROFILE# - Ссылка на активацию личного кабинета пользователя
#UF_TIN# - ИНН
#PERSONAL_PHONE# - Телефон пользователя
#UF_REGION_NAME# - Название региона
#UF_COMPANY_NAME# - Название компании
#UF_COMPANY_PHONE# - Телефон компании
#EMAIL_MANAGER_ASSISTANT_BY_USER# - Адреса эл. почты менеджера и ассистентов',
            'SORT' => '1',
        ));
        $helper->Event()->saveEventType('NEW_USER', array (
            'LID' => 'en',
            'EVENT_TYPE' => 'email',
            'NAME' => 'New user was registered',
            'DESCRIPTION' => '#USER_ID# - User ID
#LOGIN# - Login
#EMAIL# - EMail
#NAME# - Name
#LAST_NAME# - Last Name
#USER_IP# - User IP
#USER_HOST# - User Host
#UF_ACTIVATE_PROFILE# - Link to the activation of the user\'s personal account
#UF_TIN# - TIN
#PERSONAL_PHONE# - User phone
#UF_REGION_NAME# - Region name
#UF_COMPANY_NAME# - Company name
#UF_COMPANY_PHONE# - Company phone
#EMAIL_MANAGER_ASSISTANT_BY_USER# - Адреса эл. почты менеджера и ассистентов',
            'SORT' => '1',
        ));
        $helper->Event()->saveEventMessage('NEW_USER', array (
            'LID' =>
                array (
                    0 => 's1',
                ),
            'ACTIVE' => 'Y',
            'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
            'EMAIL_TO' => '#EMAIL_MANAGER_ASSISTANT_BY_USER#',
            'SUBJECT' => '#SITE_NAME#: Зарегистрировался новый пользователь',
            'MESSAGE' => '<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
		 Информационное сообщение сайта #SITE_NAME#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 На сайте #SERVER_NAME# успешно зарегистрирован новый пользователь.
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="font-size: 14px;border-spacing: 0;padding-left:40px;padding-right:40px;padding-bottom:50px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Данные пользователя:
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Имя
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Фамилия
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #LAST_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 E-Mail
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #EMAIL#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Телефон
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #PERSONAL_PHONE#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Login
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #LOGIN#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Регион
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_REGION_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Название компании
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_COMPANY_NAME#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 ИНН
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_TIN#
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Телефон компании
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #UF_COMPANY_PHONE#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 &nbsp; &nbsp; Ссылка на активацию личного кабинета пользователя - #UF_ACTIVATE_PROFILE# <br>
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
            'LANGUAGE_ID' => 'ru',
            'EVENT_TYPE' => '[ NEW_USER ] Зарегистрировался новый пользователь',
        ));
    }
}
