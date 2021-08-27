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

use Citfact\Sitecore\Core;

$core = Core::getInstance();

$this->setFrameMode(true);
//ЧТобы использовать баннер, нужно чтобы только один элемент в инфоблоке был активный
$item = $arResult['ITEMS']['0'];
$imgBig = CFile::GetFileArray($item['PROPERTIES']['IMG']['VALUE']);
$imgMobile = CFile::GetFileArray($item['PROPERTIES']['IMG_MOBILE']['VALUE']);
?>
<div class="main-b2b">
    <?if(!empty($item['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'])):?>
        <img class="lazy lazy--replace"
             src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
             data-src="<?=$imgBig['SRC'] ?>"
             data-src-mobile="<?=$imgMobile['SRC']?>"
             alt="<?=$imgBig['ORIGINAL_NAME'] ?>">
        <a href="<?=$item['DISPLAY_PROPERTIES']['LINK']['VALUE']?>" class="main-b2b__btn"><?=$item['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE']?></a>
    <?else:?>
        <a href="<?=$item['DISPLAY_PROPERTIES']['LINK']['VALUE']?>">
            <img class="lazy lazy--replace"
                 src="<?= \Citfact\SiteCore\Core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                 data-src="<?=$imgBig['SRC'] ?>"
                 data-src-mobile="<?=$imgMobile['SRC']?>"
                 alt="<?=$imgBig['ORIGINAL_NAME'] ?>">
        </a>
    <?endif;?>
    <div class="main-b2b__inner">
        <?if(isset($item['DISPLAY_PROPERTIES']['TITLE_1'])):?><div class="main-b2b__title1"> <?=$item['DISPLAY_PROPERTIES']['TITLE_1']['VALUE'] ?> </div><?endif;?>
        <?if(isset($item['DISPLAY_PROPERTIES']['TITLE_2'])):?><div class="main-b2b__title2"> <?=$item['DISPLAY_PROPERTIES']['TITLE_2']['VALUE'] ?> </div><?endif;?>
        <div class="main-b2b__text"><?=$item['PREVIEW_TEXT']?></div>
    </div>
</div>
