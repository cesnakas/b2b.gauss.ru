<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
$APPLICATION->SetPageProperty('title', "Оформление заказа");

global $USER;
if ($USER->IsAuthorized() === false) {
    LocalRedirect('/personal/');
}

$rsUser = \CUser::GetByID($USER->GetID());
$arResult["arUser"] = $rsUser->GetNext(false);

if (!$arResult['arUser']['UF_ACTIVATE_PROFILE']) {
    LocalRedirect('/personal/', false, "301 Moved permanently");
} else {
    ?>
    <?
    $APPLICATION->IncludeComponent(
        "citfact:order.checkout",
        "",
        array(
            'PATH_TO_ORDER' => '/order/',
            'PATH_TO_BASKET' => '/cart/',
            'DEFAULT_PAYSYSTEM_ID' => '1',
        ),
        false
    );
    ?>
    <?
}
?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>