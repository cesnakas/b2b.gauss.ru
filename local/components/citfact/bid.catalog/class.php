<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Result;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\BasketPropertyItem;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use Citfact\SiteCore\Bid\BidManager;
use Citfact\SiteCore\Bid\BidRepository;
use Citfact\Sitecore\Order\OrderRepository;
use Citfact\Tools\HLBlock;

Loc::loadMessages(__FILE__);

class BidCatalog extends CBitrixComponent
{
    private static $requiredParams = array(
        'SEF_FOLDER',
    );

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        global $USER;
        global $APPLICATION;
        $userId = $USER->GetID();
        $this->performActionCopyOrder();
        $this->confirmOrderInit();
        $this->resumeOrderInit();
        $dir = $APPLICATION->GetCurDir();

        $this->validateParams();
        $this->validatePath($dir);

        $dir = str_replace($this->arParams['SEF_FOLDER'], '', $dir);
        $dirExplode = explode('/', $dir);
        $urlPage = $dirExplode[0];
        $elementId = $dirExplode[1];

        $page = 'list';
        if (empty($elementId)) {
            $page = 'list';
        } elseif ($urlPage == 'bid') {
            $element = $this->getBid($elementId, $userId);
            if ($element) {
                $this->arResult['ELEMENT_ID'] = $element['ID'];
                $page = 'bid';
            } else {
                Tools::process404('MESSAGE_404', true, true, true);
            }
        } elseif ($urlPage == 'order') {
            $element = $this->getOrder($elementId, $userId);
            if ($element) {
                $this->arResult['ELEMENT_ID'] = $element['ID'];
                $page = 'order';
            } else {
                Tools::process404('MESSAGE_404', true, true, true);
            }
        }
        $this->IncludeComponentTemplate($page);
    }

    private function getBid($elementId, $userId)
    {
        $hlBlock = new HLBlock();
        $bidManager = new BidManager();
        $statuses = $bidManager->getBidStatuses('XML_ID');

        $hlBrands = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $res = $hlBrands::getList(array(
            'select' => array(
                'ID'
            ),
            'filter' => array(
                '=ID' => $elementId,
                '=UF_USER_ID' => $userId,
                '!=UF_STATUS' => $statuses[BidRepository::STATUS_CODE_CANCELED],
            ),
        ));
        $item = $res->Fetch();

        if (!$item) {
            return [];
        }

        return $item;
    }

    private function getOrder($elementId, $userId)
    {
        $saleOrder = new \CSaleOrder();
        $filter = [
            'ID' => $elementId,
            'USER_ID' => $userId,
        ];
        $res = $saleOrder->GetList([], $filter, false, false, ['ID']);
        $item = $res->Fetch();

        return $item;
    }

    private function validatePath($dir)
    {
        $dirExplode = explode('/', str_replace($this->arParams['SEF_FOLDER'], '', $dir));
        $sefFolderStrPos = strpos($dir, $this->arParams['SEF_FOLDER']);
        if (
            count($dirExplode) > 6 ||
            $sefFolderStrPos === false ||
            $sefFolderStrPos != 0 ||
            (
                $dirExplode[0] &&
                $dirExplode[0] != 'order' &&
                $dirExplode[0] != 'bid'
            )
        ) {
            Tools::process404('MESSAGE_404', true, true, true);
        }
    }

    private function validateParams()
    {
        foreach (self::$requiredParams as $code) {
            if (!$this->arParams[$code]) {
                throw new Exception('Wrong ' . $code . ' param.');
            }
        }
    }

    private function confirmOrderInit()
    {
        global $USER;
        $requestData = Application::getInstance()->getContext()->getRequest()->toArray();
        if (!$requestData['ID'] || $requestData['CONFIRM_ORDER'] != 'Y') {
            return;
        }
        $order = Order::load($requestData['ID']);
        if (!$order) {
            return;
        }
        $fieldValues = $order->getFieldValues();

        if (
            $USER->GetID() != $fieldValues['USER_ID'] ||
            $fieldValues['STATUS_ID'] != OrderRepository::STATUS_ID_WAITING_CONFIRM
        ) {
            return;
        }

        $order->setField('STATUS_ID', OrderRepository::STATUS_ID_WAITING_PAYMENT);
        $order->save();
    }

    private function resumeOrderInit()
    {
        global $USER;
        $event = new \CEvent();
        $requestData = Application::getInstance()->getContext()->getRequest()->toArray();
        if (!$requestData['ID'] || $requestData['RESUME_ORDER'] != 'Y') {
            return;
        }
        $order = Order::load($requestData['ID']);

        if (!$order) {
            return;
        }
        $fieldValues = $order->getFieldValues();

        if (
            $USER->GetID() != $fieldValues['USER_ID'] ||
            $fieldValues['STATUS_ID'] != OrderRepository::STATUS_ID_EXPIRED
        ) {
            return;
        }

        $event->Send('ORDER_RESUME', SITE_ID, [
            'ORDER_ID' => $requestData['ID']
        ]);
    }

    /**
     * Perform the following action: copy order
     * @return void
     */
    private function performActionCopyOrder()
    {
        $requestData = Application::getInstance()->getContext()->getRequest()->toArray();
        if (!$requestData['ID'] || $requestData['COPY_ORDER'] != 'Y') {
            return;
        }
        if ($id = $this->getRealId($requestData['ID'])) {
            $this->copyOrder2CustomerBasket($id);
        }
    }

    /**
     * Function checks if order with supplied id is really exists.
     * @param int|string $id Order id
     * @return int Order id
     */
    private function getRealId($id)
    {
        global $USER;

        $filter = array(
            'select' => array('ID'),
            'filter' => array('USER_ID' => $USER->GetID(), 'LID' => SITE_ID),
            'order' => array('ID' => 'DESC')
        );

        $filter['filter']['ID'] = $id;
        $orderList = Order::getList($filter);
        $orderResult = $orderList->fetch();

        if (empty($orderResult)) {
            return false;
        }

        return $orderResult['ID'];
    }

    /**
     * Function performs moving entire basket content of a certain order into client`s basket. It implements "copy order" action.
     * @param int $id Order id
     * @return void
     */
    private function copyOrder2CustomerBasket($id)
    {
        if (!$id) {
            return;
        }
        $result = new Result();

        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());

        $filterFields = array(
            'SET_PARENT_ID', 'TYPE',
            'PRODUCT_ID', 'PRODUCT_PRICE_ID', 'PRICE', 'CURRENCY', 'WEIGHT', 'QUANTITY', 'LID',
            'NAME', 'CALLBACK_FUNC', 'NOTES', 'PRODUCT_PROVIDER_CLASS', 'CANCEL_CALLBACK_FUNC',
            'ORDER_CALLBACK_FUNC', 'PAY_CALLBACK_FUNC', 'DETAIL_PAGE_URL', 'CATALOG_XML_ID', 'PRODUCT_XML_ID',
            'VAT_RATE', 'MEASURE_NAME', 'MEASURE_CODE', 'BASE_PRICE', 'VAT_INCLUDED'
        );
        $filterFields = array_flip($filterFields);

        $oldOrder = Order::load($id);

        $oldBasket = $oldOrder->getBasket();
        $oldBasketItems = $oldBasket->getBasketItems();

        /** @var BasketItem $oldBasketItem */
        foreach ($oldBasketItems as $oldBasketItem) {
            $propertyList = array();
            if ($oldPropertyCollection = $oldBasketItem->getPropertyCollection()) {
                $propertyList = $oldPropertyCollection->getPropertyValues();
            }

            $item = $basket->getExistsItem($oldBasketItem->getField('MODULE'), $oldBasketItem->getField('PRODUCT_ID'), $propertyList);

            if ($item) {
                $resultItem = $item->setField('QUANTITY', $item->getQuantity() + $oldBasketItem->getQuantity());
            } else {
                $item = $basket->createItem($oldBasketItem->getField('MODULE'), $oldBasketItem->getField('PRODUCT_ID'));
                $oldBasketValues = array_intersect_key($oldBasketItem->getFieldValues(), $filterFields);
                $item->setField('NAME', $oldBasketValues['NAME']);
                $resultItem = $item->setFields($oldBasketValues);
                $newPropertyCollection = $item->getPropertyCollection();

                /** @var BasketPropertyItem $oldProperty */
                foreach ($propertyList as $oldPropertyFields) {
                    $propertyItem = $newPropertyCollection->createItem();
                    unset($oldPropertyFields['ID'], $oldPropertyFields['BASKET_ID']);

                    /** @var BasketPropertyItem $propertyItem */
                    $propertyItem->setFields($oldPropertyFields);
                }
            }
            if (!$resultItem->isSuccess()) {
                $result->addErrors($resultItem->getErrors());
            }
        }

        if ($result->isSuccess()) {
            $basket->save();
        } else {
            $errorList = $result->getErrors();
            foreach ($errorList as $key => $error) {
                $this->arResult['ERRORS_NON_FATAL'][$error->getCode() . "_" . $key] = $error->getMessage();
            }
        }

        LocalRedirect('/cart/');
    }
}
