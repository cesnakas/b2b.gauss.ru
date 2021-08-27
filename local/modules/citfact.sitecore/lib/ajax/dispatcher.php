<?php

namespace Citfact\SiteCore\Ajax;

session_start();

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Sale\Fuser;
use Bitrix\Seo\Engine\Bitrix;
use Citfact\SiteCore\CatalogHelper\BasketRepository;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\Favorites\Favorites;
use Citfact\SiteCore\OrderTemplate\OrderTemplateManager;
use Citfact\Sitecore\Subscription\SubscriptionMarketing;
use Citfact\Tools\Tools;
use Bitrix\Main\Loader;
use Citfact\Sitecore\UserDataManager;
use Citfact\SiteCore\Tools\Basket as Basket;

class Dispatcher
{
    /**
     * Dispatcher constructor.
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function __construct()
    {
        Loader::includeModule('sender');
        $app = Application::getInstance();
        $request = $app->getContext()->getRequest();
        $action = $request->getPost('action');
        $itemId = (int)$request->getPost('item');
        $quantity = floatval($request->getPost('quantity'));
        $orderTplManager = new OrderTemplateManager();
        $basket = new BasketRepository();

        // Добавление в корзину
        if ($action === 'add2basket') {
            $cBasket = new \CSaleBasket();
            $cElement = new \CIBlockElement();

            $itemId = (int)$request->getPost('item');
            $quantity = floatval($request->getPost('quantity'));
            $add_item = $request->getPost('add_item');

            $dbBasketItems = $cBasket->GetList(
                array('NAME' => 'ASC', 'ID' => 'ASC'),
                array('PRODUCT_ID' => $itemId, 'FUSER_ID' => $cBasket->GetBasketUserID(), 'LID' => SITE_ID, 'ORDER_ID' => 'NULL'),
                false, false, array('ID', 'DELAY', 'QUANTITY')
            )->Fetch();

            $strErrorExt = '';
            if (!empty($dbBasketItems) && $dbBasketItems['DELAY'] == 'Y') {
                $arFields = array('DELAY' => 'N', 'SUBSCRIBE' => 'N');
                if ($quantity) {
                    $arFields['QUANTITY'] = $quantity;
                } else {
                    $arFields['QUANTITY'] = 1;
                }

                if ($add_item == 'Y') {
                    $arFields['QUANTITY'] = $dbBasketItems['QUANTITY'] + $quantity;
                }

                $cBasket->Update($dbBasketItems['ID'], $arFields);

                // Считаем количество и избранном
                $dbBasketItemsRes = $cBasket->GetList(
                    array('ID' => 'ASC'),
                    array('FUSER_ID' => $cBasket->GetBasketUserID(), 'LID' => SITE_ID, 'ORDER_ID' => 'NULL', 'CAN_BUY' => 'Y', 'DELAY' => 'Y'),
                    false, false, array('ID', 'PRODUCT_ID', 'DELAY')
                );
                $arItems = array();
                while ($arBasketItem = $dbBasketItemsRes->fetch()) {
                    $arItems[] = $arBasketItem;
                }
                $countFavorites = count($arItems);
                $countString = Tools::declension($countFavorites, ['товар', 'товара', 'товаров']);

                $addResult = array(
                    'STATUS' => 'OK',
                    'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET',
                    'MESSAGE_EXT' => $strErrorExt,
                    'DELAY_COUNT_STRING' => $countString,
                    'QUANTITY'=>$arFields['QUANTITY']
                );

            } else {
                $successfulAdd = true;
                $intProductIBlockID = (int)$cElement->GetIBlockByID($itemId);
                if (0 < $intProductIBlockID) {

                } else {
                    $strError = 'CATALOG_ELEMENT_NOT_FOUND';
                    $successfulAdd = false;
                }

                if ($successfulAdd) {
                    $basket = self::getCurrentCart();

                    /**
                     * @var $basketItem \Bitrix\Sale\BasketItem
                     */
                    $hasBasketItem = false;
                    $basketItems = $basket->getBasketItems();
                    foreach ($basketItems as $basketItem) {
                        if (
                            $basketItem->isDelay() === false // не отложен
                            && $basketItem->getProductId() == $itemId // наш товар
                        ) {
                            if ($quantity === '0' || $quantity === 0 || $quantity === .0) { // если передали 0 - удаляем товар
                                $basketItem->delete();

                            } else { // если не ноль (1, 2, 3 или ничего)
                                if ($add_item == 'Y') {
                                    $quantity = $basketItem->getQuantity() + $quantity;
                                } else {
                                    if (!$quantity) {
                                        $quantity = $basketItem->getQuantity() + 1;
                                    }
                                }

                                $basketItem->setField('QUANTITY', $quantity);
                            }

                            $res = $basket->save();
                            if (!$res->isSuccess()) {
                                $strErrorExt = implode(' ', $res->getErrorMessages());
                            }
                            $hasBasketItem = true;
                            break;
                        }
                    }

                    if (!$hasBasketItem) {
                        if (!$quantity) {
                            $quantity = 1;
                        }

                        $id = Add2BasketByProductID($itemId, $quantity, $arRewriteFields=['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom'], $product_properties=[]);
                        if (!$id) {
                            global $APPLICATION;
                            if ($ex = $APPLICATION->GetException())
                                $strErrorExt = $ex->GetString();

                            $strError = 'ERROR_ADD2BASKET';
                            $successfulAdd = false;
                        }
                    }


                    $addResult = [
                        'STATUS' => 'OK',
                        'ITEM_ID' => $itemId,
                        'BASKET_PRICE' => self::getPrice(),
                        'BASKET_QUANTITY' => self::getQuantity(),
                        'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET',
                        'MESSAGE_EXT' => $strErrorExt,
                        'QUANTITY' => $quantity,
                    ];

                } else {
                    $addResult = array(
                        'STATUS' => 'ERROR',
                        'ITEM_ID' => $itemId,
                        'MESSAGE' => empty($strError) ?: $strError,
                        'MESSAGE_EXT' => $strErrorExt
                    );
                }
            }

            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                Fuser::getId(),
                Context::getCurrent()->getSite()
            );

            $basket->refreshData();
            $basket->save();

            echo json_encode($addResult);
        }


        // Добавление в избранное
        if ($action == 'add2delay') {
            $addResult = Basket::addToDelay($itemId, $quantity);

            echo json_encode($addResult);
        }


        // Множественное добавление в корзину (каталог таблицей)
        if ($action == 'add2cartMultiple') {

            $cElement = new \CIBlockElement();
            $items = (array) $request->getPost('items');
            $productsToBasket = [];

            if (!empty($items)) {

                $result = ['STATUS' => 'EMPTY', 'MESSAGE' => '', 'MESSAGE_TEXT' => ''];
                $core = Core::getInstance();

                $dbIBlockElements = $cElement->GetList(
                                      ['SORT' => 'ASC'],
                                      [
                                          'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
                                          'ID' => array_keys($items)
                                      ],
                                      ['IBLOCK_ID', 'ID', 'NAME']);

                while ($iblockElement = $dbIBlockElements->Fetch()) {
                    if (!empty($items[$iblockElement['ID']])) {
                        $product = $items[$iblockElement['ID']];
                        $productsToBasket[$iblockElement['ID']] = [
                            'PRODUCT_ID' => $product['id'],
                            'QUANTITY'   => $product['quantity'],
                        ];
                    }
                }

                if (empty($productsToBasket)) {

                    $result['STATUS'] = 'ERROR';
                    $result['MESSAGE'] = 'CATALOG_ELEMENTS_NOT_FOUND';

                    echo json_encode($result);
                    die;

                } else {

                    $resultAdd = \Citfact\Sitecore\Order\Basket::addProducts($productsToBasket, ['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom']);

                    if (!$resultAdd->isSuccess()) {
                        $exceptionText = implode(' ,', $resultAdd->getErrorMessages());

                        $result = [
                            'STATUS' => 'ERROR',
                            'MESSAGE' => 'ERROR_ADD2BASKET',
                            'MESSAGE_TEXT' => $exceptionText,
                            'INFO' => 'ERROR',
                        ];

                        echo json_encode($result);
                        die;

                    } else {
                        $result = [
                            'STATUS' => 'OK',
                            'MESSAGE' => 'CATALOG_SUCCESSFUL_UPDATE_BASKET',
                        ];
                    }
                }

                echo json_encode($result);
            }
        }

        // Подписка (в футере)
        if ($action == 'subscribe') {
            $email = $request->getPost('EMAIL');
            $result = SubscriptionMarketing::subscribeEvent($email);

            echo json_encode($result);
        }
        if ($action === 'setContragent') {
            $contragentXmlId = $request->getPost('contragentXmlId');
            UserDataManager\UserDataManager::setContrAgent($contragentXmlId);
            UserDataManager\UserDataManager::getUserPriceType();
        }
        if ($action === 'saveCart') {
            $basket = new BasketRepository();
            $request = Application::getInstance()->getContext()->getRequest();
            $post_data = array(
                'clear_basket' => $request->getPost('clear_basket'),
                'sessid' => $request->getPost('sessid'),
                'saveTemplateOrder' => $request->getPost('saveTemplateOrder'),
                'dateNotify' => $request->getPost('dateNotify'),
            );

            if ($post_data["clear_basket"] == 'Y') {
                $basket->clearBasket();
                echo json_encode(array('status' => 'success'));
            } else if ($post_data["saveTemplateOrder"] == 'Y') {
                $GLOBALS['APPLICATION']->RestartBuffer();
                if ((bitrix_sessid() == $post_data["sessid"])) {
                    if ($post_data["saveTemplateOrder"] == 'Y') {
                        $name = $request->getPost('name');
                        $description = $request->getPost('description');
                        $result = $basket->saveOrderTemplate($name, false, $description);
                        echo json_encode($result);
                    }
                }
            }
        }
        if ($action === 'deleteCart') {
            $cartId = $request->getPost('savedCartId');
            $orderTplManager->delete($cartId);
        }

        if ($action === 'addCart') {
            $cartId = $request->getPost('savedCartId');
            $orderTplManager->createOrderByTemplate($cartId);
        }

        if ($action === 'updateCartTemplate') {
            $cartId = $request->getPost('savedCartId');
            $products = \CUtil::PhpToJSObject($request->getPost('products'));

            $orderTplManager->updateTemplate($cartId, $products);
        }

        if ($action === 'restoreCart') {
            $cartId = $request->getPost('savedCartId');
            $basket->saveOrderTemplate("Текущая корзина пользователя от ".date('d.m.Y'), true);
            $basket->clearBasket();
            $orderTplManager->createOrderByTemplate($cartId);
        }

        if ($action === 'changeCart') {
            $cartId = $request->getPost('savedCartId');
            $basket->clearBasket();
            $orderTplManager->createOrderByTemplate($cartId);
        }

        // Добавление в избранное
        if ($action === 'add2favorites') {
            $result = Favorites::addToFavorites($itemId, $quantity);
            echo json_encode($result);
        }

        ///Deactivate user
        ///Изменение статуса пользователя
        if ($action === 'deactivateUser') {
            $userId = $request->getPost('user')['id'];
            $xmlContragent = $request->getPost('contragentXmlId');
            if (!empty($userId)) {
                $cuser = new \CUser();
                $rsUser = \CUser::GetByID($userId);
                $userFields = [
                    'UF_ACTIVATE_PROFILE' => 0,
                    'UF_DELETE' => 1
                ];
                $isSuccess = $cuser->Update($userId, $userFields);

                $userName = '';
                $assistentName = [];
                $managerName = '';

                if ($userData = $rsUser->GetNext()) {
                    $userName = \Citfact\SiteCore\User\UserHelper::getFullNameByUser($userData);
                }


                $userManager = \Citfact\SiteCore\User\UserManagers::getManagerByContragent($xmlContragent);
                $userAssistants = \Citfact\SiteCore\User\UserManagers::getAssistantsByContragent($xmlContragent);

                $managerName = \Citfact\SiteCore\User\UserHelper::getFullNameByUser($userManager);
                if (!empty($userAssistants)) {
                    foreach ($userAssistants as $assistent) {
                        $assistentName[] = \Citfact\SiteCore\User\UserHelper::getFullNameByUser($assistent);
                    }
                }

                if ($isSuccess) {
                    $arEventFields = [
                        'RS_DATE_CREATE' => date('d.m.Y H:i:s'),
                        'RS_USER_ID' => $userId,
                        'RS_USER_NAME' => $userName,
                        'RS_MANAGER_NAME' => $managerName,
                        'RS_ASSISTENT_NAME' => implode(', ', $assistentName),
                    ];
                    \CEvent::Send('REQUEST_TO_DELETE_USER', SITE_ID, $arEventFields);
                }
            }
        }
    }


    /**
     * @return \Bitrix\Sale\BasketBase
     */
    public static function getCurrentCart()
    {
        return \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            \Bitrix\Main\Context::getCurrent()->getSite()
        );
    }

    /**
     * @return float|int
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function getPrice()
    {
        $basket = self::getCurrentCart();
        $basketItems = $basket->getBasketItems();
        $basketPrice = 0;

        /**
         * @var \Bitrix\Sale\BasketItem $basketItem
         */

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
}