<?php

namespace Sprint\Migration;


class SALE_STATUS_CHANGED_N20210816073348 extends Version
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
        $helper->Event()->saveEventType('SALE_STATUS_CHANGED_N', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Изменение статуса заказа на  "Заказ в обработке"',
  'DESCRIPTION' => '#ORDER_ID# - код заказа
#ORDER_DATE# - дата заказа
#ORDER_STATUS# - статус заказа
#EMAIL# - E-Mail пользователя
#ORDER_DESCRIPTION# - описание статуса заказа
#TEXT# - текст
#SALE_EMAIL# - E-Mail отдела продаж
#ORDER_PUBLIC_URL# - ссылка для просмотра заказа без авторизации (требуется настройка в модуле интернет-магазина)
#1C_NUMBER# - номер 1с',
  'SORT' => '100',
));
        $helper->Event()->saveEventType('SALE_STATUS_CHANGED_N', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Changing order status to ""',
  'DESCRIPTION' => '#ORDER_ID# - order ID
#ORDER_DATE# - order date
#ORDER_STATUS# - order status
#EMAIL# - customer e-mail
#ORDER_DESCRIPTION# - order status description
#TEXT# - text
#SALE_EMAIL# - Sales department e-mail
#ORDER_PUBLIC_URL# - order view link for unauthorized users (requires configuration in the e-Store module settings)
#1C_NUMBER# - number 1с',
  'SORT' => '10000',
));
        $helper->Event()->saveEventMessage('SALE_STATUS_CHANGED_N', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => '#EMAIL#',
  'SUBJECT' => '#SITE_NAME#: Изменение статуса заказа ',
  'MESSAGE' => '<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
		 Изменение статуса заказа в магазине #SITE_NAME#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 17px;color: #2F3744;font-weight: 500;">
		 Информационное сообщение сайта #SITE_NAME#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="font-size: 14px;border-spacing: 0;padding-left:40px;padding-right:40px;padding-bottom:10px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 Статус заказа&nbsp; от #ORDER_DATE# изменен.
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="font-size: 14px;border-spacing: 0;padding-left:40px;padding-right:40px;padding-bottom:50px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Новый статус заказа:
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #ORDER_STATUS#<br>
		 #ORDER_DESCRIPTION#<br>
		 #TEXT#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 Для получения подробной информации по заказу пройдите на сайт по <a href="#SERVER_NAME#/personal/orders/#ORDER_ID#/" target="_blank" style="text-decoration:none;color: #2e6eb6;">ссылке</a>
	</td>
</tr>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 Спасибо за ваш выбор!<br>
	</td>
</tr>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 С уважением,<br>
		 администрация <a href="http://#SERVER_NAME#" style="text-decoration:none;color: #2e6eb6;">#SITE_NAME#</a><br>
		 E-mail: <a href="mailto:#SALE_EMAIL#" style="text-decoration:none;color: #2e6eb6;">#SALE_EMAIL#</a>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => 'n.kropotova@studiofact.ru',
  'REPLY_TO' => '',
  'CC' => '',
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
  'EVENT_TYPE' => '[ SALE_STATUS_CHANGED_N ] Изменение статуса заказа на  "Заказ в обработке"',
));
    }

    public function down()
    {
        //your code ...
    }
}
