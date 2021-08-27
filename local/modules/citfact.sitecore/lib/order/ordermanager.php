<?php

namespace Citfact\Sitecore\Order;

use Bitrix\Sale;
use Bitrix\Sale\Order;
use Citfact\Sitecore\UserDataManager;

class OrderManager
{
    public function getOrderStatuses()
    {
        $saleStatus = new \CSaleStatus();
        $result = [];
        $res = $saleStatus->GetList([], ['LID' => LANGUAGE_ID], false, false);
        while ($item = $res->Fetch()) {
            $result[$item['ID']] = $item;
        }
        return $result;
    }

    public function checkOrders($id = null)
    {
        $registry = Sale\Registry::getInstance(Sale\Order::getRegistryType());
        $orderClassName = $registry->getOrderClassName();

        $arContragent = UserDataManager\UserDataManager::getUserContragentXmlID();

        $arFilter = [];
//        $arFilter["STATUS_ID"] = 'N';
//        $arFilter["CANCELED"] = 'N';

        if($id){
            $arFilter['ID'] = $id;
        }

        $getListParams['filter'] = $arFilter;
        $getListParams['runtime'][] =
            new \Bitrix\Main\Entity\ReferenceField('PROPERTY_CONTRAGENT',
                '\Bitrix\Sale\Internals\OrderPropsValueTable',
                array(
                    '=this.ID' => 'ref.ORDER_ID',
                    '=ref.CODE' => new \Bitrix\Main\DB\SqlExpression('?', "CONTRAGENT"),
                    '=ref.VALUE' => new \Bitrix\Main\DB\SqlExpression('?', $arContragent)
                ),
                array(
                    "join_type" => 'inner'
                )
            );

        /** @var Main\DB\Result $countQuery */
        $countQuery = $orderClassName::getList(
            array(
                "filter"=>$getListParams['filter'],
                "runtime"=>$getListParams['runtime'],
                "select"=>array(new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'))
            )
        );

        $totalCount = $countQuery->fetch();
        $totalCount = (int)$totalCount['CNT'];
        unset($countQuery);

        return $totalCount;
    }


    public function getOrdersCount($id = null)
    {
        $registry = Sale\Registry::getInstance(Sale\Order::getRegistryType());
        $orderClassName = $registry->getOrderClassName();

        if(!empty($_REQUEST['XML_ID'])) {
            $arContragent = $_REQUEST['XML_ID'];
        }else{
            $arContragent = UserDataManager\UserDataManager::getUserContragentXmlID();
        }
        if (!$arContragent) {
            return 0;
        }

        $arFilter["!=STATUS_ID"] = 'F';
        $arFilter["CANCELED"] = 'N';

        if($id){
            $arFilter['ID'] = $id;
        }

        $getListParams['filter'] = $arFilter;
        $getListParams['runtime'][] =
            new \Bitrix\Main\Entity\ReferenceField('PROPERTY_CONTRAGENT',
                '\Bitrix\Sale\Internals\OrderPropsValueTable',
                array(
                    '=this.ID' => 'ref.ORDER_ID',
                    '=ref.CODE' => new \Bitrix\Main\DB\SqlExpression('?', "CONTRAGENT"),
                    '=ref.VALUE' => new \Bitrix\Main\DB\SqlExpression('?', $arContragent)
                ),
                array(
                    "join_type" => 'inner'
                )
            );


        /** @var Main\DB\Result $countQuery */
        $countQuery = $orderClassName::getList(
            array(
                "filter"=>$getListParams['filter'],
                "runtime"=>$getListParams['runtime'],
                "select"=>array(new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'))
            )
        );

        $totalCount = $countQuery->fetch();
        $totalCount = (int)$totalCount['CNT'];
        unset($countQuery);

        return $totalCount;
    }

    public function get1CNumber($orderId)
    {
        $order = Order::load($orderId);
        $orderDB = $order->getFields();
        $number_1c = '';
        $propertyCollection = $order->getPropertyCollection();
        $arProperties = $propertyCollection->getArray();
        foreach ($arProperties['properties']  as $property){
            if($property['NAME'] == 'Номер1С'){
                $number_1c =  str_replace('1С-', '',$property['VALUE'][0]);
            }
        }
        return $number_1c;

    }
}