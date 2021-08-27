<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$i = 0;
$itemsOnFirstScreen = 9;

$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
?>
<div class="sale" id="block_<?=$bxajaxid?>">
    <!-- items !-->
    <?foreach($arResult['ITEMS'] as $key => $arItem):?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="sale-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <img <? if($i < $itemsOnFirstScreen) { ?>
            src="<?=$arItem['PREVIEW_PICTURE']['SRC']['PREVIEW']?>"
        <? } else { ?>
            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=<?/*=$core::IMAGE_PLACEHOLDER_TRANSPARENT*/?>"
        <? } ?>
            data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']['ORIGIN']?>"
            alt='<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>'
            title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
            class="sale-item__img lazy<?= $i < $itemsOnFirstScreen ? ' lazy--replace' : '' ?>"
        >
        <span class="sale-item__inner">
        <?if($arParams['DISPLAY_NAME']!='N' && $arItem['NAME']):?>
            <span class="title-2">
                <?=$arItem['NAME']?>
            </span>
        <?endif;?>
        <?if ($arItem['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] && $arItem['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']){?>
            <span class="sale-item__date">
                <?='с '.$arItem['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'].' по '.$arItem['DISPLAY_PROPERTIES']['DATE_TO']['VALUE'];?>
            </span>
        <?}elseif($arItem['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] && !$arItem['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']){?>
            <span class="sale-item__date">
                <?='c '.$arItem['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'];?>
            </span>
        <?}elseif(!$arItem['DISPLAY_PROPERTIES']['DATE_FROM']['VALUE'] && $arItem['DISPLAY_PROPERTIES']['DATE_TO']['VALUE']){?>
            <span class="sale-item__date">
                <?='по '.$arItem['DISPLAY_PROPERTIES']['DATE_TO']['VALUE'];?>
            </span>
        <?}
        ?>
        </span>
    </a>
    <? $i++;
    endforeach;?>
    <!-- /items !-->
    <? if($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer<$arResult["NAV_RESULT"]->nEndPage):?>
        <!-- btn !-->
        <div class="sale__more" id="btn_<?=$bxajaxid?>">
            <a class="btn btn--loading"
               data-ajax-id="<?=$bxajaxid?>"
               href="javascript:void(0)"
               data-show-more="<?=$arResult["NAV_RESULT"]->NavNum?>"
               data-next-page="<?=($arResult["NAV_RESULT"]->NavPageNomer + 1)?>"
               data-max-page="<?=$arResult["NAV_RESULT"]->nEndPage?>">
                <svg class='i-icon'>
                    <use xlink:href='#icon-loading'/>
                </svg>
                <span>Загрузить ещё</span>
                <span>Загружается</span>
            </a>
        </div>
        <!-- /btn !-->
    <?endif?>
</div>