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
?>
<div class="title-1">
    <span><a href="/<?=$arResult["CODE"]?>/">Акции</a></span>
    <a href="/<?=$arResult["CODE"]?>/" class="link-more">
        <span>Все акции</span>
        <svg class='i-icon'>
            <use xlink:href='#icon-arrow-r'/>
        </svg>
    </a>
</div>

<div class="main-sale">
    <div class='swiper-container' data-slider='main-sale'>
        <div class='swiper-wrapper'>
        <?foreach($arResult['ITEMS'] as $key => $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $key++;
            ?>
            <div class="swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="sale-item">
                    <img src="<?=$core::IMAGE_PLACEHOLDER_TRANSPARENT?>"
                         data-src="<?= $arItem['PREVIEW_PICTURE']['SRC']?>"
                         alt='<?= $item['PREVIEW_PICTURE']['ALT'] ?>'
                         title="<?=$item['PREVIEW_PICTURE']['TITLE']?>"
                         class="sale-item__img lazy">
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

            </div>
        <?endforeach;?>
        </div>
    </div>
</div>