<?php

namespace Citfact\Sitecore\Subscription;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sender\ContactTable;

class SubscriptionMarketing
{
    const ERROR_INCORRECT_EMAIL = 1;
    const ERROR_HAS_EMAIL = 2;
    const ERROR_OTHER_EMAIL = 3;

    /**
     * @param $email
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function isExistEmail($email)
    {
        $dbEmails = ContactTable::getList(['select' => ['CODE', 'ID'], 'filter' => ['CODE'=> $email]]);

        $emailList = [];
        while ($email = $dbEmails->fetch()) {
            $emailList[] = $email['CODE'];
        }

        return !empty($emailList);
    }

    /**
     * use self::subscribeEvent and SubscriptionMarketingComponent
     *
     * @param $email
     * @param string $name
     * @param integer $uid
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public static function addEmail($email, $name='', $uid=0)
    {
        $result = [
            'error' => 0,
            'success' => 0,
        ];
        $checkFormat = ContactTable::checkEmail($email);
        if ($checkFormat !== true) {
            $result['error'] = self::ERROR_INCORRECT_EMAIL;
            return $result;
        }

        if (self::isExistEmail($email)) {
            $result['error'] = self::ERROR_HAS_EMAIL;
            return $result;
        }

        $contactAddDb = ContactTable::add([
            'TYPE_ID' => 1, // 1 - email, 2 - phone
            'CODE' => $email,
            'NAME' => $name,
            'USER_ID' => $uid,
        ]);
        if ($contactAddDb->isSuccess()) {
            $result['success'] = true;

        } else {
            $result['error'] = self::ERROR_OTHER_EMAIL;
        }

        return $result;
    }


    /**
     * @param $email
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function subscribeEvent($email)
    {
        $result = [
            'error' => '',
            'success' => false,
        ];
        $result['success'] = true;
        $res = self::addEmail($email);
        if (
            $res['success']
            || $res['error'] === self::ERROR_HAS_EMAIL // если уже подписан. Не будем выводить ошибку
        ) {
            $result['success'] = true;

        } else {
            switch ($res['error']) {
                case self::ERROR_INCORRECT_EMAIL:
                    $result['error'] = Loc::getMessage('ERROR_INCORRECT_EMAIL');
                    break;
                case self::ERROR_HAS_EMAIL:
                    $result['error'] = Loc::getMessage('ERROR_HAS_EMAIL');
                    break;
                default:
                    $result['error'] = Loc::getMessage('ERROR_OTHER_EMAIL');
                    break;
            }
        }
        //отправляем сообщение о подписке
        if ($result['success']) {
            $arEventFields = array(
                'EMAIL' => $email,
            );
            \CEvent::Send('SUBSCRIBE_CONFIRM', SITE_ID, $arEventFields);
        }

        return $result;
    }
}
