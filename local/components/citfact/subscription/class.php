<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Citfact\Tools\Tools;

Loc::loadMessages(__FILE__);

class SubscriptionComponent extends CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $app = Application::getInstance();
        $requestData = $app->getContext()->getRequest()->getPostList()->toArray();
        $this->arResult['REQUEST_DATA'] = Tools::requestSpecialChars($requestData);
        if ($requestData['ADD_SUBSCRIPTION'] == 'Y' && !$requestData['DO_NOT_FILL']) {
            $this->addSubscription($requestData['EMAIL']);
            $this->arResult['SUCCESS'] = 'Y';
        }
        $this->IncludeComponentTemplate();
    }

    private function getRubricIds()
    {
        $rubric = new \CRubric();
        $ids = [];
        $res = $rubric->GetList();
        while ($item = $res->Fetch()) {
            $ids[] = $item['ID'];
        }
        return $ids;
    }

    private function addSubscription($email)
    {
        if (!$email) {
            return;
        }
        $subscription = new \CSubscription();
        $item = $this->getSubscription($email);
        $rubricIds = $this->getRubricIds();
        if ($item) {
            $subscription->Update($item['ID'], [
                'ACTIVE' => 'Y',
                'CONFIRMED' => 'Y',
                'RUB_ID' => $rubricIds,
            ]);
            return;
        }
        $subscription->Add([
            'EMAIL' => $email,
            'FORMAT' => 'html',
            'CONFIRMED' => 'Y',
            'RUB_ID' => $rubricIds,
        ]);
    }

    private function getSubscription($email)
    {
        $subscription = new \CSubscription();
        $res = $subscription->GetList([], ['EMAIL' => $email]);
        return $res->Fetch();
    }
}
