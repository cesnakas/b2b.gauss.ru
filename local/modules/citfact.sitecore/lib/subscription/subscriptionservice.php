<?php

namespace Citfact\Sitecore\Subscription;

use Bitrix\Sender\ContactTable;

class SubscriptionService
{
    /**
     * @param $email
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     */
    protected static function isExistEmail($email)
    {
        $dbEmails = ContactTable::getList(['select' => ['CODE', 'ID'], 'filter' => ['CODE'=> $email]]);

        $emailList = [];

        while ($email = $dbEmails->fetch()) {
            $emailList[] = $email['CODE'];
        }

        return !empty($emailList);
    }

    /**
     * @param $email
     * @return bool|null
     * @throws \Exception
     */
    public static function addEmail($email)
    {
        $checkFormat = ContactTable::checkEmail($email);
        
        if (true === $checkFormat && true !== self::isExistEmail($email)) {
            $contactAddDb = ContactTable::add(['TYPE_ID' => 1, 'CODE' => $email]);

            return $contactAddDb->isSuccess();
        }

        return null;
    }
}
