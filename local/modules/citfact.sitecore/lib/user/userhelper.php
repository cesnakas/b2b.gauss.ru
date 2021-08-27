<?php

namespace Citfact\SiteCore\User;


class UserHelper
{
    /**
     * @param $arUser
     * @return string
     */
    public static function getFullNameByUser($arUser)
    {
        if (empty($arUser)) {
            return '';
        }

        $arName = [];
        if ($arUser['LAST_NAME']) {
            $arName[] = $arUser['LAST_NAME'];
        }
        if ($arUser['NAME']) {
            $arName[] = $arUser['NAME'];
        }
        if ($arUser['SECOND_NAME']) {
            $arName[] = $arUser['SECOND_NAME'];
        }

        if (empty($arName)) {
            $arName[] = $arUser['EMAIL'];
        }

        return (string)implode(' ', $arName);
    }

}