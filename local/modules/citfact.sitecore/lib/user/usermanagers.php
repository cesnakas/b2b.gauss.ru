<?php

namespace Citfact\SiteCore\User;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\UserTable;
use Citfact\DataCache\UserData\UserGroupID;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\UserDataManager\UserDataManager;


class UserManagers
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function getAllAssistantsEmails()
    {
        $UserGroupID = new UserGroupID();

        $filter = Array('GROUPS_ID' => [$UserGroupID->getByCode('ASSISTANT')]);
        $params = ['FIELDS' => ['EMAIL']];
        $emails = [];
        $user = \CUser::GetList($by = "NAME", $order = "desc", $filter, $params);

        while($arUser = $user->Fetch()) {
            $emails[] = $arUser['EMAIL'];
        }
        return $emails;
    }

    /**
     * @param string $contragentXmlId
     * @return array
     * @throws \Exception
     */
    public static function getManagerByContragent($contragentXmlId = '')
    {
        $UserGroupID = new UserGroupID();

        $managerXmlId = UserDataManager::getContrAgentInfo($contragentXmlId)['UF_MENEDZHER'];
        $core = Core::getInstance();
        $hlBlockCode = $core::HLBLOCK_CODE_MANAGERS;

        $hl_id = $core->getHlBlockId($hlBlockCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList([
            'select' => ['ID'],
            'filter' => ['UF_XML_ID' => $managerXmlId],
        ]);

        $managerHLBlockId = $rsData->fetch()['ID'];

        $filter = Array('UF_MANAGER_ID' => $managerHLBlockId, 'GROUPS_ID' => [$UserGroupID->getByCode('MANAGER')]);
        $params = ['SELECT' => ["UF_MANAGER_ID"], 'FIELDS' => ['ID', 'NAME', 'EMAIL', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHONE']];
        return \CUser::GetList($by = "NAME", $order = "desc", $filter, $params)->Fetch();
    }

    /**
     * @param string $contragentXmlId
     * @return array
     * @throws \Exception
     */
    public static function getAssistantsByContragent($contragentXmlId = '')
    {

        $UserGroupID = new UserGroupID();

        $managerXmlId = UserDataManager::getContrAgentInfo($contragentXmlId)['UF_MENEDZHER'];
        $core = Core::getInstance();
        $hlBlockCode = $core::HLBLOCK_CODE_ASSISTANTS;

        $hl_id = $core->getHlBlockId($hlBlockCode);
        $hlblock = HighloadBlockTable::getById($hl_id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList([
            'select' => ['UF_ID'],
            'filter' => ['UF_MENEDZHER' => $managerXmlId],
        ]);


        $assistantHLBlockUfIds = [];
        while ($res = $rsData->fetch()) {
            $assistantHLBlockUfIds[] = $res['UF_ID'];
        }


        $rsData = $entity_data_class::getList([
            'select' => ['ID'],
            'filter' => ['UF_ID' => $assistantHLBlockUfIds],
        ]);

        $assistantHLBlockIds = [];
        while ($res = $rsData->fetch()) {
            $assistantHLBlockIds[] = $res['ID'];
        }

        if (!empty($assistantHLBlockIds)) {
            $filter = Array('UF_ASSISTANT_ID' => $assistantHLBlockIds, 'GROUPS_ID' => [$UserGroupID->getByCode('ASSISTANT')]);
            $params = ['SELECT' => ["UF_ASSISTANT_ID"], 'FIELDS' => ['ID', 'NAME', 'EMAIL', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHONE']];

            $res = \CUser::GetList($by = "NAME", $order = "desc", $filter, $params);

            $assistants = [];

            while ($assistant = $res->GetNext()) {
                $assistants[] = $assistant;
            }

            return $assistants;
        }

        return null;

    }

    public static function getAssistantByContragent($contragentXmlId = '')
    {
        $assistants = static::getAssistantsByContragent($contragentXmlId);
        if (!empty($assistants) && is_array($assistants)) {
            reset($assistants);
            return current($assistants);
        }
        return false;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getManagerByRegionAuthUser()
    {
        global $USER;
        if ($USER->IsAuthorized() == false) {
            return [];
        }

        $arFilter = array("ID" => $USER->GetID());
        $arParams["SELECT"] = array("UF_REGIONS");
        $arRes = \CUser::GetList($by = "", $order = "", $arFilter, $arParams);
        if ($res = $arRes->Fetch()) {
            $regionsId = $res['UF_REGIONS'];
        }

        $UserGroupID = new UserGroupID();
        $filter = Array('UF_REGIONS' => $regionsId, 'GROUPS_ID' => [$UserGroupID->getByCode('MANAGER'), $UserGroupID->getByCode('ASSISTANT')]);
        $params = ['SELECT' => ["UF_CONTRAGENT_IDS", "UF_CONTRAGENT_ID", "UF_REGIONS"], 'FIELDS' => ['ID', 'NAME', 'EMAIL', 'LAST_NAME', 'SECOND_NAME']];
        return \CUser::GetList($by = "NAME", $order = "desc", $filter, $params)->Fetch();
    }
}
