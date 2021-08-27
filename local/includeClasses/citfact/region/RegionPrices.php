<?php

use Bitrix\Sale;

class RegionPrices
{
    public $priceTypes = array();
    public $priceId = 42; // По-умолчанию московская цена
    public $discountLevel = 0;


    public function __construct()
    {
        $this->setPriceTypes();
    }


    private function setPriceTypes()
    {
        //Москва#Люберцы ТН#Розница RZ_0

        $basketSum = $this->getBasketSum();

        $codeRZ = 'RZ_0';
        if ($basketSum > 10000 && $basketSum < 40000){
            $codeRZ = 'RZ_1';
            $this->discountLevel = 1;
        }
        if ($basketSum >= 40000){
            $codeRZ = 'RZ_2';
            $this->discountLevel = 2;
        }

        $defaultPriceType = 'Москва#Люберцы ТН#Розница '.$codeRZ;

        if ($_SESSION['CURRENT_REGION']['NAME'] == '' || $_SESSION['CURRENT_REGION']['NAME'] == 'Москва'){
            $this->priceTypes = array(
                'Москва#Люберцы ТН#Розница '.$codeRZ,
                //'Москва#Люберцы ТН#Розница RZ_1',
                //'Москва#Люберцы ТН#Розница RZ_2',
            );
        }
        elseif ($_SESSION['CURRENT_REGION']['NAME'] == 'Санкт-Петербург'){
            $this->priceTypes = array(
                'Санкт-Петербург#Санкт-Петербург (Нева) ТН#Розница '.$codeRZ,
            );
            $this->priceId = 87;
        }
        elseif ($_SESSION['CURRENT_REGION']['NAME'] == 'Нижний Новгород'){
            $this->priceTypes = array(
                'Нижний Новгород#Нижний Новгород ТН#Розница '.$codeRZ,
            );
            $this->priceId = 59;
        }
        elseif ($_SESSION['CURRENT_REGION']['NAME'] == 'Уфа'){
            $this->priceTypes = array(
                'Уфа#Уфа ТН#Розница '.$codeRZ,
            );
            $this->priceId = 106;
        }
        elseif ($_SESSION['CURRENT_REGION']['NAME'] == 'Пенза'){
            $this->priceTypes = array(
                'Пенза#Пенза ТН#Розница '.$codeRZ,
            );
            $this->priceId = 71;
        }
        else{
            //\Bitrix\Main\Diag\Debug::writeToFile(print_r($_SESSION['CURRENT_REGION'], true), date('Y-m-d H:i:s ')."", "region.log");
            $dbPriceType = \CCatalogGroup::GetList(
                array("SORT" => "ASC"),
                array("%NAME" => $_SESSION['CURRENT_REGION']['NAME'])
            );
            $arPriceTypes = array();
            while ($arPriceType = $dbPriceType->Fetch())
            {
                if (strpos($arPriceType['NAME'], '#я') === false
                    && strpos($arPriceType['NAME'], 'НЕ ИСП') === false
                    && strpos($arPriceType['NAME'], 'Не использовать') === false
                    && strpos($arPriceType['NAME'], $codeRZ) !== false
                ) {
                    $arPriceTypes[] = $arPriceType['NAME'];
                    $this->priceId = $arPriceType['ID'];
                }
            }
            //\Bitrix\Main\Diag\Debug::writeToFile(print_r($arPriceTypes, true), date('Y-m-d H:i:s ')."", "region.log");

            $this->priceTypes = $arPriceTypes;
        }

        if (empty($this->priceTypes)){
            $this->priceTypes = array($defaultPriceType);
        }
    }


    private function getBasketSum(){
        $basketSum = 0;

        $basketRes = Sale\Internals\BasketTable::getList(array(
            'filter' => array(
                '=FUSER_ID' => Sale\Fuser::getId(),
                '=ORDER_ID' => null
            )
        ));

        while ($item = $basketRes->fetch()) {
            if ($item['DELAY'] != 'Y') {
                $basketSum += $item['PRICE'] * $item['QUANTITY'];
            }
        }

        return $basketSum;
    }


    /**
     * Возвращаем значение региональной цены товара
     *
     * @param $productId
     * @return bool
     */
    public function getRegionPriceForProduct($productId){
        $regionPrice = false;

        $dbPriceType = CCatalogGroup::GetList(
            array("SORT" => "ASC"),
            array("NAME" => $this->priceTypes[0])
        );
        if ($arPriceType = $dbPriceType->Fetch())
        {
            $dbProductPrice = CPrice::GetListEx(
                array(),
                array("PRODUCT_ID" => $productId, "CATALOG_GROUP_ID" => $arPriceType['ID']),
                false,
                false,
                array("ID", "PRICE")
            );
            if($arPrice = $dbProductPrice->fetch()){
                $regionPrice = $arPrice['PRICE'];
            }
        }

        return $regionPrice;
    }
}