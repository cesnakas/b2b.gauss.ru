<?php

use Citfact\Sitecore\CatalogHelper\ItemAvailability;

CModule::IncludeModule("iblock");

class SaleBasketActions
{
    public function __construct(){

    }

    public static function addToBasket($quantity, $id, $article)
    {
        if ($quantity) {
            $quantity = floatval($quantity);
        }

        global $APPLICATION;

        $dbBasketItems = CSaleBasket::GetList(
            array("NAME" => "ASC", "ID" => "ASC"),
            array("PRODUCT_ID" => $id, "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
            false, false, array("ID", "DELAY")
        )->Fetch();
        if (!empty($dbBasketItems) && $dbBasketItems["DELAY"] == "Y") {
            $arFields = array("DELAY" => "N", "SUBSCRIBE" => "N");
            if ($quantity) {
                $arFields['QUANTITY'] = $quantity;
            }
            CSaleBasket::Update($dbBasketItems["ID"], $arFields);
            $addResult = array('STATUS' => 'OK', 'MESSAGE' => 'CATALOG_SUCCESSFUL_UPDATED_TO_BASKET', 'ARTICLE' => $article,"PRODUCT_ID" =>$arFields["PRODUCT_ID"]);
        } else {
            $product_properties = $arSkuProp = array();
            $successfulAdd = true;
            $strErrorExt = '';

            $cItemAvailability = new ItemAvailability();
            $flag = $cItemAvailability->getStatusByElementId($id);
            if ($successfulAdd && $flag) {
                if (!Add2BasketByProductID($id, $quantity, $arRewriteFields=['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom'], $product_properties=[])) {
                    if ($ex = $APPLICATION->GetException())
                        $strErrorExt = $ex->GetString();

                    $strError = "ERROR_ADD2BASKET";
                    $successfulAdd = false;
                }
            }
            if ($successfulAdd && $flag) {
                $addResult = array('STATUS' => 'OK', 'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET', 'MESSAGE_EXT' => $strErrorExt, 'ARTICLE' => $article, 'QUANTITY' => $quantity, 'ID'=> $id);
            } else {
                $addResult = array('STATUS' => 'ERROR', 'MESSAGE' => $strError, 'MESSAGE_EXT' => $strErrorExt, 'ARTICLE' => $article, 'QUANTITY' => $quantity);
            }

        }
        return $addResult;
    }
}