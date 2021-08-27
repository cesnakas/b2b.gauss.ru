<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Citfact\Sitecore\Core;

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
global $APPLICATION;
$core = Core::getInstance();

if (empty($arResult['ITEMS'])) {
    return;
}
?>
<div class="main-i-article">
    <div class="title-1">
        <span><a href="/press-center/articles/">Полезные статьи</a></span>
    </div>

    <div class="main-i-article__inner">
        <div class="main-i-article__items">
            <?foreach($arResult['ITEMS'] as $key => $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="main-i-article__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>"
                   rel="nofollow"
                   title="<?=mb_strtoupper($arItem['NAME'])?>"
                   class="main-i-article__img">
                    <img src="<?=$core::IMAGE_PLACEHOLDER_TRANSPARENT?>"
                         data-src="<?=$arItem['PREVIEW_PICTURE']['src']?>"
                         alt='<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>'
                         title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
                         class="lazy">
                </a>
                <div class="main-i-article__date">
                    <?=$arItem['DISPLAY_PROPERTIES']['DATE']['VALUE']?>
                </div>
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>"
                   title="<?=mb_strtoupper($arItem['NAME'])?>"
                   rel="nofollow"
                   class="title-3">
                    <?=mb_strtoupper($arItem['NAME'])?>
                </a>
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>"
                   rel="nofollow"
                   title="<?=mb_strtoupper($arItem['NAME'])?>"
                   class="btn btn--transparent btn--small">Статья</a>
            </div>
            <?endforeach;?>
        </div>
    </div>
</div>