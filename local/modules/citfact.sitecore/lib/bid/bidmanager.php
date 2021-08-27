<?php

namespace Citfact\SiteCore\Bid;

use Citfact\SiteCore\Core;
use Citfact\Tools\HLBlock;
use Citfact\Tools\UserField\UserFieldEnumRepository;
use Citfact\Tools\ElementManager;

class BidManager
{
    public function getBidStatuses($key = 'ID')
    {
        $core = Core::getInstance();
        $enumRepository = new UserFieldEnumRepository();
        return $enumRepository->getUserFieldEnumByName(
            'UF_STATUS',
            'HLBLOCK_' . $core->getHlBlockId(HLBlock::HL_NAME_BIDS),
            $key
        );
    }

    public function checkByUser($userId, $bidId)
    {
        if ((int)$userId <= 0 || (int)$bidId <= 0) {
            return false;
        }

        $hlBlock = new HLBlock();
        $HL = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);

        $res = $HL::getList(array(
            'select' => array(
                'ID',
            ),
            'filter' => array(
                'UF_USER_ID' => (int)$userId,
                'ID' => (int)$bidId,
            )
        ));
        if ($item = $res->Fetch()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Посылаем письмо о новой заявке
     * @param $result
     */
    public function sendEmailNewBid($result)
    {
        $elementManager = new ElementManager;
        $arEventFields = array(
            'BID_ID' => $result['ID'],
            'USER_ID' => $result['FIELDS']['UF_USER_ID'],
            'USER_NAME' => $result['FIELDS']['UF_USER_NAME'],
            'USER_PHONE' => $elementManager->formatPhone($result['FIELDS']['UF_USER_PHONE']),
            'USER_EMAIL' => $result['FIELDS']['UF_USER_EMAIL'],
            'COMMENT' => $result['FIELDS']['UF_COMMENT'],
            'DELIVERY_TYPE' => ($result['FIELDS']['UF_DELIVERY_TYPE'] == 'delivery' ? 'Доставка' : 'Самовывоз'),
            'DELIVERY_ADDRESS' => $result['FIELDS']['UF_DELIVERY_ADDRESS'],
            'DELIVERY_DATE' => $result['FIELDS']['UF_DELIVERY_DATE'],
            "SALE_EMAIL" => \COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
        );
        \CEvent::Send('NEW_BID', SITE_ID, $arEventFields);
    }
}