<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\UserDataManager;
use Bitrix\Highloadblock\HighloadBlockTable;

Loader::includeModule('iblock');
Loc::loadMessages(__FILE__);

class ContragentListComponent extends \CBitrixComponent
{
    /**
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        $contragent = UserDataManager\UserDataManager::getUserContragentXmlID();
        $this->arResult['CURRENT_CONTRAGENT'] = $contragent;

        global $USER;
        global $CACHE_MANAGER;

        Loc::loadMessages(__FILE__);
        if ($this->StartResultCache(86400, $contragent . $USER->GetID())) {

            if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']))
            {
                $CACHE_MANAGER->RegisterTag('user_contragent_list');
            }

            $contragents = $this->getContragents();
            $this->arResult['CONTRAGENTS'] = $contragents;
            $this->IncludeComponentTemplate();
        }

        return $this->arResult['CONTRAGENTS'];
    }

    /**
     * @param $arParams
     * @return array
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getContragents()
    {
        global $USER;
        $uid = $USER->GetID();
        if (!$uid) {
            return [];
        }

        $isRegularUser = UserDataManager\UserDataManager::isRegularUser();

        if ($isRegularUser) {

            $arUser = \Bitrix\Main\UserTable::getlist([
                'filter' => ['ID' => $uid, 'UF_ACTIVATE_PROFILE' => '1', 'ACTIVE' => 'Y'],
                'select' => ['UF_CONTRAGENT_IDS'],
            ])->fetch();
            if (empty($arUser['UF_CONTRAGENT_IDS'])) {
                return [];
            }

            CModule::IncludeModule('highloadblock');
            $core = \Citfact\SiteCore\Core::getInstance();
            $hl_id = $core->getHlBlockId('Kontragenty');
            $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
            $entity = HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            $rsData = $entity_data_class::getList(array(
                'select' => array('*'),
                'filter' => array('ID' => $arUser['UF_CONTRAGENT_IDS']),
            ));

            $contragents = [];
            while ($el = $rsData->fetch()) {
                $contragents[] = $el;
            }

            return $contragents;

        } else {

            return  \Citfact\SiteCore\UserDataManager\UserDataManager::getContragentsList();

        }
    }
}