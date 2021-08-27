<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Application;
use Citfact\SiteCore\Core;
use Citfact\SiteCore\Entity\FormResultAnswerTable;
use Citfact\SiteCore\Entity\FormResultTable;

global $USER;

$core = Core::getInstance();
$formCode = $core::SHORTAGE_WEB_FORM_SID;
$invoiceCode = $core::SHORTAGE_INVOICE_FIELD_SID;
$currentUser = $USER->GetID();
$formId = $core->getFormIdBySid($formCode);
$invoiceFieldId = $core->getfieldIdBySid($invoiceCode);

$arParams['NUM_RESULTS'] = intval($arParams['NUM_RESULTS']);
if($arParams['NUM_RESULTS'] <= 0)
    $arParams['NUM_RESULTS'] = 50;

$arResult['RESULTS'] = array();

if (!$USER->IsAuthorized())
{
    $APPLICATION->AuthForm(GetMessage('FRLM_NEED_AUTH'));
    return false;
}

if (!CModule::IncludeModule('form'))
{
    ShowError('FRLM_MODULE_NOT_INSTALLED');
    return false;
}

$request = Application::getInstance()->getContext()->getRequest();
$name = $request->getQuery("value_id");


$userTable = new FormResultAnswerTable();
\Bitrix\Main\Application::getConnection()->startTracker(false);
$res = $userTable::getList([ 
    'filter' => [
        '%=USER_TEXT_SEARCH' => '%'.$name.'%',
        'FORM_ID'=> $formId,
        'FIELD_ID'=>$invoiceFieldId,
        '=RES_USER_ID' => $currentUser
    ],
    'select'=>['*', 'RES_'=>'RESULT.*']
    ]
);
$sql = $res->getTrackerQuery()->getSql();
\Bitrix\Main\Application::getConnection()->stopTracker();

while($filteredRes = $res->fetch()){
    $filteredResults[$filteredRes['RESULT_ID']] = $filteredRes;
}

$answer = CForm::GetResultAnswerArray(
    $formId,
    $arrColumns,
    $arrAnswers,
    $answers2
);

CModule::IncludeModule('citfact.sitecore');

    $rsResults = CFormResult::GetList(
        $formId,
        ($by = "s_timestamp"),
        ($order = "desc"),
        ['USER_ID' => $currentUser],
        $is_filtered,
        "Y",
        $arParams['NUM_RESULTS']
    );


    if(isset($name)){
        while($results= $rsResults->Fetch()){
            foreach($filteredResults as $resId =>$resData) {
                $arResult['RESULTS'][$resId] = $results;
                $arResult['RESULTS'][$resId]['ANSWERS'] = $answers2[$resId];
            }
        }
    } else {
        while($results= $rsResults->Fetch()){
            $arResult['RESULTS'][$results['ID']]= $results;
            $arResult['RESULTS'][$results['ID']]['ANSWERS'] = $answers2[$results['ID']];
        }
    }

$this->IncludeComponentTemplate();
?>