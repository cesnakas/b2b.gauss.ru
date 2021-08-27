<?php

namespace Citfact\SiteCore;

use Citfact\Tools\HLBlock;
use Curl\Curl;

class Sms
{
    const URL = 'https://gate.imobis.ru/send';
    const LOGIN = '';
    const PASSWORD = '*';
    const SENDER = '';

    public static function send($phone, $text, $messageId = '')
    {
        if (!$phone || !$text) {
            return;
        }
        $curl = new Curl();
        $hlBlock = new HLBlock();
        $params = [
            'user' => self::LOGIN,
            'password' => self::PASSWORD,
            'sender' => self::SENDER,
            'phone' => $phone,
            'text' => $text,
        ];
        if ($messageId) {
            $params['messageId'] = $messageId;
        }
        $response = $curl->get(self::URL, $params);

        if (strpos($text, 'Ваш пароль:') !== false) {
            $text = 'Ваш пароль:';
        }
        $hlEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_SMS);
        $hlEntity::add([
            'UF_DATE_CREATE' => date('d.m.Y H:i:s'),
            'UF_PHONE' => $phone,
            'UF_TEXT' => $text,
            'UF_RESPONSE' => $response->response,
        ]);
    }
}