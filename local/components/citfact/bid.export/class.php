<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Citfact\Tools\HLBlock;
use fillup\A2X;

Loc::loadMessages(__FILE__);

class BidExport extends CBitrixComponent
{
    static private $dateFieldCodes = [
        'UF_DATETIME',
        'UF_DELIVERY_DATE',
    ];

    static private $jsonFieldCodes = [
        'UF_ITEMS',
    ];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $this->arResult['ITEMS'] = $this->getItems();

        $data = [
            'BIDS' => [
                'ДатаФормирования' => date('c'),
            ]
        ];
        if (!empty($this->arResult['ITEMS'])){
            $data['BIDS']['ELEMENTS'] = $this->arResult['ITEMS'];
        }

        $schema = [
            '/BIDS' => [
                'attributes' => [
                    'ДатаФормирования',
                ],
            ],
            '/BIDS/ELEMENTS' => [
                'sendItemsAs' => 'BID',
                'includeWrappingTag' => false,
            ],
            '/BIDS/ELEMENTS/BID/ITEMS' => [
                'sendItemsAs' => 'ITEM',
            ],
        ];
        $a2x = new A2X($data, $schema);
        $xml = $a2x->asXml();
        print $xml;
    }

    private function getItems()
    {
        global $USER;
        $hlBlock = new HLBlock();
        $bidsEntity = $hlBlock->getHlEntityByName($hlBlock::HL_NAME_BIDS);
        $result = [];

        // Выгружаем заявки, помеченные к экспорту, плюс все со статусом "Сформирована"
        $filter = [
            //'UF_USER_ID' => $USER->GetID(),
            'UF_NEED_EXPORT' => 1,
            /*[
                'LOGIC' => 'OR',
                ['UF_NEED_EXPORT' => 1],
                ['UF_STATUS' => 1],
            ]*/
        ];

        if (strlen(COption::GetOptionString("sale", "last_export_time_bids_committed", "")) > 0) {
            $filter[">UF_DATE_UPDATED"] = ConvertTimeStamp(COption::GetOptionString("sale", "last_export_time_bids_committed", ""), "FULL");
        }
        COption::SetOptionString("sale", "last_export_time_bids", time());

        $res = $bidsEntity::getList([
            'order' => ['ID' => 'DESC'],
            'filter' => $filter,
        ]);
        while ($item = $res->fetch()) {
            unset($item['UF_NEED_EXPORT']);
            $resultItem = [];
            foreach ($item as $code => $value) {
                $value = $this->formatValue($code, $value);
                $code = preg_replace('/^UF_/', '', $code);
                $resultItem[$code] = $value;
            }
            $result[$resultItem['ID']] = $resultItem;
            //$bidsEntity::update($item['ID'], ['UF_NEED_EXPORT' => 0]);
        }

        return $result;
    }

    private function formatValue($code, $value)
    {
        if (in_array($code, $this::$dateFieldCodes) && $value) {
            $value = $value->format('d.m.Y H:i:s');
        }
        if (in_array($code, $this::$jsonFieldCodes) && $value) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
