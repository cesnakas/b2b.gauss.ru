<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$protocol = Option::get("main", "mail_link_protocol", 'https', $arParams["SITE_ID"]);
$email = Option::get("main", "email_from");
$serverName = $protocol.$arParams["SERVER_NAME"];
?>
<table style="width: 100%;background-color: #F0F2F3;margin:auto;padding: 20px 40px;color: #000000;font-size: 12px;line-height:16px;">
    <tbody>
    <tr>
        <td style="padding:0;">
			С уважением, администрация <a href="<?= $serverName; ?>" style="text-decoration:none;color: #2e6eb6;">Gauss</a>. Для авторизации пройдите по <a href="https://b2b.gauss.ru/auth/" >Ссылке</a><br>
            E-mail: <a href="mailto:<?= $email; ?>" style="text-decoration:none;color: #2e6eb6;"><?= $email; ?></a>
            <br>
            <br>
            Пожалуйста, не отвечайте на это письмо, оно составлено автоматически<br>
            Если письмо отправлено вам по ошибке, пожалуйста сообщите об этом нам по телефону <a href="tel:84956498133" style="text-decoration:none;color: #2e6eb6;">8 (495) 649-81-33</a>
            или по электронной почте <a target="_blank" style="text-decoration:none;color: #2e6eb6;" href="mailto:<?= $email; ?>"><?= $email; ?></a>.
        </td>
    </tr>
    </tbody>
</table>
</td>
</tr>
</table>
<!--[if (gte mso 9)|(IE)]>
</td>
</tr>
</table>
<![endif]-->
</td>
<td></td>
</tr>
</tbody>
</table>
</body>
</html>