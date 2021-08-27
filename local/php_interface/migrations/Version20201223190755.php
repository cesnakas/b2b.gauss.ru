<?php

namespace Sprint\Migration;


class Version20201223190755 extends Version
{
    protected $description = "Активация рассылки для пользователей";

    protected $moduleVersion = "3.17.2";

    public function up()
    {
        $filter = [
            'ACTIVE' => 'Y',
            '!GROUPS_ID' => [6, 7, 8]
        ];
        $rsUsers = \CUser::GetList(($by="personal_country"), ($order="desc"), $filter);
        $user = new \CUser;
        while($arUser = $rsUsers->Fetch()) {
            $user->Update($arUser['ID'], ['UF_EMAIL_NEWS' => 1, 'UF_EMAIL_PROMOTIONS' => 1]);
        }
    }

    public function down()
    {
        //your code ...
    }
}
