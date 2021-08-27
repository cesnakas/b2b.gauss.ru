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
<div class="news">
	<div class="title-1">
		<span><a href="/press-center/news/">Новости</a></span>

		<a href="/press-center/" class="link-more">
			<span>Перейти в пресс-центр</span>
			<svg class='i-icon'>
				<use xlink:href='#icon-arrow-r'/>
			</svg>
		</a>
	</div>

	<div class="news__main">
		<?foreach($arResult['ITEMS'] as $key => $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$key++;
			?>
			<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news__item animatedSlow" data-animation="fadeInRightSmall" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<img src="<?=$core::IMAGE_PLACEHOLDER_TRANSPARENT?>"
					 data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']['ORIGIN']?>"
					 data-src-small="<?=$arItem['PREVIEW_PICTURE']['SRC']['SMALL']?>"
                     alt='<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>'
                     title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
					 class="lazy">
				<div class="news__inner">
					<div class="news__date">
						<?=$arItem['DISPLAY_PROPERTIES']['DATE']['VALUE']?>
					</div>
					<div class="title-3" data-news-text>
						<?=mb_strtoupper($arItem['NAME'])?>
					</div>
					<div class="btn btn--transparent btn--small">Новость</div>
				</div>
			</a>
		<?endforeach;?>
	</div>
</div>