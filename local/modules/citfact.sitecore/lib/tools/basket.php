<?php

namespace Citfact\SiteCore\Tools;

class Basket
{
    /**
     * @param $itemId
     * @param $quantity
     * @return array
     */
    public static function addToBasket($itemId, $quantity)
    {
        global $APPLICATION;
        $cBasket = new \CSaleBasket();
        $cElement = new \CIBlockElement();
        $minOrder = 1;
        $dbRes = $cElement->GetList([], ['ID' => $itemId], false, false, ['ID', 'IBLOCK_ID', 'PROPERTY_MIN_ORDER']);
        while($obRes = $dbRes->Fetch()) {
            $intProductIBlockID = (int)$obRes['IBLOCK_ID'];
            //проверка на минимальное количество для заказа
            $minOrder = intval($obRes['PROPERTY_MIN_ORDER_VALUE'])?: 1;
        }

        $dbBasketItems = $cBasket->GetList(
            ['NAME' => 'ASC', 'ID' => 'ASC'],
            ['PRODUCT_ID' => $itemId, 'FUSER_ID' => $cBasket->GetBasketUserID(true), 'LID' => SITE_ID, 'ORDER_ID' => 'NULL'],
            false,
            false,
            ['ID', 'DELAY']
        )->Fetch();

        if (!empty($dbBasketItems) && $dbBasketItems['DELAY'] == 'Y') {
            $arFields = ['DELAY' => 'N', 'SUBSCRIBE' => 'N'];
            $strErrorExt = '';

            if (!empty($quantity)) {
                $arFields['QUANTITY'] = $quantity;
            }

            $cBasket->Update($dbBasketItems['ID'], $arFields);

            $addResult = [
                'STATUS' => 'OK',
                'ITEM_ID' => $itemId,
                'BASKET_PRICE' => self::getPrice(),
                'BASKET_QUANTITY' => self::getQuantity(),
                'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET',
                'MESSAGE_EXT' => $strErrorExt
            ];

        } else {
            $successfulAdd = true;
            $strErrorExt = '';

            if (0 >= $intProductIBlockID) {
                $strError = 'CATALOG_ELEMENT_NOT_FOUND';
                $successfulAdd = false;
            }

            if (true === $successfulAdd) {
                if ($quantity < $minOrder && empty($dbBasketItems)) {
                    $quantity = $minOrder;
                }
                $id = Add2BasketByProductID($itemId, $quantity, $arRewriteFields=['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom']);

                if (false === $id) {
                    if ($ex = $APPLICATION->GetException()) {
                        $strErrorExt = $ex->GetString();
                    }
                }

                $addResult = [
                    'STATUS' => 'OK',
                    'ITEM_ID' => $itemId,
                    'BASKET_PRICE' => self::getPrice(),
                    'BASKET_QUANTITY' => self::getQuantity(),
                    'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET',
                    'MESSAGE_EXT' => $strErrorExt
                ];
            } else {
                $addResult = [
                    'STATUS' => 'ERROR',
                    'ITEM_ID' => $itemId,
                    'MESSAGE' => empty($strError) ?: $strError,
                    'MESSAGE_EXT' => $strErrorExt
                ];
            }
        }

        return $addResult;
    }

    /**
     * @param $itemId
     * @param $quantity
     * @return array
     */
    public static function addToDelay($itemId, $quantity)
    {
        $cBasket = new \CSaleBasket();

        $successfulAdd = true;

        $dbBasketItems = $cBasket->GetList(
            ['NAME' => 'ASC', 'ID' => 'ASC'],
            [
                'PRODUCT_ID' => $itemId,
                'FUSER_ID' => $cBasket->GetBasketUserID(true),
                'LID' => SITE_ID,
                'ORDER_ID' => 'NULL',
                'CAN_BUY' => 'Y',
                'SUBSCRIBE' => 'N'
            ],
            false,
            false,
            ['ID', 'PRODUCT_ID', 'DELAY']
        )->Fetch();

        if (!empty($dbBasketItems) && $dbBasketItems['DELAY'] == 'N') {
            $arFields = ['DELAY' => 'Y', 'SUBSCRIBE' => 'N'];

            if (!empty($quantity)) {
                $arFields['QUANTITY'] = $quantity;
            }

            $cBasket->Update($dbBasketItems['ID'], $arFields);
            $event = 'ADD';
        } elseif (!empty($dbBasketItems) && $dbBasketItems['DELAY'] == 'Y') {
            $cBasket->Delete($dbBasketItems['ID']);
            $event = 'REMOVE';
        } else {
            $cElement = new \CIBlockElement();
            $minOrder = 1;
            $dbRes = $cElement->GetList([], ['ID' => $itemId], false, false, ['ID', 'IBLOCK_ID', 'PROPERTY_MIN_ORDER']);
            while($obRes = $dbRes->Fetch()) {
                //проверка на минимальное количество для заказа
                $minOrder = intval($obRes['PROPERTY_MIN_ORDER_VALUE'])?: 1;
            }

            if ($minOrder > $quantity && empty($dbBasketItems)) {
                $quantity = $minOrder;
            }
            $id = Add2BasketByProductID($itemId, $quantity, $arRewriteFields=['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom']);

            if (false === $id) {
                global $APPLICATION;

                if ($ex = $APPLICATION->GetException()) {
                    $strErrorExt = $ex->GetString();
                }

                $successfulAdd = false;
                $strError = 'ERROR_ADD2BASKET';
            }

            $arFields = array('DELAY' => 'Y', 'SUBSCRIBE' => 'N');
            $cBasket->Update($id, $arFields);
            $event = 'ADD';
        }

        if (true === $successfulAdd) {
            $dbBasketItemsRes = $cBasket->GetList(
                ['ID' => 'ASC'],
                [
                    'FUSER_ID' => $cBasket->GetBasketUserID(true),
                    'LID' => SITE_ID,
                    'ORDER_ID' => 'NULL',
                    'CAN_BUY' => 'Y',
                    'DELAY' => 'Y'
                ],
                false,
                false,
                ['ID', 'PRODUCT_ID', 'DELAY']
            );

            $arItems = [];

            while ($arBasketItem = $dbBasketItemsRes->fetch()) {
                $arItems[] = $arBasketItem;
            }

            $countFavorites = count($arItems);

            $addResult = [
                'STATUS' => 'OK',
                'COUNT' => $countFavorites,
                'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET',
                'MESSAGE_EXT' => empty($strErrorExt) ?: $strErrorExt,
                'EVENT' => $event,
            ];
        } else {
            $addResult = [
                'STATUS' => 'ERROR',
                'MESSAGE' => empty($strError) ?: $strError,
                'MESSAGE_EXT' => empty($strErrorExt) ?: $strErrorExt,
            ];
        }

        return $addResult;
    }

    /**
     * @param $itemId
     * @return array
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public static function removeToBasket($itemId)
    {
        $result = [
            'success' => false,
        ];
        $basket = self::getCurrentCart();
        if (!$basket->isEmpty())
        {
            $item = $basket->getItemByBasketCode($itemId);
            if ($item)
            {
                $resItem = $item->delete();
                if ($resItem->isSuccess()) {
                    $resBasket = $basket->save();
                    if ($resBasket->isSuccess()) {
                        $result['success'] = true;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param $items
     * @return array
     */
    public static function addToCartMultiple($items)
    {
        $addResultMultiple = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $addResultMultiple[] = self::addToBasket($item['ITEM_ID'], $item['QUANTITY']);
            }
        }

        return $addResultMultiple;
    }

    /**
     * @return \Bitrix\Sale\Basket
     */
    public static function getCurrentCart()
    {
        return \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(true),
            \Bitrix\Main\Context::getCurrent()->getSite()
        );
    }

    /**
     * @return int
     */
    public static function getPrice()
    {
        $basket = self::getCurrentCart();
        $basketItems = $basket->getBasketItems();
        $basketPrice = 0;

        foreach ($basketItems as $basketItem) {
            if (false === $basketItem->isDelay()) {
                $basketPrice += $basketItem->getFinalPrice();
            }
        }

        return $basketPrice;
    }

    /**
     * @return int
     */
    public static function getQuantity()
    {
        $basket = self::getCurrentCart();
        $basketItems = $basket->getBasketItems();
        $quantity = 0;

        foreach ($basketItems as $basketItem) {
            if (false === $basketItem->isDelay()) {
                $quantity++;
            }
        }
        return $quantity;
    }

    /**
     * @return int
     */
    public static function getQuantityDelayed()
    {
        $basket = self::getCurrentCart();
        $basketItems = $basket->getBasketItems();
        $quantity = 0;

        foreach ($basketItems as $basketItem) {
            if (true === $basketItem->isDelay()) {
                $quantity++;
            }
        }
        return $quantity;
    }
}