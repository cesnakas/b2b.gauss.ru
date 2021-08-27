<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
global $USER;
$idUser = $USER->GetID();
$user = new CUser;

if ($request->getPost('name') == 'news') {
    $isActive = $request->getPost('active');
    if ($isActive == 'Y') {
        $user->Update($idUser, ['UF_EMAIL_NEWS' => 1]);
    } else {
        $user->Update($idUser, ['UF_EMAIL_NEWS' => 0]);
    }
} elseif ($request->getPost('name') == 'promotions') {
    $isActive = $request->getPost('active');
    if ($isActive == 'Y') {
        $user->Update($idUser, ['UF_EMAIL_PROMOTIONS' => 1]);
    } else {
        $user->Update($idUser, ['UF_EMAIL_PROMOTIONS' => 0]);
    }
}
