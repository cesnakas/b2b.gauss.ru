<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use Citfact\SiteCore\Bid\BidManager;
use Citfact\SiteCore\Bid\BidRepository;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\Order\OrderManager;
use Citfact\Tools\HLBlock;
use Citfact\Tools\UserField\UserFieldEnumRepository;

Loc::loadMessages(__FILE__);

class BidList extends CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $orderManager = new OrderManager();
        $bidManager = new BidManager();
        $this->connectRequestFilter();
        $this->connectRequestFilter();
        $this->arResult['STATUSES']['BID_BY_XML_ID'] = $bidManager->getBidStatuses('XML_ID');
        $this->arResult['STATUSES']['BID'] = $bidManager->getBidStatuses();
        $this->arResult['STATUSES']['ORDER'] = $orderManager->getOrderStatuses();
        $this->arResult['ITEMS'] = $this->getBidItems();
        $this->connectOrders();

        $this->includeComponentTemplate();
    }

    private function connectRequestFilter()
    {
        $requestData = Application::getInstance()->getContext()->getRequest()->toArray();
        $this->arResult['FILTER'] = $requestData['FILTER'];
        array_walk_recursive($this->arResult['FILTER'], function (&$item) {
            $item = htmlspecialchars(strip_tags(trim($item)));
        });
    }

    private function getBidItems()
    {
        global $USER;
        $hlBlock = new HLBlock();
        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $result = [];

        $filter = [
            '=UF_USER_ID' => $USER->GetID(),
            '!=UF_STATUS' => $this->arResult['STATUSES']['BID_BY_XML_ID'][BidRepository::STATUS_CODE_CANCELED],
        ];
        if ($this->arResult['FILTER']['DATE_START']) {
            $filter['>=UF_DATETIME'] = $this->arResult['FILTER']['DATE_START'] . ' 00:00:00';
        }
        if ($this->arResult['FILTER']['DATE_END']) {
            $filter['<=UF_DATETIME'] = $this->arResult['FILTER']['DATE_END'] . ' 23:59:59';
        }
        $res = $bidsEntity::getList([
            'order' => ['ID' => 'DESC'],
            'filter' => $filter,
        ]);
        while ($item = $res->fetch()) {
            $item['UF_ITEMS'] = json_decode($item['UF_ITEMS'], true);
            $item['SUM'] = 0;
            foreach ($item['UF_ITEMS'] as $product) {
                if (!$product['PRICE']) {
                    continue;
                }
                $item['SUM'] += $product['PRICE'] * $product['QTY'];
            }
            $item['SUM'] = number_format($item['SUM'], 2, '.', ' ');
            $result[$item['ID']] = $item;
        }

        return $result;
    }

    private function connectOrders()
    {
        $saleOrder = new \CSaleOrder();
        $select = [
            'ID',
            'DATE_INSERT',
            'PRICE',
            'STATUS_ID',
        ];
        $filter = [
            'PROPERTY_VAL_BY_CODE_BID' => array_keys($this->arResult['ITEMS'])
        ];
        $res = $saleOrder->GetList([], $filter, false, false, $select);
        while ($item = $res->Fetch()) {
            $item['PRICE'] = number_format($item['PRICE'], 2, '.', ' ');
            $order = Order::load($item['ID']);
            $propertyCollection = $order->getPropertyCollection();
            $properties = $propertyCollection->getArray();
            foreach ($properties['properties'] as $property) {
                $item['PROP'][$property['CODE']] = $property['VALUE'];
            }
            if ($item['PROP']['BID'][0]) {
                $this->arResult['ITEMS'][$item['PROP']['BID'][0]]['ORDERS'][$item['ID']] = $item;
            }
        }
    }
}
