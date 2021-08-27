<?php

namespace Sprint\Migration;


class Version20201112222308 extends Version
{
    protected $description = "обновление почт. шаблона REQUEST_TO_DELETE_USER";

    protected $moduleVersion = "3.17.2";

    public function up()
    {
        $arFilter = Array(
            "TYPE_ID" => array("REQUEST_TO_DELETE_USER"),
        );

        $rsMess = \CEventMessage::GetList($by="site_id", $order="desc", $arFilter);

        while($arMess = $rsMess->GetNext())
        {
            $id = $arMess['ID'];
        }

        $message = '
            <table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-top: 30px;padding-bottom: 30px;font-size: 22px;color: #2F3744;font-weight: 500;">
		 Поступил запрос на удаление пользователя с портала: <a href="#SERVER_NAME#/bitrix/admin/user_edit.php?lang=ru&ID=#RS_USER_ID#">подробнее</a>
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
		 Менеджер
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_MANAGER_NAME#<br>
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Ассистент
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #RS_ASSISTENT_NAME#<br>
	</td>
</tr>
<tr>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
		 Пользователь
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 [#RS_USER_ID#] #RS_USER_NAME#&nbsp;
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
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
</table>
        ';

        $em = new \CEventMessage;
        $arFields = Array(
            "MESSAGE" => $message,
        );
        if($id>0)
        {
            $res = $em->Update($id, $arFields);
        }

    }

    public function down()
    {
        //your code ...
    }
}
