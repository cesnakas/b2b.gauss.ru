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
$APPLICATION->SetTitle('История запросов');
?>
<? $APPLICATION->IncludeComponent("citfact:lk.user.history", ".default",
    array(
        'PAGE_ELEMENT_COUNT' => 50,
        'CONTRACTOR_GUID' => $arResult['ELEMENT_ID'],
    ),
    false
); ?>