<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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
$item = $arParams['ITEM'];
global $USER;

$isPageFavorite = ($arParams['IS_PAGE_FAVOURITE'] == 'Y');
?>
<div class="p" data-item-container="<?= $item['ID'] ?>" id="<?php echo $arParams['AREA_ID']; ?>" itemscope itemtype="http://schema.org/Product">
    <?if($arParams['IS_USER_AUTHORIZED']):?>
        <a href="javascript:void(0);" class="p__favorite"
           data-add2favorites
           title="Удалить из избраннного"
            <? if ($isPageFavorite) { ?>
                data-remove-item="1"
            <? } ?>

           data-itemId="<?= $item['ID'] ?>">
            <? if ($isPageFavorite) { ?>
                <span class="tooltip">
                        <span class="tooltip__icon">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-favorite'/>
                            </svg>
                        </span>
                        <span class="tooltip__text" style="display: none;">
                            Удалить из избраннного
                        </span>
                    </span>
            <? } else { ?>
                <svg class='i-icon'>
                    <use xlink:href='#icon-favorite'/>
                </svg>
            <? } ?>
        </a>
    <?else:?>
        <a href="/local/include/modals/auth.php" data-modal="ajax" class="p__favorite <?/* active */?>" >
            <svg class='i-icon'>
                <use xlink:href='#icon-favorite'/>
            </svg>
        </a>
    <?endif?>

    <?
    $description = '';
    if ($item['PREVIEW_TEXT']) {
        $description = strip_tags($item['~PREVIEW_TEXT']);
    } else if ($item['DETAIL_TEXT']) {
        $description = strip_tags($item['~DETAIL_TEXT']);
    } else {
        $description = strip_tags($item['NAME']);
    }
    ?>
    <link itemprop="image" content="<?=$item['IMG']?>">
    <link itemprop="description" content="<?= $description; ?>">

    <a href="<?= $item['DETAIL_PAGE_URL'] ?>" rel="nofollow" title="<?= $item['NAME'] ?>" class="p__img">
        <img src="<?=$item['IMG']?>"
             data-src="<?=$item['IMG']?>"
             title="<?= $item['NAME'] ?>"
             alt="<?= $item['NAME'] ?>"
             class="lazy lazy--replace">
    </a>

    <div class="p__middle">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $item['NAME'] ?>" class="p__title" itemprop="name">
            <?= $item['NAME'] ?>
        </a>
    </div>

    <?if ($item['PRICE'] != ''){?>
        <div class="p__bottom" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <div class="price">
                <div class="price__current">
                    <div class="price__current"><?=$item['PRICE']?>&nbsp;₽</div>
                </div>
            </div>

            <?if ($item["RAW_PRICE"]):?>
                <meta itemprop="price" content="<?=number_format((float)$item["RAW_PRICE"], 2, '.', '')?>">
                <meta itemprop="priceCurrency" content="RUB">
            <?endif;?>
            <link itemprop="availability" href="http://schema.org/InStock">
        </div>
    <? }?>
</div>