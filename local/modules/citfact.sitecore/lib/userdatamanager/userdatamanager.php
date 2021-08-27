<?php

namespace Citfact\SiteCore\UserDataManager;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\SiteCore\Core;
use Citfact\DataCache\PriceData\PriceId;
use Exception;


class UserDataManager
{
    protected static $arContragent = [];
    protected static $priceType = [];
    protected static $stackManager = [];


    /**
     * @param $contragentXmlId
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function setContrAgent($contragentXmlId)
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) {
            self::$arContragent = [];
        }

        $oUser = new \CUser;
        $contragentId = self::getContagentIdByXmlId($contragentXmlId);
        $aFields = array(
            'UF_CONTRAGENT_ID' => $contragentId
        );

        $_SESSION['contragent'] = $contragentId;
        $oUser->Update($userID, $aFields);

        $basket = Basket::loadItemsForFUser(
            Fuser::getId(),
            Context::getCurrent()->getSite()
        );
        $basket->refreshData();
        $basket->save();

        $_SESSION['SALE_USER_BASKET_PRICE'][Context::getCurrent()->getSite()][Fuser::getId()] = $basket->getPrice();
    }

    /**
     * @param string $contragentXml
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getContrAgentInfo($contragentXml = '')
    {
        $arContragent = [];

        if (!empty(self::$arContragent[$contragentXml])) {
            return self::$arContragent[$contragentXml];
        }

        if ($contragentXml) {
            $userContragent = $contragentXml;
        } else {
            $userContragent = self::getUserContragentXmlID();
        }

        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_XML_ID' => $userContragent],
        ));
        if ($el = $rsData->fetch()) {
            $arContragent = $el;
        }

        if ($arContragent['UF_TELEFON']) {
            $arContragent['UF_TELEFON'] = explode(',', $arContragent['UF_TELEFON']);
        }

        if ($arContragent['UF_REGION']) {
            $arContragent['UF_REGION'] = self::getContragentRegionByXmlId($arContragent['UF_REGION']);
        }

        self::$arContragent[$userContragent] = $arContragent;
        return self::$arContragent[$userContragent];
    }

    /**
     * @param $userId
     */
    public static function setDefaultContragentUser($userId)
    {
        $arFilter = array("ID" => $userId);
        $arParams["SELECT"] = array("UF_CONTRAGENT_ID", "UF_CONTRAGENT_IDS");
        $arRes = \CUser::GetList($by = "", $order = "", $arFilter, $arParams);
        if ($res = $arRes->Fetch()) {
            if (empty($res['UF_CONTRAGENT_ID']) && !empty($res['UF_CONTRAGENT_IDS'])) {
                $user = new \CUser;
                $user->Update($userId, ["UF_CONTRAGENT_ID" => $res['UF_CONTRAGENT_IDS'][0]]);
            }
        }
    }

    /**
     * @param $userId
     */
    public static function setDefaultContragentManager($userId)
    {

        $contragent = UserDataManager::getContragentsList()[0];

        $arFilter = array("ID" => $userId);
        $arParams["SELECT"] = array("UF_CONTRAGENT_ID", "UF_CONTRAGENT_IDS");
        $arRes = \CUser::GetList($by = "", $order = "", $arFilter, $arParams);
        if ($res = $arRes->Fetch()) {
            if (empty($res['UF_CONTRAGENT_ID']) && !empty($contragent)) {
                $user = new \CUser;
                $user->Update($userId, ["UF_CONTRAGENT_ID" => $contragent['ID']]);
            }
        }
    }

    /**
     * @return mixed|string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getUserContragentXmlID()
    {
        global $USER;
        if ($USER->IsAuthorized() == false) {
            $_SESSION['contragent'] = '';
            return '';
        }

        $contragentId = '';
        $arFilter = array("ID" => $USER->GetID());
        $arParams["SELECT"] = array("UF_CONTRAGENT_ID");
        $arRes = \CUser::GetList($by = "", $order = "", $arFilter, $arParams);
        if ($res = $arRes->Fetch()) {
            $contragentId = $res['UF_CONTRAGENT_ID'];
        }

        if (!empty($contragentId)) {
            $contragentXmlId = self::getContagentXmlIdById($contragentId);
        } else {
            $contragentXmlId = "";
        }

        $_SESSION['contragent'] = $contragentXmlId;
        return $contragentXmlId;
    }

    /**
     * @param $contagentId
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getContagentXmlIdById($contagentId)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('UF_XML_ID'),
            'filter' => ['ID' => $contagentId],
        ));
        $row = $rsData->fetch();
        return $row['UF_XML_ID'];
    }


    /**
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getUserPriceType()
    {
        if (!empty(self::$priceType)) {
            $_SESSION["price"] = self::$priceType['ID'];
            return self::$priceType;
        }

        $contragent = self::getUserContragentXmlID();
        if (empty($contragent)) {
            $_SESSION["price"] = '';
            return [];
        }

        $priceTypesByContragent = UserDataManager::getPriceTypesByContragent($contragent);
        if (empty($priceTypesByContragent)) {
            $_SESSION["price"] = '';
            return [];
        }

        self::$priceType = UserDataManager::getShopPriceType($priceTypesByContragent);
        $_SESSION["price"] = self::$priceType['ID'];
        return self::$priceType;
    }

    /**
     * @param $contragent
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function getPriceTypesByContragent($contragent)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_TIPY_TSEN_KONTRAGENTOV);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_KONTRAGENT' => $contragent],
        ));
        $priceTypes = [];
        while ($el = $rsData->fetch()) {
            $priceTypes[] = $el['UF_TIPTSEN'];
        }
        return $priceTypes;
    }

    /**
     * @param $userPriceTypes
     * @return mixed
     * @throws \Exception
     */
    private static function getShopPriceType($userPriceTypes)
    {
        $PriceId = new PriceId();
        $allShopPriceTypes = $PriceId->getAllData();
        foreach ($allShopPriceTypes as $shopPriceType) {
            foreach ($userPriceTypes as $userPriceType) {
                if ($shopPriceType['XML_ID'] == $userPriceType) {
                    return $shopPriceType;
                }
            }
        }
    }

    /**
     * @param array $filter
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getContragentXmlIdListOfUser($filter = [])
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return [];
        }


        $rsUser = \CUser::GetByID($USER->GetId());
        $arUser = $rsUser->Fetch();
        $contragentIdList = $arUser['UF_CONTRAGENT_IDS'];

        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $arFilter = ['ID' => $contragentIdList];
        $arFilter = array_merge($arFilter, $filter);
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => $arFilter,
        ));

        $contragentList = [];
        while ($el = $rsData->fetch()) {
            $contragentList[] = $el;
        }

        return $contragentList;
    }

    public static function getUserManagerStructureXmlId($managerId = null)
    {
        if (is_null($managerId)) {
            global $USER;
            if (!$USER->IsAuthorized()) {
                return null;
            }
            $rsUser = \CUser::GetByID($USER->GetId());
            $arUser = $rsUser->Fetch();
            if (empty($arUser['UF_MANAGER_ID'])) {
                return null;
            }
            $managerId = $arUser['UF_MANAGER_ID'];
        }

        $core = Core::getInstance();
        $allSubManagersId = static::getAllowIdManagers($managerId);
        $managersXml = [];
        if ($allSubManagersId) {
            $hlBlockCode = $core::HLBLOCK_CODE_MANAGERS;
            $hl_id = $core->getHlBlockId($hlBlockCode);
            $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
            $entity = HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            $rsData = $entity_data_class::getList([
                'select' => ['UF_XML_ID'],
                'filter' => ['ID' => $allSubManagersId],
            ]);

            $managers = $rsData->FetchAll();
            $managersXml = array_column($managers, 'UF_XML_ID');
        }

        return $managersXml;
    }

    /**
     * @return |null
     * @throws Exception
     */
    public static function getUserManagerXmlId()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return null;
        }

        $rsUser = \CUser::GetByID($USER->GetId());
        $arUser = $rsUser->Fetch();

        $core = Core::getInstance();

        if (empty($arUser['UF_MANAGER_ID'])) {
            return null;
        }

        $hlBlockCode = $core::HLBLOCK_CODE_MANAGERS;

        $hl_id = $core->getHlBlockId($hlBlockCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList([
            'select' => ['UF_XML_ID'],
            'filter' => ['ID' => $arUser['UF_MANAGER_ID']],
        ]);

        return $rsData->fetch()['UF_XML_ID'];
    }

    public static function getIdManagerByXml($idXml)
    {
        $core = Core::getInstance();
        $hlBlockCode = $core::HLBLOCK_CODE_MANAGERS;

        $hl_id = $core->getHlBlockId($hlBlockCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList([
            'select' => ['ID'],
            'filter' => ['UF_XML_ID' => $idXml],
        ]);

        return $rsData->fetch()['ID'];
    }

    /**
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getUserAssistantXmlId()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return null;
        }

        $rsUser = \CUser::GetByID($USER->GetId());
        $arUser = $rsUser->Fetch();

        $core = Core::getInstance();

        if (empty($arUser['UF_ASSISTANT_ID'])) {
            return null;
        }

        $hlBlockCode = $core::HLBLOCK_CODE_ASSISTANTS;

        $hl_id = $core->getHlBlockId($hlBlockCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsUf_id = $entity_data_class::getList([
            'select' => ['UF_ID'],
            'filter' => ['ID' => $arUser['UF_ASSISTANT_ID']],
        ]);
        $arUf_id[] = $rsUf_id->fetch()['UF_ID'];

        $rsData = $entity_data_class::getList([
            'select' => ['UF_MENEDZHER'],
            'filter' => ['UF_ID' => $arUf_id],
        ]);

        $menedzherList = [];

        while ($menedzher = $rsData->fetch()) {
            $menedzherList[] = $menedzher['UF_MENEDZHER'];
        }
        return $menedzherList;
    }

    public static function isRegularUser()
    {
        global $USER;
        $currentUserGroups = $USER->GetUserGroupArray();

        $core = Core::getInstance();

        $isUserManager = !empty(array_intersect($currentUserGroups, $core->GetGroupByCode($core::USER_GROUP_MANAGER)));
        $isUserAssistant = !empty(array_intersect($currentUserGroups, $core->GetGroupByCode($core::USER_GROUP_ASSISTANT)));

        return !$isUserManager && !$isUserAssistant;
    }


    /**
     * @param array $filter
     * @param bool $takeSubContragents
     * @return array
     * @throws Exception
     */
    public static function getContragentsList($filter = [], $takeSubContragents = false)
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return [];
        }

        $currentUserGroups = $USER->GetUserGroupArray();

        $core = Core::getInstance();

        $isUserManager = !empty(array_intersect($currentUserGroups, $core->GetGroupByCode($core::USER_GROUP_MANAGER)));
        $isUserAssistant = !empty(array_intersect($currentUserGroups, $core->GetGroupByCode($core::USER_GROUP_ASSISTANT)));

        switch (true) {
            case $isUserManager:
                if($takeSubContragents) {
                    $managerXmlId = self::getUserManagerStructureXmlId();
                } else {
                    $managerXmlId = self::getUserManagerXmlId();
                }
                break;
            case $isUserAssistant:
                $managerXmlId = self::getUserAssistantXmlId();
                break;
            default:
                return [];
        }

        if (empty($managerXmlId)) {
            return [];
        }

        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();


        $arFilter = ['UF_MENEDZHER' => $managerXmlId]; //'UF_OSNOVNOYKONTRAGEN' => false, убрала фильтр,т.к. выгружались не все контрагенты
        $arFilter = array_merge($arFilter, $filter);
        $contragentList = [];

        $rsData = $entity_data_class::getList([
            'select' => ['*'],
            'filter' => $arFilter,
        ]);
        while ($el = $rsData->fetch()) {
            $contragentList[] = $el;
        }

        return $contragentList;
    }

    /**
     * @param string $contragentParam
     */

    //Метод проверки ссылки контрагента

    public static function checkContragentXmlId($contragentParam) {
        $contragents = self::getContragentsList();
        global $APPLICATION;
        $xmlIdAr = array_column($contragents,"UF_XML_ID");

        if(!in_array($contragentParam, $xmlIdAr)){
            if (!defined("ERROR_404"))  define("ERROR_404", "Y");
            \CHTTP::setStatus("404 Not Found");

            if ($APPLICATION->RestartWorkarea())
            {
                require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
                die();
            }
        }
    }

    //метод получает абсолютно всех контрагентов привязанных менеджеров

    public static function getAllContragentsofUser()
    {
        global $USER;
        $managerId = static::getIdManagerByUserId($USER->GetID());
        $allManagers = static::getAllowIdManagers($managerId);
        $managerXMLs = static::getManagersXmlIds($allManagers);
        $allContragentsofUser = self::getKontragents($managerXMLs);
        foreach ($allContragentsofUser as $contragent) {
            $contragents[] = $contragent['UF_XML_ID'];
        }
        return $contragents;
    }

    public static function checkContragentXmlIdByStructure($contragentParam)
    {
        global $APPLICATION;
        $contragents = static::getAllContragentsofUser();
        if (!in_array($contragentParam, $contragents)) {
            if (!defined("ERROR_404")) define("ERROR_404", "Y");
            \CHTTP::setStatus("404 Not Found");

            if ($APPLICATION->RestartWorkarea()) {
                require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
                die();
            }
        }
    }

    public static function getAllowIdManagers($managerId)
    {
        $hlBlock = new HLBlock();
        $hlClassName = $hlBlock->getHlEntityByName(Core::HLBLOCK_CODE_STRUCTURE_MANAGERS);

        $recursion = function ($managerId, $hlClassName, &$ids = []) use (&$recursion) {
            $dbResult = $hlClassName::getList([
                'filter' => ['UF_ID_MANAGER' => $managerId],
                'select' => ['UF_ID_MANAGER', 'UF_ID_SUB_MANAGER']
            ]);
            while ($arData = $dbResult->Fetch()) {
                $ids[$arData['UF_ID_SUB_MANAGER']] = $arData['UF_ID_SUB_MANAGER'];
                $recursion($arData['UF_ID_SUB_MANAGER'], $hlClassName, $ids);
            }
            return $ids;
        };
        $result = array_values($recursion($managerId, $hlClassName));
        $result[] = $managerId;
        return array_unique($result);
    }

    /**
     * @param $contragentXmlId
     * @return array
     */
    public static function getUsersContragentByXmlId($contragentXmlId)
    {
        $arUsers = [];
        if (!$contragentXmlId) {
            return $arUsers;
        }

        $contragentId = self::getContagentIdByXmlId($contragentXmlId);

        if (!$contragentId) {
            return $arUsers;
        }

        $res = \CUser::GetList($by = '', $order = [], $filter = [
            'UF_CONTRAGENT_IDS' => $contragentId,
            'ACTIVE' => 'Y',
            'UF_ACTIVATE_PROFILE' => '1',
        ], $params = [
            'FIELDS' => ['ID', 'EMAIL'],
            'SELECT' => ['UF_CONTRAGENT_IDS', 'UF_ACTIVATE_PROFILE'],
        ]);
        while ($arRes = $res->Fetch()) {
            $arUsers[] = $arRes;
        }

        return $arUsers;
    }


    /**
     * @param $email
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function isUserContragentByEmail($email)
    {
        $arUser = UserTable::getList([
            'filter' => ['EMAIL' => $email],
            'select' => ['XML_ID'],
        ])->fetch();
        if (empty($arUser)) {
            return true;
        }

        if (empty($arUser['XML_ID'])) {
            return false;
        }


        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $arContragent = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_XML_ID' => $arUser['XML_ID']],
        ))->fetch();

        if (!empty($arContragent)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $xmlid
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function isUserContragentByXmlId($xmlid)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $arContragent = $entity_data_class::getList(array(
            'select' => array('*'),
            'filter' => ['UF_XML_ID' => $xmlid],
        ))->fetch();

        if (!empty($arContragent)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $contagentXmlId
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getContagentIdByXmlId($contagentXmlId)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('ID'),
            'filter' => ['UF_XML_ID' => $contagentXmlId],
        ));
        $row = $rsData->fetch();
        return $row['ID'];
    }

    /**
     * @param $regionXmlId
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getContragentRegionByXmlId($regionXmlId)
    {
        $core = Core::getInstance();
        $hl_id = $core->getHlBlockId($core::HLBLOCK_CODE_ORDER_REGIONS);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            'select' => array('ID', 'UF_NAME'),
            'filter' => ['UF_XML_ID' => $regionXmlId],
        ));

        $row = $rsData->fetch();

        return $row['UF_NAME'];

    }


    public static function clearUserPriceType()
    {
        unset($_SESSION['price']);
    }

    public static function clearContrAgent()
    {
        unset($_SESSION['contragent']);
    }

    public static function getIdXmlContragentsCurrentUser()
    {
        return array_column(static::getContragentsCurrentUser(), 'UF_XML_ID');
    }

    public static function getContragentsCurrentUser()
    {
        global $USER;
        $uid = $USER->GetID();
        if (!$uid) {
            return [];
        }

        $isRegularUser = UserDataManager::isRegularUser();

        if ($isRegularUser) {

            $arUser = \Bitrix\Main\UserTable::getlist([
                'filter' => ['ID' => $uid, 'UF_ACTIVATE_PROFILE' => '1', 'ACTIVE' => 'Y'],
                'select' => ['UF_CONTRAGENT_IDS'],
            ])->fetch();
            if (empty($arUser['UF_CONTRAGENT_IDS'])) {
                return [];
            }

            \CModule::IncludeModule('highloadblock');
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
            return \Citfact\SiteCore\UserDataManager\UserDataManager::getContragentsList();
        }
    }

    /**
     * Получает XML_ID менеджера
     *
     * @param $managerId
     * @return string
     *
     * @throws Exception если привязка к менеджеру у пользователя не будет найдена
     */
    public static function getManagerXmlId($managerId) {
        if ($managerId) {
            $hlblock = HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'Menedzhery']
            ])->fetch();
            if ($hlblock) {
                $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
                $dbResult = $hlClassName::getList([
                    'filter' => ['ID' => $managerId]
                ]);
                if ($arData = $dbResult->Fetch()) {
                    return $arData['UF_XML_ID'];
                }
            } else {
                throw new Exception('HighloadBlock \'Menedzhery\' not found');
            }
        } else {
            throw new Exception('Manager not found');
        }
    }

    /**
     * Получает XML_ID менеджеров
     *
     * @param array $managerIds
     * @return array
     *
     * @throws Exception если привязка к менеджеру у пользователя не будет найдена
     */
    public static function getManagersXmlIds($managerIds) {
        if (!empty($managerIds)) {
            $hlblock = HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'Menedzhery']
            ])->fetch();
            if ($hlblock) {
                $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
                $dbResult = $hlClassName::getList([
                    'filter' => ['ID' => $managerIds]
                ]);
                $xmlIds = [];
                while ($arData = $dbResult->Fetch()) {
                    $xmlIds[] = $arData['UF_XML_ID'];
                }
                return $xmlIds;
            } else {
                throw new Exception('HighloadBlock \'Menedzhery\' not found');
            }
        } else {
            throw new Exception('Manager not found');
        }
    }

    /**
     * Получает контрагентов, привязанных к менеджеру
     *
     * @param array $managerXmlId - XML_ID менеджера
     * @param array $additionalFilter - дополнительный фильтр
     * @param array $select
     *
     * @return array
     *
     * @throws Exception если highloadBlock Контрагенты не будет найден
     */
    public static function getKontragents(array $managerXmlId, array $additionalFilter = [], array $select = ["*"]) {
        $kontragentsList = [];
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'Kontragenty']
        ])->fetch();

        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();

            $arFilter = ['UF_MENEDZHER' => $managerXmlId];
            $arFilter = array_merge($arFilter, $additionalFilter);

            $dbResult = $hlClassName::getList([
                'filter' => $arFilter,
                'select' => $select
            ]);
            while ($arData = $dbResult->Fetch()) {
                $arData['USE_PORTAL'] = self::checkUsePortal($arData['ID']);
                $kontragentsList[] = $arData;
            }
            return $kontragentsList;
        } else {
            throw new Exception('HighloadBlock \'Kontragenty\' not found');
        }
    }

    /**
     * Использует ли контрагент портал
     *
     * @param int $id - id элемента в HL блоке 'Контрагенты'
     *
     * @return boolean
     */
    public static function checkUsePortal($id) {
        $result = \Bitrix\Main\UserTable::getList([
            'select' => ['ID'],
            'limit' => 1,
            'filter' => ['UF_CONTRAGENT_IDS' => $id, 'ACTIVE' => 'Y']
        ]);

        if ($arUser = $result->Fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Получает ID менеджера
     *
     * @param $idUser
     *
     * @return false|mixed
     */
    public static function getIdManagerByUserId($idUser) {
        $rsUser = \CUser::GetByID($idUser);
        $arUser = $rsUser->Fetch();
        if ($arUser['UF_MANAGER_ID']) {
            return $arUser['UF_MANAGER_ID'];
        }
        return false;
    }

    /**
     * Структура подчиненности
     * @param $managerId
     *
     * @return array
     * @throws Exception
     */
    public static function getStructureManagers($managerId) {
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => Core::HLBLOCK_CODE_STRUCTURE_MANAGERS]
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            return self::recursionStructureManagers($managerId, $hlClassName);
        } else {
            throw new Exception('HighloadBlock \'StructureManagers\' not found');
        }
    }

    private static function recursionStructureManagers($managerId, $hlClassName, $stackBoss = []) {
        $dbResult = $hlClassName::getList([
            'filter' => ['UF_ID_MANAGER' => $managerId],
            'select' => ['UF_ID_MANAGER', 'UF_ID_SUB_MANAGER']
        ]);

        while ($arData = $dbResult->Fetch()) {
            $arData['stackBoss'] = $stackBoss;
            array_unshift($arData['stackBoss'], $managerId);
            static::$stackManager[$arData['UF_ID_SUB_MANAGER']] = $arData['stackBoss'];
            $arData['sub'] = self::recursionStructureManagers($arData['UF_ID_SUB_MANAGER'], $hlClassName, $arData['stackBoss']);
            $result[] = $arData;
        }

        return isset($result) ? $result : [];
    }

    /**
     * Информация о подчиненных менеджерах
     * @param $managerId
     * @param bool $onlyId - возвращает только id
     * @param array $structureManagers
     *
     * @return array
     * @throws Exception
     */
    public static function getStructureManagersInfo($managerId, $onlyId = false, $structureManagers = []) {
        if (empty($structureManagers)) {
            $structureManagers = UserDataManager::getStructureManagers($managerId);
        }

        $managerIds = self::recursion($structureManagers);
        if ($onlyId) {
            return $managerIds;
        }
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => Core::HLBLOCK_CODE_MANAGERS]
        ])->fetch();
        $users = [];
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            $dbResult = $hlClassName::getList([
                'filter' => ['ID' => $managerIds]
            ]);
            while ($arData = $dbResult->Fetch()) {
                $users[] = $arData;
            }
        } else {
            throw new Exception('HighloadBlock \'Menedzhery\' not found');
        }
        return $users;
    }

    public static function recursion($structureManagers) {
        $result = [];
        foreach ($structureManagers as $manager) {
            $result[] = $manager['UF_ID_MANAGER'];
            $result[] = $manager['UF_ID_SUB_MANAGER'];
            if($manager['sub'] && !empty($manager['sub'])) {
                $result = array_merge($result, self::recursion($manager['sub']));
            }
        }

        return $result;
    }

    public static function getStackManager($managerId)
    {
        self::getStructureManagers($managerId);

        return static::$stackManager;
    }

    /**
     * Структура руководителей для менеджера
     * @param $managerId
     *
     * @return array
     * @throws Exception
     */
    public static function getStructureBossManagers($managerId) {
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => Core::HLBLOCK_CODE_STRUCTURE_MANAGERS]
        ])->fetch();
        if ($hlblock) {
            $hlClassName = (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
            return self::recursionStructureBossManagers($managerId, $hlClassName);
        } else {
            throw new Exception('HighloadBlock \'StructureManagers\' not found');
        }
    }

    private static function recursionStructureBossManagers($managerId, $hlClassName) {
        $dbResult = $hlClassName::getList([
            'filter' => ['UF_ID_SUB_MANAGER' => $managerId],
            'select' => ['UF_ID_MANAGER', 'UF_ID_SUB_MANAGER']
        ]);

        while ($arData = $dbResult->Fetch()) {
            $result[] = $arData['UF_ID_MANAGER'];
            $stack = self::recursionStructureBossManagers($arData['UF_ID_MANAGER'], $hlClassName);
            $result = array_merge($result, $stack);
        }

        return isset($result) ? $result : [];
    }
}
