<?php

namespace Citfact\SiteCore\CatalogHelper;

use Citfact\SiteCore\Core;
use Bitrix\Sale;
use Citfact\SiteCore\OrderTemplate\OrderTemplateManager;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\UserDataManager;

class BasketRepository
{
    public function getItemsInfo(array $itemsIds)
    {
        $arReturn = ['ITEMS_INFO' => [], 'ITEMS_CATEGORIES' => []];

        if (empty($itemsIds)) {
            return $arReturn;
        }

        $core = Core::getInstance();
        $arItems = [];
        $arItemsCategories = [];

        $arOrder = array("SORT" => "ASC");
        $arFilter = array('IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ID' => $itemsIds);
        $arSelectFields = array("ID", 'IBLOCK_SECTION_ID', 'PROPERTY_UID_EDINITSY_IZMERENIYA_MEST', 'PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA_MEST');
        $rsElements = \CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
        while ($arElement = $rsElements->GetNext()) {
            $arItems[$arElement['ID']] = $arElement;

            // Определяем кирпич или газобетон
            $onlyPallet = false;
            $nav = \CIBlockSection::GetNavChain(false, $arElement['IBLOCK_SECTION_ID']);
            while ($sectionItem = $nav->Fetch()) {
                if ($sectionItem['ID'] == SECTION_ID_KIRPICH || $sectionItem['ID'] == SECTION_ID_GAZOBETON || $sectionItem['ID'] == SECTION_ID_TSVETNYE_KLADOCHNYE_RASTVORY) {
                    $onlyPallet = true;
                } elseif ($sectionItem['ID'] == SECTION_ID_UBLOCKS) {
                    $onlyPallet = false;
                }

                if ($sectionItem['ID'] == SECTION_ID_KIRPICH) {
                    $arItemsCategories['KIRPICH'] = true;
                }
                if ($sectionItem['ID'] == SECTION_ID_GAZOBETON) {
                    $arItemsCategories['GAZOBETON'] = true;
                }
                if ($sectionItem['ID'] == SECTION_ID_BETON) {
                    $arItemsCategories['BETON'] = true;
                }
                if ($sectionItem['ID'] == SECTION_ID_KIRPICH_I_GAZOBETON) {
                    $arItemsCategories['KIRPICH_I_GAZOBETON'] = true;
                }
            }
            $arItems[$arElement['ID']]['ONLY_PALLET'] = $onlyPallet;

            // Коэффициент для добавления кратно поддону
            if ($onlyPallet === true && $arElement['PROPERTY_UID_EDINITSY_IZMERENIYA_MEST_VALUE'] == MEASURE_CODE_PALLET) {
                $arItems[$arElement['ID']]['KOEFF_PALLET'] = $arElement['PROPERTY_KOEFFITSIENT_EDINITSY_IZMERENIYA_MEST_VALUE'];
            }
        }

        $arReturn['ITEMS_INFO'] = $arItems;
        $arReturn['ITEMS_CATEGORIES'] = $arItemsCategories;

        unset($arItems);
        unset($arItemsCategories);

        return $arReturn;
    }


    /**
     * Очищаем корзину
     *
     * @param bool $clearDelay
     */
    public function clearBasket($clearDelay=false)
    {
        $fuserId = Sale\Fuser::getId();
        $sid = \Bitrix\Main\Context::getCurrent()->getSite();
        $basket = Sale\Basket::loadItemsForFUser($fuserId, $sid);

        foreach ($basket as $basketItem) {
            if ($basketItem->isDelay()) {
                if ($clearDelay) {
                    $basketItem->delete();
                }
            } else {
                $basketItem->delete();
            }
        }
        $basket->save();

        $_SESSION['SALE_USER_BASKET_PRICE'][$sid][$fuserId] = $basket->getPrice();
    }

    /**
     * Проверяем пустая корзина или нет
     * @return bool
     */
    public static function isEmptyBasket()
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
        if (empty($basket)) {
            return false;
        }

        return true;
    }

    /**
     * Сохранить шаблон заказа
     *
     * @param $name
     * @param bool $isCurrent
     * @param $description
     * @return array|bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function saveOrderTemplate($name, $isCurrent=false, $description='')
    {
        global $USER;

        if (!$USER->IsAuthorized())
            return false;

        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

        if (empty($basket)) {
            return false;
        }

        $arItem = [];
        $i = 0;
        foreach ($basket as $basketItem) {
            if ($basketItem->isDelay()) {
            } else {
                $arItem[$i] = [
                    "PRODUCT_ID" => $basketItem->getProductId(),
                    "QUANTITY" => $basketItem->getQuantity()
                ];
                $i++;
            }
        }
        $orderTplManager = new OrderTemplateManager();

        $contragent = UserDataManager\UserDataManager::getContrAgentInfo()['UF_NAME'];
        $arParams = [
            'UF_TIMESTAMP' => new DateTime(),
            'UF_NAME' => $name,
            'UF_DESCRIPTION' => $description,
            'UF_PRODUCTS' => json_encode($arItem),
            'UF_USER' => $USER->GetID(),
            'UF_CONTRAGENT' => $contragent,
            'UF_IS_CURRENT' => $isCurrent?'1':'0',
        ];

        try {
            $orderTplManager->addTemplate($arParams);
            $status = 'success';
            $title = Loc::getMessage('THX');
            $msg = Loc::getMessage('TEMPLATE_SAVE');

        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
            $title = Loc::getMessage('ERROR');
            if ($e->getCode() == 400) {
                $msg = 'Шаблон с таким перечнем товара уже существует!';
            }
        }

        return [
            'status' => $status,
            'title' => $title,
            'msg' => $msg,
        ];
    }
}