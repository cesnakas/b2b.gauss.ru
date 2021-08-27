<?php

namespace Sprint\Migration;


class sendingMailAboutArrivalGoods20210121174336 extends Version
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
        $helper->Event()->saveEventType('GOODS_ARRIVAL_NOTIFICATION', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Уведомление о поступлении товаров из листа ожидания',
  'DESCRIPTION' => '#GOOD#  - название товара
#USERNAME# - имя пользователя
#EMAIL# - email пользователя
#CATALOG_QUANTITY# - количество товара
#IMG# - картинка
#IMG_NAME# - название картинки
#LINK# - ссылка на детальную страницу товара
#PRICE# - цена
#HOST# - хост сервера',
  'SORT' => '150',
));
        $helper->Event()->saveEventType('GOODS_ARRIVAL_NOTIFICATION', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Notification about arrival of goods from waiting list',
  'DESCRIPTION' => '',
  'SORT' => '150',
));
        $helper->Event()->saveEventMessage('GOODS_ARRIVAL_NOTIFICATION', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
  'EMAIL_TO' => '#EMAIL#',
  'SUBJECT' => 'Уведомление о поступлении товара из списка ожидания',
  'MESSAGE' => '<table width="800" cellspacing="0" cellpadding="0">
<tbody>
<tr>
	<td style="width: 800px; min-width: 800px; padding: 25px 0 35px; background: #FFFFFF; border: 1px solid #DDDDE2;">
		<table style="width: 100%;">
		<tbody>
		<tr>
			<td style="padding: 10px 30px 5px; font-size: 18px; line-height: 22px;">
				 #USERNAME#, товар "#GOOD#" из листа ожидания появился на складе:
			</td>
		</tr>
		<tr>
			<td>

<table align="center" style="width: 400px; border-collapse: collapse;">
<tbody>
<tr>
	<td colspan="2"  style="padding-top: 10px; padding-bottom: 10px; text-align: center; vertical-align: middle;">
		<a href="https://#HOST##LINK#">
			<img alt="#IMG_NAME#" src="https://#HOST#/#IMG#" style="max-width: 300px; max-height: 300px; width: auto; height: auto; border:0 none; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges; image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;">
		</a>
	</td>
</tr>
<tr>
	<td style="padding-top: 10px; padding-bottom: 20px; font-size: 17px; line-height: 22px; font-weight: 500; text-align: center;">
 <b><a href="https://#HOST##LINK#" style="color: #232323; text-decoration: none;" target="_blank">#GOOD#</a></b>
	</td>
</tr>
<tr>
	<td style="padding-top: 5px; padding-bottom: 5px; color: #7F8A9E; font-size: 15px; text-align: center;">
		 Наличие:     #CATALOG_QUANTITY#
	</td>
</tr>
<tr>
	<td style="padding-top: 20px; padding-bottom: 30px; color: #232323; font-size: 18px; font-weight: 500; text-align: center;">
 <b>#PRICE#</b>
	</td>
</tr>
<tr>
	<td style="padding: 5px 10px 10px; text-align: center;">
 <a href="https://#HOST##LINK#" target="_blank" style="padding-top: 10px; padding-right: 30px; padding-bottom: 10px; padding-left: 30px; border: 1px solid #232323; color: #232323; font-size: 14px; font-weight: 500; text-align: center; text-decoration: none;"> <b>Купить</b> </a>
	</td>
</tr>
</tbody>
</table>

			</td>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
</tbody>
</table>',
  'BODY_TYPE' => 'html',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => 'email-actions',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => 'ru',
  'EVENT_TYPE' => '[ GOODS_ARRIVAL_NOTIFICATION ] Уведомление о поступлении товаров из листа ожидания',
));
    }

    public function down()
    {
        //your code ...
    }
}
