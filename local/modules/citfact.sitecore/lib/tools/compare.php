<?php

namespace Citfact\SiteCore\Tools;

use Bitrix\Main\Page\Frame;
use Citfact\SiteCore\Core;

class Compare
{
    /**
     * @param $itemId
     * @param $IBlockId
     * @return array
     * @throws \Exception
     */
    public static function addToCompare($itemId, $IBlockId=0)
    {
        $iblockElement = new \CIBlockElement();

        if (!$IBlockId) {
            $core = \Citfact\SiteCore\Core::getInstance();
            $IBlockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);
        }

        if (!empty($_SESSION['CATALOG_COMPARE_LIST']) && !empty($_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]) && array_key_exists($itemId, $_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS'])) {
            unset($_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS'][$itemId]);
            $event = 'remove';
        } else {
            $_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS'][$itemId] = $iblockElement->GetByID($itemId)->Fetch();
            $event = 'add';
        }

        $cnt = count($_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS']);
        $cntSections = self::getQuantitySections();
        return [
            'event' => $event,
            'cnt' => $cnt,
            'cntSections' => $cntSections,
        ];
    }

    /**
     * @param $itemId
     * @param int $IBlockId
     * @return array
     * @throws \Exception
     */
    public static function removeByCompare($itemId, $IBlockId=0)
    {
        if (!$IBlockId) {
            $core = \Citfact\SiteCore\Core::getInstance();
            $IBlockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);
        }

        if (!empty($_SESSION['CATALOG_COMPARE_LIST']) && !empty($_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]) && array_key_exists($itemId, $_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS'])) {
            unset($_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS'][$itemId]);
        }
        $event = 'remove';

        $cnt = count($_SESSION['CATALOG_COMPARE_LIST'][$IBlockId]['ITEMS']);
        $cntSections = self::getQuantitySections();
        return [
            'event' => $event,
            'cnt' => $cnt,
            'cntSections' => $cntSections,
        ];
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getQuantity()
    {
        $core = \Citfact\SiteCore\Core::getInstance();
        $IBlockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);
        // поймал баг с пустым элементом.
        // поставил очистку, на всякий случай
        foreach ($_SESSION["CATALOG_COMPARE_LIST"][$IBlockId]["ITEMS"] as $k => $item) {
            if (!$item) {
                unset($_SESSION["CATALOG_COMPARE_LIST"][$IBlockId]["ITEMS"][$k]);
            }
        }
        return (int)count($_SESSION["CATALOG_COMPARE_LIST"][$IBlockId]["ITEMS"]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getQuantitySections()
    {
        $core = \Citfact\SiteCore\Core::getInstance();
        $IBlockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

        $cntSections = [];
        foreach ($_SESSION["CATALOG_COMPARE_LIST"][$IBlockId]["ITEMS"] as $k => $item) {
            if ($item) {
                $cntSections[$item['IBLOCK_SECTION_ID']] = (int)$cntSections[$item['IBLOCK_SECTION_ID']] + 1;

            } else {
                // поймал баг с пустым элементом.
                // поставил очистку, на всякий случай
                unset($_SESSION["CATALOG_COMPARE_LIST"][$IBlockId]["ITEMS"][$k]);
            }
        }


        return $cntSections;
    }

    public static function getComparedItems()
    {
        $core = Core::getInstance();
        return $_SESSION['CATALOG_COMPARE_LIST'][$core->getIblockId($core::IBLOCK_CODE_CATALOG)]['ITEMS'];
    }


    /**
     * Очистка списка сравнения пользователя
     */
    public static function clearComparedItems()
    {
        $core = Core::getInstance();
        $_SESSION['CATALOG_COMPARE_LIST'][$core->getIblockId($core::IBLOCK_CODE_CATALOG)]['ITEMS'] = [];
    }


    public static function setComparedItemsOnLoad()
    {
        Frame::getInstance()->startDynamicWithID("compareditems-block");
        $arItems = static::getComparedItems();
        ?>
        <script type="text/javascript">
            document.addEventListener('AppLib.Ready', function (e) {
                <?foreach( $arItems as $key=>$item ){?>
                $('[data-add2compare][data-itemId=<?=$key?>]').addClass('active');
                <?}?>
            });
        </script><?
        Frame::getInstance()->finishDynamicWithID("compareditems-block", "");
    }

}