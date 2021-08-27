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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>
<?php if ($arResult['BANNERS']) { ?>
    <div class="c-banners">
        <?php foreach ($arResult['BANNERS'] as $path) { ?>
            <a href="<?= $arResult['SECTION']['UF_BANNER_LINK'] ?>">
                <img src="<?= $path['src'] ?>" alt="">
            </a>
        <?php } ?>
    </div>
<?php } ?>
<?php
if (!empty($arResult["SECTIONS"])) { ?>
    <div class="c-subsection">
        <?php
        foreach ($arResult["SECTIONS"] as $arSection) :
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams); ?>
            <a href="<?= $arSection['SECTION_PAGE_URL'] ?>"
               class="c-subsection__item"
               id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
                <img class="lazy lazy--replace"
                     src="<?= $arSection['PICTURE']['SRC'] ?>"
                     alt="<?= $arSection['NAME'] ?>"
                     title="<?= $arSection['NAME'] ?>"
                     data-src="<?= $arSection['PICTURE']['SRC'] ?>">

                <span class="title-2"><?= $arSection['NAME'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>
<?php } else { ?>
    <div class="c__empty">
        <h3>Товаров в разделе не найдено.</h3>
    </div>
<?php } ?>

<? if (!empty($arResult['SECTION']['~DESCRIPTION']) || $arResult['SECTION']['UF_TITLE']) { ?>
    <div class="seo">
        <? if ($arResult['SECTION']['UF_TITLE']) { ?>
            <div class="title-1">
                <span><?= $arResult['SECTION']['UF_TITLE'] ?></span>
            </div>

            <? if ($arResult['SECTION']['~DESCRIPTION']) { ?>
                <div data-show-more>
                    <p><?= $arResult['SECTION']['~DESCRIPTION'] ?></p>
                </div>
            <? } ?>

        <? } else { ?>
            <div>
                <p><?= $arResult['SECTION']['~DESCRIPTION'] ?></p>
            </div>
        <? } ?>

        <? if (!empty($arResult['SECTION']['~DESCRIPTION'])) { ?>
            <a class="link-more link-more--toggle hidden" href="javascript:void(0)" style="display: none;"
               data-show-more-btn>
                <span>Читать далее</span>
                <span>Скрыть</span>
                <svg class='i-icon'>
                    <use xlink:href='#icon-arrow-r'/>
                </svg>
            </a>
        <? } ?>
    </div>
<? } ?>
