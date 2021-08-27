<?php

use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\Product\CatalogProvider;
use Bitrix\Catalog\ProductTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Result;
use Citfact\SiteCore\Portal\UserService\PortalUser;
use Citfact\SiteCore\UserDataManager\UserDataManager;

class CCatalogProductProviderCustom extends CatalogProvider
{
    /**
     * @param array $arParams
     * @return Result
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function GetProductData(array $arParams)
    {
        $userPriceType = UserDataManager::getUserPriceType();

        if (empty($userPriceType)) {
            $basePriceType = [];
            $res =\CCatalogGroup::GetListEx(array(), array('BASE' => 'Y'), false, false, array('*'));
            if($base = $res->Fetch()){
                $basePriceType = $base;
            }
            $userPriceType = $basePriceType;
        }

        $userPriceTypeID = $userPriceType['ID'];
        $userPriceTypeName = $userPriceType['NAME'];
        $products = parent::GetProductData($arParams);
        $data = $products->getData();
        $productIDs = array_keys($data['PRODUCT_DATA_LIST']);

        $dbPrices = PriceTable::getList([
            'select' => ['*'],
            'filter' => ['PRODUCT_ID' => $productIDs, 'CATALOG_GROUP_ID' => $userPriceTypeID]
        ]);

        while ($productPriceRes = $dbPrices->fetch()) {
            $productID = $productPriceRes['PRODUCT_ID'];
            $priceTypeID = $productPriceRes['CATALOG_GROUP_ID'];

            $productPrices[$productID][$priceTypeID] = $productPriceRes;
        }

        if (empty($productPrices)) {
            return $products;
        }

        foreach ($data['PRODUCT_DATA_LIST'] as $productID => &$productData) {
            foreach ($productData['PRICE_LIST'] as $rowID => &$priceData) {

                $priceValue    = $productPrices[$productID][$userPriceTypeID]['PRICE'];
                $priceCurrency = $productPrices[$productID][$userPriceTypeID]['CURRENCY'];

                if ($priceCurrency !== $priceData['CURRENCY']) {
                    $priceValue = CCurrencyRates::ConvertCurrency($priceValue, $priceCurrency, $priceData['CURRENCY']);
                }

                $priceData['PRODUCT_PRICE_ID'] = $productPrices[$productID][$userPriceTypeID]['ID'] ?: 0;
                $priceData['NOTES']            = $userPriceTypeName;
                $priceData['PRICE_TYPE_ID']    = $userPriceTypeID ?: 0;
                $priceData['BASE_PRICE']       = $priceValue ?: 0;
                $priceData['PRICE']            = $priceValue ?: 0;

            }
            unset($priceData);
        }
        unset($productData);

        $products->setData($data);

        return $products;
    }
}