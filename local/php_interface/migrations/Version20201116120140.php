<?php

namespace Sprint\Migration;


class Version20201116120140 extends Version
{
    protected $description = "Изменение тела почтовых шаблонов FORM_FILLING_SIMPLE_FORM_16 и FORM_FILLING_SIMPLE_FORM_4";

    protected $moduleVersion = "3.17.2";

    public function up()
    {
        $arFilter = Array(
            "TYPE_ID" => array("FORM_FILLING_SIMPLE_FORM_4", "FORM_FILLING_SIMPLE_FORM_16"),
        );

        $rsMess = \CEventMessage::GetList($by="site_id", $order="desc", $arFilter);

        while($arMess = $rsMess->GetNext()) {
            if ($arMess['EVENT_NAME'] == 'FORM_FILLING_SIMPLE_FORM_4') {
                $id['FORM_FILLING_SIMPLE_FORM_4'] = $arMess['ID'];
            } else {
                $id['FORM_FILLING_SIMPLE_FORM_16'] = $arMess['ID'];
            }

        }

        $message['FORM_FILLING_SIMPLE_FORM_4'] = '
            <table width="100%" style="padding: 0px;padding-left:40px;">
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
		 Выбрать файл
	</td>
	<td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
		 #FILE#
	</td>
</tr>
</tbody>
</table>
<table width="100%" style="padding: 0px;padding-left:40px;">
<tbody>
<tr>
	<td style="padding:0;padding-bottom: 30px;color: #2F3744;font-size: 16px;">
		 Для просмотра воспользуйтесь <a href=" http://#SERVER_NAME#/bitrix/admin/form_result_view.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID">ссылкой</a>
	</td>
</tr>
</tbody>
</table>
        ';

        $message['FORM_FILLING_SIMPLE_FORM_16'] = '
                        <table width="100%" style="padding: 0px;padding-left:40px;">
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
                     С
                </td>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
                     #DATE_FROM#
                </td>
            </tr>
            <tr>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-bottom: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">
                     По
                </td>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left: 16px;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
                     #DATE_TO#
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
            </table>
        ';

        $em = new \CEventMessage;

        if(!empty($id)) {
            $em->Update($id['FORM_FILLING_SIMPLE_FORM_4'], ['MESSAGE' => $message['FORM_FILLING_SIMPLE_FORM_4']]);
            $em->Update($id['FORM_FILLING_SIMPLE_FORM_16'], ['MESSAGE' => $message['FORM_FILLING_SIMPLE_FORM_16']]);
        }

    }

    public function down()
    {
        //your code ...
    }
}
