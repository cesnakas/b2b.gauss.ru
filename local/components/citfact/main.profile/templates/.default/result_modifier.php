<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
global $USER;
$idUser = $USER->GetID();
$user = new CUser;

$rsUser = CUser::GetByID($idUser);
$arUser = $rsUser->Fetch();

$arResult['UF_EMAIL_PROMOTIONS'] = $arUser['UF_EMAIL_PROMOTIONS'];
$arResult['UF_EMAIL_NEWS'] = $arUser['UF_EMAIL_NEWS'];