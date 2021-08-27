<?php

namespace Citfact\Sitecore\Order;

use Citfact\SiteCore\Core;

class OrderFiles
{
    const FILES_DIR = '/upload/orders/accounts';

    protected static $hlDataClass = false;

    /**
     * @param array $filter
     * @return array
     * @throws \Bitrix\Main\SystemException
     */
    public static function getListFilter($filter = ['*'])
    {
        $files = [];
        $names = [];
        $hlDataClass = self::getEntityClass();
        if ($hlDataClass){
            $res = $hlDataClass::getList(array(
                    'filter' => $filter,
                    'select' => array("*"),
                )
            );
            while ($row = $res->fetch()) {
                switch ($row['UF_TIP']) {
                    case 'БланкЗаказа':
                        $row['FILE_NAME'] = 'Бланк заказа';
                        break;

                    default:
                        $row['FILE_NAME'] = $row['UF_TIP'];
                        break;
                }


                /** проверяем наличие файла */
                if ($row['UF_IMYA']
                    && file_exists($_SERVER['DOCUMENT_ROOT'] . self::FILES_DIR . '/' . $row['UF_IMYA'])
                    && false === in_array($row['UF_IMYA'], $names)) {

                    $row['FILE'] = [
                        'SRC' => self::FILES_DIR . '/' . $row['UF_IMYA']
                    ];

                    $files[] = $row;
                    $names[] = $row['UF_IMYA'];

                }
            }
        }

        return $files;
    }

    /**
     * @return bool|string
     * @throws \Bitrix\Main\SystemException
     */
    public static function getEntityClass()
    {
        if (self::$hlDataClass) {
            return self::$hlDataClass;
        }

        $core = Core::getInstance();
        $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$core->getHlBlockId($core::HLBLOCK_CODE_ORDER_FILES))));
        if ($hldata = $rsData->fetch()) {
            $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            self::$hlDataClass = $hldata['NAME'] . 'Table';
        }

        return self::$hlDataClass;
    }
}