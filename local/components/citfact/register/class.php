<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Sms;
use Citfact\SiteCore\User\UserRepository;
use Citfact\Tools\ElementManager;
use Citfact\Tools\Tools;

Loc::loadMessages(__FILE__);

class RegisterComponent extends CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            LocalRedirect('/account/settings/');
        }
        $app = Application::getInstance();
        $requestData = $app->getContext()->getRequest()->getPostList()->toArray();
        $this->arResult['REQUEST_DATA'] = Tools::requestSpecialChars($requestData);
        if ($requestData['CONFIRM_MODAL_SHOW'] == 'Y') {
            $this->sendConfirmCode($requestData);
        } elseif ($requestData['ENTER_CONFIRM_CODE'] == 'Y') {
            $this->enterConfirmCode($requestData);
        } elseif (
            $requestData['REGISTER'] == 'Y' &&
            $_SESSION['CONFIRM_CODE']['PHONE_' . $requestData['PHONE']]['ENTERED'] == 'Y'
        ) {
            $this->register($requestData);
        }

        if ($requestData['PHONE'] && $_SESSION['CONFIRM_CODE']['PHONE_' . $requestData['PHONE']]['ENTERED'] == 'Y') {
            $this->arResult['CONFIRM_CODE_SUCCESS'] = 'SUCCESS';
        }
        $this->IncludeComponentTemplate();
    }

    private function enterConfirmCode($requestData)
    {
        if (!$requestData['CONFIRM_CODE']) {
            $this->arResult['CONFIRM_CODE_ERROR'] = 'Заполните код подтверждения';
            return;
        }
        $code = $_SESSION['CONFIRM_CODE']['PHONE_' . $requestData['PHONE']]['CODE'];

        if ($requestData['CONFIRM_CODE'] == $code) {
            $_SESSION['CONFIRM_CODE']['PHONE_' . $requestData['PHONE']]['ENTERED'] = 'Y';
        } else {
            $this->arResult['CONFIRM_CODE_ERROR'] = 'Неверный код подтверждения';
        }
    }

    private function sendConfirmCode($requestData)
    {
        $sms = new Sms();
        if (!$requestData['PHONE']) {
            return;
        }
        $code = $_SESSION['CONFIRM_CODE']['PHONE_' . $requestData['PHONE']]['CODE'];
        if (!$code) {
            $code = strtolower(randString(5, '0123456789'));
            $sms->send($requestData['PHONE'], 'Код подтверждения: ' . $code);
            $_SESSION['CONFIRM_CODE']['PHONE_' . $requestData['PHONE']]['CODE'] = $code;
        }
    }

    private function register($requestData)
    {
        $elementManager = new ElementManager();
        $user = new \CUser();
        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByPhone($requestData['PHONE']);
        if ($userData) {
            $this->arResult['ERROR'] = 'Пользователь с таким телефоном уже зарегистрирован.';
            return;
        }

        $fields = array(
            'LOGIN' => $requestData['PHONE'],
            'EMAIL' => $requestData['EMAIL'],
            'NAME' => $requestData['NAME'],
            'PERSONAL_PHONE' => $requestData['PHONE'],
            'ACTIVE' => 'Y',
            'PASSWORD' => $requestData['PASSWORD'],
            'CONFIRM_PASSWORD' => $requestData['CONFIRM_PASSWORD'],
        );

        $userId = $user->Add($fields);
        if (!$userId) {
            $this->arResult['ERROR'] = $user->LAST_ERROR;
            return;
        }

        $this->arResult['SUCCESS'] = 'Вы успешно зарегистрированы.';
        $user->Authorize($userId);

        $arEventFields = array(
            'USER_ID' => $userId,
            'NAME' => $requestData['NAME'],
            'PHONE' => $elementManager->formatPhone($requestData['PHONE']),
            'LOGIN' => $requestData['PHONE'],
            'EMAIL' => $requestData['EMAIL'],
        );
        CEvent::Send('NEW_USER', SITE_ID, $arEventFields);
    }
}
