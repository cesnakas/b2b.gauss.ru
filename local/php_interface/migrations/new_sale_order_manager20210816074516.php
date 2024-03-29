<?php

namespace Sprint\Migration;


class new_sale_order_manager20210816074516 extends Version
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
        $helper->Event()->saveEventType('SALE_NEW_ORDER_MANAGER', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Новый заказ менеджеру',
  'DESCRIPTION' => '#ORDER_ID# - код заказа
#ORDER_ACCOUNT_NUMBER_ENCODE# - код заказа(для ссылок)
#ORDER_REAL_ID# - реальный ID заказа
#ORDER_DATE# - дата заказа
#ORDER_USER# - заказчик
#PRICE# - сумма заказа
#EMAIL# - E-Mail заказчика
#BCC# - E-Mail скрытой копии
#ORDER_LIST# - состав заказа
#ORDER_PUBLIC_URL# - ссылка для просмотра заказа без авторизации (требуется настройка в модуле интернет-магазина)
#SALE_EMAIL# - E-Mail отдела продаж
#EMAIL_MANAGER# - E-mail менеджера
#CONTRAGENT_NAME# - Юр. лицо
#ID_1C# - ID из 1С
#1C_NUMBER# - Номер заказа в 1с',
  'SORT' => '150',
));
        $helper->Event()->saveEventType('SALE_NEW_ORDER_MANAGER', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'New order for manager',
  'DESCRIPTION' => '#ORDER_ID# - order ID
#ORDER_ACCOUNT_NUMBER_ENCODE# - order ID (for URL\'s)
#ORDER_REAL_ID# - real order ID
#ORDER_DATE# - order date
#ORDER_USER# - customer
#PRICE# - order amount
#EMAIL# - customer e-mail
#BCC# - BCC e-mail
#ORDER_LIST# - order contents
#ORDER_PUBLIC_URL# - order view link for unauthorized users (requires configuration in the e-Store module settings)
#SALE_EMAIL# - sales dept. e-mail
#EMAIL_MANAGER# - E-mail manager
#CONTRAGENT_NAME# - Юр. лицо
#ID_1C# - ID из 1С
#1C_NUMBER# - order\'s number in 1C',
  'SORT' => '150',
));
        $helper->Event()->saveEventMessage('SALE_NEW_ORDER_MANAGER', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@b2b.gauss.ru',
  'EMAIL_TO' => '#EMAIL_MANAGER#',
  'SUBJECT' => '#SITE_NAME#: Новый заказ N#1C_NUMBER#',
  'MESSAGE' => '<!--[if !mso]><!--> <!--<![endif]--> <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        table {border-collapse: collapse;}
    </style>
    <![endif]--> <style>
		@font-face {
			font-family: "Arial";
			font-display: fallback;
			font-weight: 300;
		}
		@font-face {
			font-family: "Arial";
			font-display: fallback;
			font-weight: 500;
		}
		a:hover{color: #0DB9F9 !important;}
td[style="width:200px"], td[style="width:20px"], td[style="width:100px"] {
border-bottom: 2px solid black;
}

	</style>
<table width="100%" cellspacing="0" cellpadding="0" style="width:100%;max-width:650px;background-color:#ffffff;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: auto; line-height: normal; font-family: Arial, sans-serif;font-weight: 300;">
<tbody>
<tr>
	<td>
	</td>
	<td width="650" style="padding:0px;margin:0px;">
		 <!--[if (gte mso 9)|(IE)]>
            <table width="100%" align="center">
                <tr>
                    <td>
            <![endif]-->
		<table width="100%" align="center" style="border-spacing: 0;Margin: 0 auto;width: 100%;max-width: 700px;font-size:16px;">
		<tbody>
		<tr>
			<td style="padding: 0;">
				<table width="100%" style="padding-top: 9px;padding-bottom:12px;padding-left:40px;padding-right:40px;background-color:#F0F2F3;" cellspacing="0" cellpadding="0">
				<tbody>
				<tr>
					<td>
						<table width="100%" style="width: 100%;">
						<tbody>
						<tr>
							<td style="padding: 0px;">
 <a style="display:inline-block" target="_blank" href="https://b2b.gauss.ru/"> <img alt="Гаусс B2B" src="https://b2b.gauss.ru/local/templates/email_theme/logo.png" style="border:0 none; display:block;padding-top:15px;padding-top:15px; position: relative; bottom: -5px; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges; image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;"> </a>
							</td>
						</tr>
						</tbody>
						</table>
					</td>
					<td style="text-align:right;">
 <a href="tel:84956498133" style="text-decoration:none; display:block; font-weight: 500;color: #2F3744;padding: 0px;padding-bottom: 7px;">8 (495) 649-81-33</a>
						<table width="100%" cellspacing="0" cellpadding="0">
						<tbody>
						<tr>
						</tr>
						<tr>
						</tr>
						</tbody>
						</table>
					</td>
				</tr>
				</tbody>
				</table>
				<table width="100%" style="padding: 0px;padding-left:40px;">
				<tbody>
				<tr>
					<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
 <b>Уважаемый пользователь портала Гаусс,&nbsp;#MANAGER_NAME##EMAIL_ASSISTANT_BY_USER#</b>
					</td>
				</tr>
				</tbody>
				</table>
				<table width="100%" style="padding: 0px;padding-left:40px;">
				<tbody>
				<tr>
					<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
						 Оформлен заказ номер <b>#1C_NUMBER#</b> от <b>#ORDER_DATE#<br>
 </b><br>
						<ul>
							<li>ФИО: #ORDER_USER#</li>
							<li>Юр. лицо:&nbsp;#CONTRAGENT_NAME#</li>
							<li>Номер заказа на сайте:&nbsp;#1C_NUMBER#</li>
						</ul>
						 Стоимость заказа: <b>#PRICE#<b>.<br>
 <br>
 </b></b>
					</td>
				</tr>
				</tbody>
				</table>
				<table width="100%" style="padding: 0px;padding-left:40px;">
				<tbody>
				<tr>
					<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
 <b>Состав заказа</b>
					</td>
				</tr>
				</tbody>
				</table>
				 #BASKET#
				<table width="100%" style="padding: 0px;padding-left:40px;">
				<tbody>
				<tr>
					<td>
 <b>#PROPERTIES#</b>
					</td>
				</tr>
				</tbody>
				</table>
 <br>
				<table width="100%" style="padding: 0px;padding-left:40px;">
				<tbody>
				<tr>
					<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
						 Вы можете следить за выполнением своего заказа (на какой стадии выполнения он находится), войдя в Ваш персональный раздел сайта #SITE_NAME#.<br>
 <br>
						 Обратите внимание, что для входа в этот раздел Вам необходимо будет ввести логин и пароль пользователя сайта #SITE_NAME#.<br>
 <br>
						 Для того, чтобы аннулировать заказ, воспользуйтесь функцией отмены заказа, которая доступна в Вашем персональном разделе сайта #SITE_NAME#.<br>
 <br>
						 Пожалуйста, при обращении к администрации сайта #SITE_NAME# ОБЯЗАТЕЛЬНО указывайте номер Вашего заказа - #ORDER_ID#.<br>
 <br>
					</td>
				</tr>
				</tbody>
				</table>
				<table style="width: 100%;background-color: #F0F2F3;margin:auto;padding: 20px 40px;color: #000000;font-size: 12px;line-height:16px;">
				<tbody>
				<tr>
					<td style="padding:0;">
						 С уважением, администрация <a href="#SERVER_NAME#" style="text-decoration:none;color: #2e6eb6;">Gauss</a><br>
						 E-mail: <a href="mailto:#DEFAULT_EMAIL_FROM#" style="text-decoration:none;color: #2e6eb6;">#DEFAULT_EMAIL_FROM#</a> <br>
					</td>
				</tr>
				</tbody>
				</table>
			</td>
		</tr>
		</tbody>
		</table>
		 <!--[if (gte mso 9)|(IE)]>
</td>
</tr>
</table>
<![endif]-->
	</td>
	<td>
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
  'SITE_TEMPLATE_ID' => '',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => '',
  'EVENT_TYPE' => '[ SALE_NEW_ORDER_MANAGER ] Новый заказ менеджеру',
));
    }

    public function down()
    {
        //your code ...
    }
}
