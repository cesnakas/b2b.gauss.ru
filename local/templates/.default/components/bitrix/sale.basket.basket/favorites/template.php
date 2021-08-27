<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\Sitecore\CatalogHelper\ElementRepository;
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
/** @var CBitrixBasketComponent $component */

$elem = new ElementRepository;
CJSCore::Init(array('currency'));

$curPage = '?' . $arParams["ACTION_VARIABLE"] . '=';
$curPageDelayed = $APPLICATION->GetCurPage() . '?' . $arParams["ACTION_VARIABLE_DELAYED"] . '=';
$arUrls = array(
    "delete" => $curPage . "delete&id=#ID#",
    "delay" => $curPage . "delay&id=#ID#",
    "add" => $curPage . "add&id=#ID#",
);
$arUrlsDelayed = array(
    "delete" => $curPageDelayed . "delete&id=#ID#&favourite=y", // остаемся в избранном. Обработчик в модуле
    "delay" => $curPageDelayed . "delay&id=#ID#&favourite=y", // остаемся в избранном. Обработчик в модуле
    "add" => $APPLICATION->GetCurPage() . $curPage . "add&id=#ID#&quantity=#QUANTITY#&" . $arParams["ACTION_VARIABLE_ADD"] . '=Y', // переходим в корзину
);
unset($curPage);

$arBasketJSParams = array(
    'SALE_DELETE' => GetMessage("SALE_DELETE"),
    'SALE_DELAY' => GetMessage("SALE_DELAY"),
    'SALE_TYPE' => GetMessage("SALE_TYPE"),
    'TEMPLATE_FOLDER' => $templateFolder,
    'DELETE_URL' => $arUrls["delete"],
    'DELAY_URL' => $arUrls["delay"],
    'ADD_URL' => $arUrls["add"],
    'EVENT_ONCHANGE_ON_START' => (!empty($arResult['EVENT_ONCHANGE_ON_START']) && $arResult['EVENT_ONCHANGE_ON_START'] === 'Y') ? 'Y' : 'N'
);
?>

<script type="text/javascript">
  var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
</script>
<?

if ($APPLICATION->GetCurDir() == '/personal/favourite/') {
    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket_items_delayed.php");
    return;
}

$APPLICATION->AddHeadScript($templateFolder . "/script.js");

if (strlen($arResult["ERROR_MESSAGE"]) <= 0) {
    ?>
    <div id="warning_message">
        <?
        if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"])) {
            foreach ($arResult["WARNING_MESSAGE"] as $v)
                ShowError($v);
        }
        ?>
    </div>
    <?
    $normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
    include($_SERVER["DOCUMENT_ROOT"] . "/local/include/modals/templateorder.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/local/include/modals/templateorder-notify.php");
    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket_items.php");
} else {?>
    <div class="b-cart-empty">
        <div class="b-cart-empty__text">
            <?= \Bitrix\Main\Localization\Loc::getMessage('YOUR_EMPTY_BASKET') ?>
        </div>

        <div class="b-cart-empty__message">
            <?= \Bitrix\Main\Localization\Loc::getMessage('EMPTY_BASKET') ?>
        </div>

        <div class="b-cart-empty__funcs">
            <a href="<?=$arParams['PATH_TO_CATALOG']?>" class="btn btn--red"><?= \Bitrix\Main\Localization\Loc::getMessage('VIEW_CATALOG') ?></a>
            <a href="<?=$arParams['PATH_TO_MANUFACTURED']?>" class="btn btn--yellow"><?= \Bitrix\Main\Localization\Loc::getMessage('VIEW_BRAND') ?></a>
        </div>


        <? $APPLICATION->IncludeFile(
            SITE_DIR . "local/include/areas/html/slider-detail-recent.php",
            Array(),
            Array("MODE" => "html")
        ); ?>
    </div>
<?}?>


