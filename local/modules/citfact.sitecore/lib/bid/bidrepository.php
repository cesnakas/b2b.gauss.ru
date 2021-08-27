<?php

namespace Citfact\SiteCore\Bid;

use Bitrix\Main\UserTable;
use Citfact\Sitecore\Order\Basket;
use Citfact\Tools\HLBlock;

class BidRepository
{
    const STATUS_CODE_CANCELED = 'canceled';
    const STATUS_CODE_FORMED = 'formed';
    const STATUS_CODE_IN_PROCESS = 'in_process';
    const STATUS_CODE_PROCESSED = 'processed';

    public function add($userId, $deliveryType = '', $deliveryAddress = '', $deliveryDate = '', $comment = '')
    {
        // Для доставки проверяем адрес и дату
        if ($deliveryType == 'delivery') {
            if ($deliveryType == '') {
                throw new \Exception('Не заполнен тип доставки');
            }
            if ($deliveryType != '' && ($deliveryAddress == '' || $deliveryType == '')) {
                throw new \Exception('Не заполнен адрес или дата доставки');
            }
        }

        $bidManager = new BidManager();
        $bidStatuses = $bidManager->getBidStatuses('XML_ID');

        global $USER;
        $tableEntity = UserTable::getEntity();
        $query = new \Bitrix\Main\Entity\Query($tableEntity);
        $query
            ->setSelect(array('ID', 'NAME', 'EMAIL', 'PERSONAL_PHONE'))
            ->setFilter(array("ID" => $USER->GetID()));
        $result = $query->exec();

        $arUserData = array();
        if ($row = $result->fetch()) {
            $arUserData = $row;
        }

        $hlBlock = new HLBlock();
        $HL = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);

        $dateTimeOrder = new \DateTime();
        $dateTimeDelivery = new \DateTime(htmlspecialcharsbx(urldecode($deliveryDate)));

        $basketHelper = new Basket();
        $arBasketItems = $basketHelper->getBasketItems();
        $arItems = $arBasketItems['BASKET'];

        $arElementFields = array(
            'UF_DATETIME' => $dateTimeOrder->format('d.m.Y H:i:s'),
            'UF_DATE_UPDATED' => $dateTimeOrder->format('d.m.Y H:i:s'),
            'UF_USER_ID' => $USER->GetID(),
            'UF_DELIVERY_TYPE' => htmlspecialcharsbx(urldecode($deliveryType)),
            'UF_DELIVERY_ADDRESS' => htmlspecialcharsbx(urldecode($deliveryAddress)),
            'UF_DELIVERY_DATE' => $dateTimeDelivery->format('d.m.Y'),
            'UF_ITEMS' => json_encode($arItems),
            'UF_COMMENT' => $comment,
            'UF_USER_NAME' => $arUserData['NAME'],
            'UF_USER_PHONE' => $arUserData['PERSONAL_PHONE'],
            'UF_USER_EMAIL' => $arUserData['EMAIL'],
            'UF_NEED_EXPORT' => 1,
            'UF_STATUS' => $bidStatuses[BidRepository::STATUS_CODE_FORMED],
        );

        $obResult = $HL::add($arElementFields);
        $bidId = $obResult->getID();
        $bSuccess = $obResult->isSuccess();

        if (!$bSuccess) {
            return false;
        } else {
            $basketHelper->clearBasketItems();
            return array('ID' => $bidId, 'FIELDS' => $arElementFields);
        }
    }
}