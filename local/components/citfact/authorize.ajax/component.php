<?php

/*
 * This file is part of the Studio Fact package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$app = Application::getInstance();
$request = $app->getContext()->getRequest();

CJSCore::Init();

$arResult["ALLOW_SOCSERV_AUTHORIZATION"] = (COption::GetOptionString("main", "allow_socserv_authorization", "Y") != "N" ? "Y" : "N");

$arResult["AUTH_SERVICES"] = false;
$arResult["CURRENT_SERVICE"] = false;
$arResult["FOR_INTRANET"] = false;
if(IsModuleInstalled("intranet")||IsModuleInstalled("rest"))
    $arResult["FOR_INTRANET"] = true;
if(!$USER->IsAuthorized() && CModule::IncludeModule("socialservices") && ($arResult["ALLOW_SOCSERV_AUTHORIZATION"] == 'Y'))
{
    $oAuthManager = new CSocServAuthManager();
    $arServices = $oAuthManager->GetActiveAuthServices(array(
        'BACKURL' => $arResult['~BACKURL'],
        'FOR_INTRANET' => $arResult['FOR_INTRANET'],
    ));

    if(!empty($arServices))
    {
        $arResult["AUTH_SERVICES"] = $arServices;
        if(isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '' && isset($arResult["AUTH_SERVICES"][$_REQUEST["auth_service_id"]]))
        {
            $arResult["CURRENT_SERVICE"] = $_REQUEST["auth_service_id"];
            if(isset($_REQUEST["auth_service_error"]) && $_REQUEST["auth_service_error"] <> '')
            {
                $arResult['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult["CURRENT_SERVICE"], $_REQUEST["auth_service_error"]);
            }
            elseif(!$oAuthManager->Authorize($_REQUEST["auth_service_id"]))
            {
                $ex = $APPLICATION->GetException();
                if ($ex)
                    $arResult['ERROR_MESSAGE'] = $ex->GetString();
            }
        }
    }
}

$this->IncludeComponentTemplate();
