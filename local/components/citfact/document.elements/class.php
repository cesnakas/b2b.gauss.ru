<?
use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock;
use \Bitrix\Iblock\Component\ElementList;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

CBitrixComponent::includeComponentClass("bitrix:catalog.section");
/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global CIntranetToolbar $INTRANET_TOOLBAR
 */

Loc::loadMessages(__FILE__);

if (!\Bitrix\Main\Loader::includeModule('iblock'))
{
	ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
	return;
}

class DocumentElementsComponent extends CatalogSectionComponent
{
    protected function loadDisplayPropertyCodes($iblockId)
    {
        $list = Iblock\Model\PropertyFeature::getListPageShowPropertyCodes(
            $iblockId,
            ['CODE' => 'Y']
        );
        if ($list === null)
            $list = [];
        $this->storage['IBLOCK_PARAMS'][$iblockId]['PROPERTY_CODE'] = [];
        if ($this->useCatalog)
        {
            $list = Iblock\Model\PropertyFeature::getListPageShowPropertyCodes(
                $this->getOffersIblockId($iblockId),
                ['CODE' => 'Y']
            );
            if ($list === null)
                $list = [];
            $this->storage['IBLOCK_PARAMS'][$iblockId]['OFFERS_PROPERTY_CODE'] = $list;
        }

        unset($list);
    }
}