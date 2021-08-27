<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use \Citfact\Sitecore\Subscription\SubscriptionMarketing;

Loc::loadMessages(__FILE__);

class SubscriptionMarketingComponent extends CBitrixComponent
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     */
    public function executeComponent()
    {
        $app = Application::getInstance();
        $requestData = $app->getContext()->getRequest()->getQueryList()->toArray();
        if ($requestData['ADD_SUBSCRIPTION_MARKETING'] == 'Y' && !$requestData['DO_NOT_FILL']) {
            $res = SubscriptionMarketing::addEmail($requestData['EMAIL']);
            if (
                $res['success']
                || $res['error'] === SubscriptionMarketing::ERROR_HAS_EMAIL // если уже подписан. Не будем выводить ошибку
            ) {
                $this->arResult['SUCCESS'] = 'Y';

            } else {
                $this->arResult['ERROR'] = 'Y';
                switch ($res['error']) {
                    case SubscriptionMarketing::ERROR_INCORRECT_EMAIL:
                        $this->arResult['ERROR_TEXT'] = Loc::getMessage('ERROR_INCORRECT_EMAIL');
                        break;
                    default:
                        $this->arResult['ERROR_TEXT'] = Loc::getMessage('ERROR_OTHER_EMAIL');
                        break;
                }
            }
        }
        $this->IncludeComponentTemplate();
    }
}
