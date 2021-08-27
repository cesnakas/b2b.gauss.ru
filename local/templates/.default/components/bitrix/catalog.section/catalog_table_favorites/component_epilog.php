<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

// есть ли GET параметры? Если есть, установить canonical на url без параметров
$urlHaveParams = false;
if (strpos($_SERVER["REQUEST_URI"], "?") !== false) {
    $urlHaveParams = true;
}

if ($urlHaveParams) {
    $APPLICATION->AddViewContent('canonical', '<link rel="canonical" href="http://' . SITE_SERVER_NAME . $APPLICATION->GetCurDir() . '">');
}

if (!empty($arResult['ELEMENT_PAGE_AMOUNT'])) {
    $APPLICATION->AddViewContent('amount', 'Товаров на странице:&nbsp;&nbsp;<span>' . $arResult['ELEMENT_PAGE_AMOUNT'] . '</span>');
}