<?php

namespace Citfact\Sitecore\Order;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale;
use Bitrix\Main\Page\Frame;
use Bitrix\Sale\Fuser;
use Citfact\SiteCore\Core;

class Basket
{

    public static function addProducts(array $products, array $fields = [])
    {
        Loader::includeModule('sale');

        $productFields['MODULE'] = 'catalog';
        $productFields['PRODUCT_PROVIDER_CLASS'] = \Bitrix\Catalog\Product\Basket::getDefaultProviderName();

        if (!empty($fields)) {
            $productFields = array_merge($productFields, $fields);
        }


        self::setEventForStatictic($products);


        $context = array(
            'SITE_ID' => SITE_ID,
        );
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), SITE_ID);

        $options['CHECK_PERMISSIONS'] = 'Y';
        $options['USE_MERGE'] = (isset($options['USE_MERGE']) && $options['USE_MERGE'] == 'N' ? 'N' : 'Y');
        $options['CHECK_CRAWLERS'] = 'Y';

        foreach ($products as $product) {

            ///TODO Fields
            $fields = [
                'PRODUCT_ID' => $product['id'] ? intval($product['id']) : intval($product['PRODUCT_ID']),
                'QUANTITY' => $product['quantity'] ? intval($product['quantity']) : intval($product['QUANTITY']),
            ];

            $product = array_merge($fields, $productFields);

            \Bitrix\Catalog\Product\Basket::addProductToBasketWithPermissions($basket, $product, $context, $options);
        }

        $basket->refreshData();

        return $basket->save();
    }

    protected static function setEventForStatictic(array $products)
    {

        if (!Loader::includeModule('statistic')) {
            return;
        }

        if (empty($products)) {
            return;
        }

        $ids = [];

        foreach ($products as $product) {
            $ids[] = $product['PRODUCT_ID'];
        }

        if (empty($ids)) {
            return;
        }

        $core = Core::getInstance();

        $dbIblockElements = \CIBlockElement::GetList(['SORT' => 'ASC'],
                                       ['IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ID' => $ids],
                                       false,
                                       false,
                                       ['IBLOCK_ID', 'ID', 'NAME', 'DETAIL_PAGE_URL']);
        while ($iblockElement = $dbIblockElements->GetNext()) {
            \CStatistic::Set_Event(
                'sale2basket', 'catalog', $iblockElement['DETAIL_PAGE_URL']
            );
        }
    }

    public static function getBasketItems()
    {
        $arBasketItems = array('BASKET' => [], 'DELAY' => []);
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
        //$basketItems = $basket->getBasketItems();
        foreach ($basket as $basketItem) {
            /** @var Sale\BasketItem $basketItem */
            $arTemp = array();
            $arTemp['ID'] = $basketItem->getId();
            $arTemp['PRODUCT_ID'] = $basketItem->getProductId();
            $arTemp['XML_ID'] = $basketItem->getField('PRODUCT_XML_ID');
            $arTemp['QTY'] = $basketItem->getQuantity();
            $arTemp['PRICE'] = $basketItem->getPrice();
            if ($basketItem->isDelay()) {
                $arBasketItems['DELAY'][$arTemp['PRODUCT_ID']] = $arTemp;
            } else {
                $arBasketItems['BASKET'][$arTemp['PRODUCT_ID']] = $arTemp;
            }
        }

        return $arBasketItems;
    }


    /**
    Очистка корзины пользователя
    */
    public function clearBasketItems()
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
        foreach ($basket as $basketItem) {
            /** @var Sale\BasketItem $basketItem */
            if (!$basketItem->isDelay()) {
                $basketItem->delete();
            }
        }
        $basket->save();
    }


    public function setBasketItemsOnLoad()
    {
        Frame::getInstance()->startDynamicWithID("basketitems-block");
        $arItems = $this->getBasketItems();
        $arFavorites = \Citfact\Sitecore\Favorites\Favorites::getForUser();
        ?>
        <script type="text/javascript">
          if (window.NodeList && !NodeList.prototype.forEach) {
            NodeList.prototype.forEach = Array.prototype.forEach;
          }

            var setBasketActiveItems = function(container) {
              var catalogItemWrap, catalogItemCountWrap;

              if (!container) {
                container = document;
              }

                <?if(is_array($arItems) && !empty($arItems)):?>
                <?foreach( $arItems["BASKET"] as $key=>$item ){?>

              catalogItemWrap = container.querySelectorAll('[data-add2basket][data-itemId="<?=$key?>"]');
              catalogItemCountWrap = container.querySelectorAll('[data-input-count-input][data-itemId="<?=$key?>"]');

              catalogItemWrap.forEach(function($item) {
                var classListItem = $item.classList;
                if ($item.hasAttribute('data-detail')) {
                    if (classListItem && classListItem.contains('active') === false) {
                      $item.classList.add('active');
                      $item.innerHTML = '<span>В корзине</span>';
                    }
                } else {
                  if (classListItem && classListItem.contains('active') === false) {
                    $item.classList.add( 'active' );
                    $item.innerHTML = 'В корзине';
                  }
                }
              });

              catalogItemCountWrap.forEach(function($item) {
                if (false === $item.hasAttribute('data-input-count-not-val')) {
                    $item.value = <?=$item['QTY']?>;
                }
              });

                <?}?>
                <?endif;?>

                <?if(!empty($arFavorites)):?>
                <?foreach( $arFavorites as $key=>$item ){?>
                      var favorites = container.querySelectorAll('[data-add2favorites][data-itemId="<?=$key?>"]');
                      favorites.forEach(function($item) {
                        var classListItem = $item.classList;
                        if (classListItem && classListItem.contains('active') === false) {
                            $item.classList.add( 'active' );
                        }
                      });
                <?}?>
                <?endif;?>
            };

            setBasketActiveItems();
        </script><?
        Frame::getInstance()->finishDynamicWithID("basketitems-block", "");
    }



    /**
     * @param Sale\BasketBase $basket
     * @param string $currency
     * @param string $sid
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getProductItemsByBasket(Sale\BasketBase $basket, $currency = 'RUB', $sid = '')
    {
        Loader::includeModule('iblock');

        if (!$basket) {
            return [];
        }

        $basketItems = $basket->getBasketItems();
        if (empty($basketItems)) {
            return [];
        }

        /**
         * @var $item \Bitrix\Sale\BasketItem
         */
        $arIds = [];
        $arSortKeys = [];
        $arBasketItemsByProductId = [];
        foreach ($basketItems as $item) {
            $arBasketItemsByProductId[$item->getField('PRODUCT_ID')] = $item;
            $arIds[] = $item->getField('PRODUCT_ID');
            $arSortKeys[] = $item->getField('PRODUCT_ID');
        }

        if (empty($arIds)) {
            return [];
        }

        $core = \Citfact\SiteCore\Core::getInstance();


        /**
         * элементы каталога
         */
        $arProductItems = [];
        $arOrder = array("SORT" => "ASC");
        if (!$sid) {
            $sid = $core::DEFAULT_SITE_ID;
        }

        $SiteList = new \Citfact\DataCache\SiteData\SiteList();
        $arSite = $SiteList->getByCode($sid);

        $arFilter = array(
            'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
            'ID' => $arIds,
            'LID' => $sid,
        );
        $arSelectFields = array(
            'ID',
            'IBLOCK_ID',
            'CODE',
            'NAME',
            'PREVIEW_PICTURE',
            'DETAIL_PAGE_URL',
            'LID',
            'PROPERTY_CML2_ARTICLE',
            'PROPERTY_VES_BRUTTO',
            'PROPERTY_OBEM',
            'PROPERTY_SHIRINA',
            'PROPERTY_DLINA',
            'PROPERTY_VYSOTA',
        );
        $rsElements = \CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
        while ($arTemp = $rsElements->Fetch()) {
            $arTemp['PROPERTIES']['CML2_ARTICLE']['VALUE'] = $arTemp['PROPERTY_CML2_ARTICLE_VALUE'];
            $arTemp['PROPERTIES']['VES_BRUTTO']['VALUE'] = $arTemp['PROPERTY_VES_BRUTTO_VALUE'];
            $arTemp['PROPERTIES']['OBEM']['VALUE'] = $arTemp['PROPERTY_OBEM_VALUE'];
            $arTemp['PROPERTIES']['SHIRINA']['VALUE'] = $arTemp['PROPERTY_SHIRINA_VALUE'];
            $arTemp['PROPERTIES']['DLINA']['VALUE'] = $arTemp['PROPERTY_DLINA_VALUE'];
            $arTemp['PROPERTIES']['VYSOTA']['VALUE'] = $arTemp['PROPERTY_VYSOTA_VALUE'];

            /**
             * в админке запрос для s1 возвращает в результате s2
             * в связи с чем DETAIL_PAGE_URL формируется некорректно
             * поэтому вручную формируем DETAIL_PAGE_URL
             * ТП битрикса ответила что это нормлаьная работа
             */
            $arTemp['LID'] = $arSite['LID'];
            $arTemp['LANG_DIR'] = $arSite['DIR'];
            $arTemp['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl($arTemp['DETAIL_PAGE_URL'], $arTemp, true, 'E');


            /**
             * картинка элемента
             */
//            if ($arTemp['PREVIEW_PICTURE']) {
//                $file = \CFile::ResizeImageGet($arTemp['PREVIEW_PICTURE'], array('width' => 212, 'height' => 212), BX_RESIZE_IMAGE_PROPORTIONAL);
//                $arTemp['PICTURE']['SRC'] = $file['src'];
//
//            } else {
//                $arTemp['PICTURE']['SRC'] = \Citfact\SiteCore\Core::NO_PHOTO_SRC;
//            }


            /**
             * из корзины достаем реальные данные (цены)
             * @var $basketItem \Bitrix\Sale\BasketItem
             */
            $basketItem = $arBasketItemsByProductId[$arTemp['ID']];
            $arTemp['BASKET'] = [
                'PRICE' => $basketItem->getPrice(),
                'CURRENCY' => $basketItem->getCurrency(),
                'QUANTITY' => $basketItem->getQuantity(),
                'FINAL_PRICE' => $basketItem->getFinalPrice(),
            ];

            $key = array_search($arTemp['ID'], $arSortKeys);
            $arProductItems[$key] = $arTemp;
        }
        asort($arProductItems);

        return $arProductItems;
    }
}