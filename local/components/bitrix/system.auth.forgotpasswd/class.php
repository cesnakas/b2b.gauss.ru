<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Sms;
use Citfact\SiteCore\User\UserRepository;

Loc::loadMessages(__FILE__);

class SystemAuthForgotPasswordComponent extends CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $userRepository = new UserRepository();
        $requestData = Application::getInstance()->getContext()->getRequest()->getPostList()->toArray();
        if ($requestData['PASSWORD_RESTORE'] && !$requestData['DO_NOT_FILL']) {
            $this->arResult['REQUEST_DATA'] = $requestData;
            $userData = $userRepository->getUserByPhone($requestData['PHONE']);
            if ($userData) {
                $this->restorePassword($userData);
            }

            $this->arResult['SUCCESS'] = 'Y';
        }
        $this->includeComponentTemplate();
    }

    private function restorePassword($userData)
    {
        if ($_SESSION['PASSWORD_RESTORE']['PHONE_' . $userData['PERSONAL_PHONE']] == 'Y') {
            return;
        }
        $user = new \CUser();
        $sms = new Sms();
        $password = strtolower(randString(6));
        $user->Update($userData['ID'], [
            'PASSWORD' => $password,
            'NAME' => $userData['NAME'],
            'PERSONAL_PHONE' => $userData['PERSONAL_PHONE'],
        ]);

        $sms->send($userData['PERSONAL_PHONE'], 'Ваш пароль: ' . $password);
        $_SESSION['PASSWORD_RESTORE']['PHONE_' . $userData['PERSONAL_PHONE']] = 'Y';
    }
}
