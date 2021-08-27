<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use Citfact\Tools\HLBlock;
use fillup\A2X;

Loc::loadMessages(__FILE__);

class BidExport extends CBitrixComponent
{
    static private $dateFieldCodes = [
        'DATE_REGISTER',
    ];

    static private $jsonFieldCodes = [];

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        $this->arResult['ITEMS'] = $this->getItems();

        $data = [
            'USERS' => [
                'ДатаФормирования' => date('c'),
            ]
        ];
        if (!empty($this->arResult['ITEMS'])){
            $data['USERS']['ELEMENTS'] = $this->arResult['ITEMS'];
        }

        $schema = [
            '/USERS' => [
                'attributes' => [
                    'ДатаФормирования',
                ],
            ],
            '/USERS/ELEMENTS' => [
                'sendItemsAs' => 'USER',
                'includeWrappingTag' => false,
            ],
        ];
        $a2x = new A2X($data, $schema);
        $xml = $a2x->asXml();
        print $xml;
    }

    private function getItems()
    {
        $tableEntity = UserTable::getEntity();
        $query = new \Bitrix\Main\Entity\Query($tableEntity);
        $filter = [
            'LOGIC' => 'OR',
            ['=UF_NEED_EXPORT' => 'Y'],
            ['=UF_NEED_EXPORT' => false],
        ];

        $filter = [
            '=UF_NEED_EXPORT' => 'Y',
            /*[
                'LOGIC' => 'OR',
                ['=UF_NEED_EXPORT' => 'Y'],
                ['=UF_NEED_EXPORT' => false],
            ]*/
        ];

        if (strlen(COption::GetOptionString("sale", "last_export_time_users_committed", "")) > 0) {
            $filter[">TIMESTAMP_X"] = ConvertTimeStamp(COption::GetOptionString("sale", "last_export_time_users_committed", ""), "FULL");
        }
        COption::SetOptionString("sale", "last_export_time_users", time());

        //echo "<pre style=\"display:block;\">"; print_r($filter); echo "</pre>";

        $query
            ->setSelect(array('ID', 'NAME', 'EMAIL', 'PERSONAL_PHONE', 'UF_ADDITIONAL_PHONE', 'DATE_REGISTER'))
            ->setFilter($filter);
        $result = $query->exec();

        $arUsers = array();
        $user = new \CUser;
        while ($row = $result->fetch()) {
            $resultItem = [];
            foreach ($row as $code => $value) {
                $value = $this->formatValue($code, $value);
                $code = preg_replace('/^UF_/', '', $code);
                $resultItem[$code] = $value;
            }

            $arUsers[] = $resultItem;

            /*if ( !$user->Update($row['ID'], array('NAME' => $row['NAME'], 'EMAIL' => $row['EMAIL'], 'PERSONAL_PHONE' => $row['PERSONAL_PHONE'], 'UF_NEED_EXPORT' => 'N')) ){
                echo $user->LAST_ERROR;
            }*/
        }

        return $arUsers;
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
