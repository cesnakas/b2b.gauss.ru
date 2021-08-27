<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 * @global CUser $USER
 * @global CMain $APPLICATION
 */

$this->setFrameMode(true);
?>
<div class="about">
    <? foreach ($arResult['ITEMS'] as $key => $item) : ?>
        <?
        $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div
            class="about__section<?= $item['PROPERTY_TEXT_COLOR_VALUE'] === 'Белый' ? ' about__section--txt-white' : '' ?>"
            id="<?= $this->GetEditAreaId($item['ID']); ?>">
            <div class="about__wrapper">
            <div class="about__img lazy<?= $key === 0 ? ' lazy--replace' : '' ?>"
                <?= $key === 0 ? ' style="background-image: url(' . $item['PREVIEW_PICTURE']['SRC']['LOW'] . ');"' : '' ?>
                 data-src="<?= $item['PREVIEW_PICTURE']['SRC']['ORIGIN'] ?>"
                 data-src-small="<?= $item['PROPERTY_IMAGE_MOBILE']['SRC']['ORIGIN'] ?>"
                <?= $key !== 0 ? ' data-animation="fadeIn"' : '' ?>>
            </div>

            <? if ($key == 0) { ?>
                <div class="about__inner"
                     style="background-color: <?= $item['PROPERTY_BACKGROUND_COLOR_VALUE'] ? $item['PROPERTY_BACKGROUND_COLOR_VALUE'] : ' transparent' ?>;">
                    <div class="container">
                        <div class="about__content">
                            <div class="about__title"><?= $item['NAME'] ?></div>
                            <p><?= $item['PREVIEW_TEXT'] ?></p>
                        </div>
                    </div>
                </div>
            <? } else { ?>
                <div class="about__inner"
                     style="background-color: <?= $item['PROPERTY_BACKGROUND_COLOR_VALUE'] ? $item['PROPERTY_BACKGROUND_COLOR_VALUE'] : ' transparent' ?>;">
                    <div class="container">
                        <div class="about__content">
                            <div class="about__title"
                                 data-split-animation="<?= $key % 2 ? 'fadeInRightSmall' : 'fadeInLeftSmall' ?>"
                                 data-split-animation-m="fadeInUpSmall"
                                 data-split="[24, 16, 22, 29]"
                            ><?= $item['NAME'] ?></div>
                            <p data-split-animation="<?= $key % 2 ? 'fadeInRightSmall' : 'fadeInLeftSmall' ?>"
                               data-split-animation-m="fadeInUpSmall"
                               data-split="[68, 50, 50, 40]"
                            ><?= $item['PREVIEW_TEXT'] ?></p>
                        </div>
                    </div>
                </div>
            <? } ?>
            </div>
        </div>
    <? endforeach; ?>
</div>