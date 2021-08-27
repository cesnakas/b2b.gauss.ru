<?php

namespace Citfact\SiteCore\User\Service;

class PortalAuthorizeByUser
{
    const SESSION_KEY = 'PORTAL_AUTHORIZE_BACK_DATA';
    const USER_NAME_KEY = 'AUTHORIZED_USER_NAME';
    const BACK_USER_ID_KEY = 'BACK_USER_ID';
    const BACK_URL_MANAGER = 'BACK_URL_MANAGER';

    public function saveSessionData($userId, $backUserId, $backUrl)
    {
        $_SESSION[self::SESSION_KEY] = [
            self::BACK_USER_ID_KEY => $userId,
            self::USER_NAME_KEY => $backUserId,
            self::BACK_URL_MANAGER => $backUrl,
        ];
    }

    public function unsetSessionData()
    {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public function getUserName()
    {
        return $_SESSION[self::SESSION_KEY][self::USER_NAME_KEY];
    }

    public function getBackUserId()
    {
        return $_SESSION[self::SESSION_KEY][self::BACK_USER_ID_KEY];
    }

    public function getBackUrl()
    {
        return $_SESSION[self::SESSION_KEY][self::BACK_URL_MANAGER];
    }
}

