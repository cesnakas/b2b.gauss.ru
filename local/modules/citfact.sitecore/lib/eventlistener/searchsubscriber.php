<?php

namespace Citfact\SiteCore\EventListener;

use CIBlockElement;
use Citfact\SiteCore\Core;
use Citfact\Tools\Event\SubscriberInterface;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class SearchSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array('module' => 'search', 'event' => 'BeforeIndex', 'sort' => 100, 'method' => 'addVendorCodeToTitle'),
        );
    }

    /**
     * Update fields on register and update user
     *
     * @param  array $arFields
     * @return mixed
     */
    public static function addVendorCodeToTitle($arFields)
    {
        $core = Core::getInstance();
        $catalogIblockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

        if ('iblock' == $arFields['MODULE_ID'] && $catalogIblockId == $arFields['PARAM2']) {
            $dbPropItem = CIBlockElement::GetProperty(
                $arFields['PARAM2'],
                $arFields['ITEM_ID'],
                ['sort' => 'asc'],
                ['CODE' => 'CML2_ARTICLE']);
            if($itemProps = $dbPropItem->Fetch()) {
                $arFields["TITLE"] .= ' '.$itemProps['VALUE'];
            }

        }

        return $arFields;
    }
}
