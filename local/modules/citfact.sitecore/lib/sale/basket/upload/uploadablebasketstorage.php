<?php
namespace Citfact\Sitecore\Sale\Basket\Upload;


class UploadableBasketStorage
{
    const MODE_CATALOG = 'catalog';
    const MODE_EXPORT = 'export';
    const UPLOAD_BASKET = 'UPLOAD_BASKET';

    public static function saveBasket(array $data)
    {
        return $_SESSION[self::UPLOAD_BASKET]['basket'] = $data;
    }

    public static function saveMode($mode)
    {
        return $_SESSION[self::UPLOAD_BASKET]['mode'] = $mode;
    }

    public static function selectItem($itemId)
    {
        $_SESSION[self::UPLOAD_BASKET]['selectedItem'] = $itemId;
        $_SESSION[self::UPLOAD_BASKET]['mode'] = self::MODE_EXPORT;
    }

    public static function getSelectedItem()
    {
        return $_SESSION[self::UPLOAD_BASKET]['selectedItem'];
    }

    public static function resetSelectedItem()
    {
        $_SESSION[self::UPLOAD_BASKET]['selectedItem'] = null;
    }

    public static function loadBasket()
    {
        if (!isset($_SESSION[self::UPLOAD_BASKET])){
            $_SESSION[self::UPLOAD_BASKET] = self::blankData();
            return [];
        } else {
            return $_SESSION[self::UPLOAD_BASKET]['basket'];
        }

    }

    public static function blankData()
    {
        return  [
            'mode' => self::MODE_CATALOG,
            'basket' => [],
            'selectedItem' => ''
        ];
    }

    public static function loadData()
    {
        return $_SESSION[self::UPLOAD_BASKET]?: self::blankData();
    }

    public static function clearData()
    {
        unset($_SESSION[self::UPLOAD_BASKET]);
    }

    public static function isExport()
    {
        $data = self::loadData();
        return ($data['mode'] == self::MODE_EXPORT);
    }
}