<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Bid\BidManager;
use Citfact\SiteCore\Bid\BidRepository;
use Citfact\SiteCore\Core;
use Citfact\Tools\ElementManager;
use Citfact\Tools\HLBlock;
use Bitrix\Iblock\Component\Tools;
use Citfact\Tools\UserField\UserFieldEnumRepository;

Loc::loadMessages(__FILE__);

class BidDetail extends CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $requestData = Application::getInstance()->getContext()->getRequest()->toArray();
        $this->arResult['CHANGE_MODE'] = (
            $requestData['BID_CHANGE_QUANTITY'] == 'Y' ||
            $requestData['BID_DELETE_PRODUCT'] == 'Y'
        );

        $this->arResult['ITEM'] = $this->getItem();
        if (!$this->arResult['ITEM']) {
            Tools::process404('MESSAGE_404', true, true, true);
        }

        $this->arResult['BID_STATUSES'] = $this->getBidStatuses();
        $this->bidCancelInit($requestData);
        $this->arResult['IS_USER_CAN_CHANGE'] = $this->isUserCanChange($this->arResult['ITEM']['ID'], $this->arResult['ITEM']['UF_STATUS']);

        if ($this->arResult['IS_USER_CAN_CHANGE']) {
            $this->changeBidQuantityInit($requestData);
            $this->bidDeleteProductInit($requestData);
        }

        $this->arResult['ITEM'] = $this->formatItem($this->arResult['ITEM']);
        $this->arResult['PRODUCTS'] = $this->getProducts();

        $this->includeComponentTemplate();
        if (
            $requestData['BID_CANCEL'] == 'Y' ||
            $requestData['BID_CHANGE_QUANTITY'] == 'Y' ||
            $requestData['BID_DELETE_PRODUCT'] == 'Y'
        ) {
            die;
        }
    }

    private function getBidStatuses()
    {
        $bidManager = new BidManager();
        return $bidManager->getBidStatuses();
    }

    private function isUserCanChange($id, $status)
    {
        $saleOrder = new \CSaleOrder();
        if ($this->arResult['BID_STATUSES'][$status]['XML_ID'] != BidRepository::STATUS_CODE_FORMED) {
            return false;
        }

        $filter = ['PROPERTY_VAL_BY_CODE_BID' => $id];
        $res = $saleOrder->GetList([], $filter, false, false, ['ID']);
        $order = $res->Fetch();
        if ($order) {
            return false;
        }
        return true;
    }

    private function changeBidQuantityInit($requestData)
    {
        $hlBlock = new HLBlock();
        if (
            !$requestData['BID_ID'] ||
            !$requestData['PRODUCT_ID'] ||
            !$requestData['QUANTITY'] ||
            $requestData['BID_CHANGE_QUANTITY'] != 'Y'
        ) {
            return;
        }

        foreach ($this->arResult['ITEM']['UF_ITEMS'] as &$item) {
            if ($item['ID'] != $requestData['PRODUCT_ID']) {
                continue;
            }
            $item['QTY'] = $requestData['QUANTITY'];
            break;
        }
        unset($item);

        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $bidsEntity::update(
            $this->arResult['ITEM']['ID'],
            [
                'UF_ITEMS' => json_encode($this->arResult['ITEM']['UF_ITEMS'], true),
                'UF_NEED_EXPORT' => 1,
            ]
        );
    }

    private function bidCancelInit($requestData)
    {
        $event = new \CEvent();
        $hlBlock = new HLBlock();
        if (
            !$requestData['BID_ID'] ||
            $requestData['BID_CANCEL'] != 'Y'
        ) {
            return;
        }

        $statusId = '';
        foreach ($this->arResult['BID_STATUSES'] as $status) {
            if ($status['XML_ID'] == BidRepository::STATUS_CODE_CANCELED) {
                $statusId = $status['ID'];
                break;
            }
        }

        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $bidsEntity::update(
            $this->arResult['ITEM']['ID'],
            [
                'UF_STATUS' => $statusId,
                'UF_NEED_EXPORT' => 1,
            ]
        );
        $this->arResult['ITEM']['UF_STATUS'] = $statusId;

        $event->Send('BID_CANCEL', SITE_ID, [
            'BID_ID' => $this->arResult['ITEM']['ID']
        ]);
    }


    private function bidDeleteProductInit($requestData)
    {
        $hlBlock = new HLBlock();
        if (
            !$requestData['BID_ID'] ||
            !$requestData['PRODUCT_ID'] ||
            $requestData['BID_DELETE_PRODUCT'] != 'Y'
        ) {
            return;
        }

        foreach ($this->arResult['ITEM']['UF_ITEMS'] as $key => &$item) {
            if ($item['ID'] != $requestData['PRODUCT_ID']) {
                continue;
            }
            unset($this->arResult['ITEM']['UF_ITEMS'][$key]);
            break;
        }
        unset($item);

        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $bidsEntity::update(
            $this->arResult['ITEM']['ID'],
            [
                'UF_ITEMS' => json_encode($this->arResult['ITEM']['UF_ITEMS'], true),
                'UF_NEED_EXPORT' => 1,
            ]
        );
    }

    private function getProducts()
    {
        $result = [];
        if (!$this->arResult['ITEM_IDS']) {
            return $result;
        }
        $file = new \CFile();
        $core = Core::getInstance();
        $iblockElement = new \CIBlockElement();
        $filter = [
            'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
            'ID' => $this->arResult['ITEM_IDS'],
        ];
        $select = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'DETAIL_PAGE_URL',
        ];
        $res = $iblockElement->GetList([], $filter, false, false, $select);

        while ($item = $res->GetNext()) {
            if ($item['PREVIEW_PICTURE']) {
                $picture = $file->ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 85, 'height' => 66), BX_RESIZE_IMAGE_EXACT);
                $item['IMG'] = $picture['src'];
            } else {
                $item['IMG'] = '/local/client/img/no_photo_85_66_new.png';
            }

            $result[$item['ID']] = $item;
        }
        return $result;
    }

    private function getItem()
    {
        global $USER;
        $hlBlock = new HLBlock();
        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $result = [];

        $filter = [
            'UF_USER_ID' => $USER->GetID(),
            'ID' => $this->arParams['ELEMENT_ID'],
        ];
        $res = $bidsEntity::getList([
            'order' => ['ID' => 'DESC'],
            'filter' => $filter,
        ]);

        while ($item = $res->fetch()) {
            $item['UF_ITEMS'] = json_decode($item['UF_ITEMS'], true);
            $result = $item;
        }
        return $result;
    }

    private function formatItem($item)
    {
        $elementManager = new ElementManager();

        $this->arResult['SUM'] = 0;
        if ($item['UF_DELIVERY_DATE']) {
            $item['UF_DELIVERY_DATE'] = CIBlockFormatProperties::DateFormat('d F Y', MakeTimeStamp($item['UF_DELIVERY_DATE'], CSite::GetDateFormat()));
        }
        $item['UF_USER_PHONE'] = $elementManager->formatPhone($item['UF_USER_PHONE']);
        foreach ($item['UF_ITEMS'] as &$product) {
            $this->arResult['ITEM_IDS'][$product['ID']] = $product['ID'];
            $sum = $product['PRICE'] * $product['QTY'];
            $this->arResult['SUM'] += $sum;
            $product['SUM'] = number_format($sum, 2, '.', ' ');
            $product['PRICE'] = number_format($product['PRICE'], 2, '.', ' ');
        }
        unset($product);
        $this->arResult['SUM'] = number_format($this->arResult['SUM'], 2, '.', ' ');

        return $item;
    }
}
