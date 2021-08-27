<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$protocol = Option::get("main", "mail_link_protocol", 'https', $arParams["SITE_ID"]);
$serverName = $protocol.$arParams["SERVER_NAME"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        table {border-collapse: collapse;}
    </style>
    <![endif]-->
    <style>
        <?=$this->showStyles();?>
    </style>
    <!--[if mso]>
    <style type=”text/css”>
        body, table, td {font-family: Arial, Helvetica, sans-serif !important;}
    </style>
    <![endif]-->
</head>
<body  style="Margin: 0;padding: 0;">
<table width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:650px;background-color:#ffffff;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: auto; line-height: normal; font-family: Arial, sans-serif;font-weight: 300;">
    <tbody>
    <tr>
        <td></td>
        <td width="650" style="padding:0px;margin:0px;">
            <!--[if (gte mso 9)|(IE)]>
            <table width="100%" align="center">
                <tr>
                    <td>
            <![endif]-->
            <table width="100%" align="center" style="border-spacing: 0;Margin: 0 auto;width: 100%;max-width: 700px;font-size:16px;">
                <tr>
                    <td style="padding: 0;">
                        <table width="100%" style="padding-top: 9px;padding-bottom:12px;padding-left:40px;padding-right:40px;background-color:#F0F2F3;" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                            <tr>
                                <td>
                                    <table width="100%" style="width: 100%;">
                                        <tbody>
                                        <tr>
                                            <td style="padding: 0px;">
                                                <a  style="display:inline-block" target="_blank" href="https://b2b.gauss.ru/">
													<img style="border:0 none; display:block;padding-top:15px; position: relative; top: 5px; image-rendering: optimizeSpeed; image-rendering: -moz-crisp-edges; image-rendering: -o-crisp-edges;  image-rendering: -webkit-optimize-contrast; image-rendering: optimize-contrast;" src="https://b2b.gauss.ru/local/templates/email_theme/logo.png" alt="Гаусс B2B"/>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="text-align:right;">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                        <tr>
                                            <a href="tel:84956498133" style="text-decoration:none; display:block; font-weight: 500;color: #2F3744;padding: 0px;padding-bottom: 7px;">8 (495) 649-81-33</a>
                                        </tr>
                                        <tr>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
