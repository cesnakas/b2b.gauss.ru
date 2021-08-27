<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$nextNewsUrl = $arResult["NEAR_ELEMENTS"]["RIGHT"][0]["DETAIL_PAGE_URL"];


$unixDate = MakeTimeStamp($arResult["DISPLAY_PROPERTIES"]['DATE']['DISPLAY_VALUE']);

$formatDate = formatDate('f, Y', $unixDate);
?>
<div class="static-section styled-list">
    <div class="static-content">
        <h1><?= $arResult["NAME"] ?></h1>
        <div class="static-section__date"><?= $formatDate; ?></div>

        <? if ($arResult["DETAIL_PICTURE"]["src"]) { ?>
            <img src="<?= $arResult["DETAIL_PICTURE"]["src"] ?>"
                 data-src="<?= $arResult["DETAIL_PICTURE"]["src"] ?>"
                 alt=""
                 title=""
                 class="lazy lazy--replace">
        <? } ?>

        <div>
            <? if ($arResult['DETAIL_TEXT_TYPE'] == 'text') { ?>
                <p><? echo $arResult["DETAIL_TEXT"]; ?></p>
            <? } else { ?>
                <? echo $arResult["~DETAIL_TEXT"]; ?>
            <? } ?>
        </div>
        <div class="sale-d__buttons">
            <a href="<?= $arResult['LIST_PAGE_URL']; ?>" class="btn btn--transparent">Все новости</a>
            <? if (!empty($nextNewsUrl)): ?>
                <a href="<?= $nextNewsUrl ?>" class="btn btn--transparent">Следующая новость</a>
            <? endif ?>
            <?if(!empty($arResult["PROPERTIES"]["SRC_CAT"]["VALUE"]) && !empty($arResult["PROPERTIES"]["TEXT_CAT"]["VALUE"])):?>
                <a href="<?=$arResult["PROPERTIES"]["SRC_CAT"]["VALUE"]?>" class="btn btn--transparent"><?=$arResult["PROPERTIES"]["TEXT_CAT"]["VALUE"]?></a>
            <?else:?>
                <a href="/catalog/" class="btn btn--transparent">Перейти в каталог</a>
            <?endif;?>
        </div>
    </div>
</div>