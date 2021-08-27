<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$APPLICATION->SetTitle('Пользователи ' . $arResult['ELEMENT_NAME']);
?>

<div class="lk__register">
    <a class="btn btn--transparent" href="/personal/register/add_new_user/?contragent_guid=<?=$arResult['ELEMENT_ID']?>">
        Добавить нового пользователя
    </a>
</div>

<? $APPLICATION->IncludeComponent("citfact:lk.requesting_user.list", ".default",
    array(
        'PAGE_ELEMENT_COUNT' => 20,
        'CONTRAGENT_GUID' => $arResult['ELEMENT_ID'],
        'COMPANY_NAME' => $arResult['ELEMENT_NAME'],
    ),
    false
); ?>
<? $APPLICATION->IncludeComponent("citfact:lk.user.list", ".default",
    array(
        'PAGE_ELEMENT_COUNT' => 20,
        'CONTRACTOR_GUID' => $arResult['ELEMENT_ID'],
        'COMPANY_NAME' => $arResult['ELEMENT_NAME'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER'] . $arResult['ELEMENT_ID'] . '/',
    ),
    false
); ?>
