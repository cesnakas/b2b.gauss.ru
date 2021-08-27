<?php

namespace Citfact\SiteCore\User;


use Bitrix\Main\UserTable;
use Bitrix\Main\GroupTable;
use Bitrix\Main\UserGroupTable;

class UserRepository
{
    /**
     * @param $phone
     * @return mixed
     */
    public static function getUserByPhone($phone)
    {
        $userTable = new UserTable();
        $res = $userTable->getList([
            'filter' => [
                'PERSONAL_PHONE' => $phone
            ],
            'select' => [
                'ID',
                'EMAIL',
                'NAME',
                'LAST_NAME',
                'SECOND_NAME',
                'PERSONAL_PHONE',
                'UF_CONTRAGENT_IDS',
                'UF_CONTRAGENT_ID'
            ]
        ]);
        return $res->fetch();
    }

    /**
     * @param $email
     * @return mixed
     */
    public static function getUserByEmail($email)
    {
        $userTable = new UserTable();
        $res = $userTable->getList([
            'filter' => [
                'EMAIL' => $email
            ],
            'select' => [
                'ID',
                'EMAIL',
                'NAME',
                'LAST_NAME',
                'SECOND_NAME',
                'PERSONAL_PHONE',
                'UF_CONTRAGENT_IDS',
                'UF_CONTRAGENT_ID'
            ]
        ]);
        return $res->fetch();
    }


    /**
     * @param $id
     * @return mixed
     */
    public static function getUserById($id)
    {
        $userTable = new UserTable();
        $res = $userTable->getList([
            'filter' => [
                'ID' => $id,
                'ACTIVE' => 'Y',
            ],
            'select' => [
                'ID',
                'EMAIL',
                'NAME',
                'LAST_NAME',
                'SECOND_NAME',
                'PERSONAL_PHONE',
                'UF_CONTRAGENT_IDS',
                'UF_CONTRAGENT_ID'
            ]
        ]);
        return $res->fetch();
    }

    /**
     * @param int $userID
     * @return mixed
     */
    public static function getUserGroups(int $userID)
    {
        $rsGroupIDs = UserGroupTable::getList([
            'filter' => [
                'USER_ID' => $userID
            ],
            'select' => [
                'GROUP_ID'
            ]
        ])->fetchAll();
        $userGroupIDs = [];
        foreach ($rsGroupIDs as $rsGroupID) {
            $userGroupIDs[] = $rsGroupID['GROUP_ID'];
        }

        $userGroups = GroupTable::getList([
            'filter' => [
                '@ID' => $userGroupIDs
            ],
        ])->fetchAll();

        return $userGroups;
    }

    public static function getUserGroupCodes(int $userID)
    {
        $userGroups = UserRepository::getUserGroups($userID);
        $userGroupCodes = [];
        foreach ($userGroups as $userGroup) {
            $userGroupCodes[] = $userGroup['STRING_ID'];
        }

        return $userGroupCodes;
    }

    //проверяет входит ли пользователь $userID хотя бы в одну их групп $groupToCheck
    public static function checkUserInGroup(int $userID, array $groupToCheck)
    {
        $userGroups = UserRepository::getUserGroupCodes($userID);
        $hasRole = count(array_intersect($groupToCheck, $userGroups)) > 0 ? true : false;
        return $hasRole;
    }

    public static function getUserDataByID(int $userID)
    {
        $userTable = new UserTable();
        $res = $userTable->getList([
            'filter' => [
                'ID' => $userID
            ],
            'select' => ['*']
        ]);
        return $res->fetch();
    }
}