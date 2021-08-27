<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use Citfact\SiteCore\Core;

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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
$is_closed = false; // Переменная для проверки закрытия блока
?>
<?$i=1;?>
<? if($arParams['SECTION_MAIN']):?>
    <div class="title-1">
        <span><a href="/catalog/">Каталог Gauss</a></span>
    </div>
<?endif;?>
<div class="c-section<?= $arParams['SECTION_MAIN'] ? ' c-section--main' : '' ?>">
    <div class="c-section__section">
        <?
        foreach ($arResult['SECTIONS'] as $key => $arSection) {
        if (!in_array($arSection['CODE'], [Core::IBLOCK_SECTION_CODES_PROMO])) {
        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
        <div class="c-section__item c-section__item--horizontal" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
            <div class="c-section__img">
                <img src="<?= $arSection['PICTURE']['SRC'] ?>" <? /* превью размера 43х43 px */ ?>
                     data-src="<?= $arSection['PICTURE']['SRC'] ?>"
                     alt="<?= $arSection['NAME'] ?>"
                     title="<?= $arSection['NAME'] ?>"
                     class="lazy lazy--replace">
            </div>
            <div class="c-section__inner">
                <a href="<?= $arSection['SECTION_PAGE_URL'] ?>"
                   class="title-2"><?= $arSection['NAME'] ?></a>

                <div class="c-section__links">
                    <? foreach ($arSection["SECTIONS"] as $arSubSection) {
                        $this->AddEditAction($arSubSection['ID'], $arSubSection['EDIT_LINK'], $strSectionEdit);
                        $this->AddDeleteAction($arSubSection['ID'], $arSubSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams); ?>
                        <a href="<?= $arSubSection['SECTION_PAGE_URL'] ?>"
                           id="<? echo $this->GetEditAreaId($arSubSection['ID']); ?>">
                            <?= $arSubSection['NAME'] . " "; ?>
                        </a>
                    <? } ?>

                    <a href="/products/<?= $arSection['CODE']; ?>/" class="link-more">
                        <span>Все товары</span>
                        <svg class='i-icon'>
                            <use xlink:href='#icon-arrow-r'/>
                        </svg>
                    </a>
                </div>
                <?
                $arSection['SECTION_PAGE_URL'] = $arSection['SECTION_PAGE_URL'] . '?sort=data-desc&order=desc';
                $arSection['SECTION_PAGE_URL'] = str_replace('catalog', 'products', $arSection['SECTION_PAGE_URL'])
                ?>
                <a href="<?= $arSection['SECTION_PAGE_URL'] ?>" class="c-section__b-link">
                    Новинки категории
                </a>
            </div>
        </div>
        <?if($i % 2 == 0) {
            $is_closed = true;?>
            </div>
            <?if($arParams['SECTIONS_COUNT'] > $i) {
                $is_closed = false;?>
                <div class="c-section__section">
            <?}?>
        <?}
        $i++;
        }
    }
    if(!$is_closed) {?>
        </div>
    <?}?>
</div>
