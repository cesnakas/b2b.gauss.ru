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

$this->setFrameMode(true);?>
<div class="title-1">
    <span class="animated" data-animation="fadeInUpSmall"><a href="/about/">О компании</a></span>
</div>
<? foreach ($arResult['ITEMS'] as $item): ?>
    <?
    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT'));
    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="main-about" id="<?= $this->GetEditAreaId($item['ID']); ?>">
        <div class="main-about__inner">
            <div class="title-1">
                <span><?=mb_strtoupper(html_entity_decode($item['DISPLAY_PROPERTIES']['TITLE']['VALUE']['TEXT']))?></span>
            </div>
            <div class="main-about__items">
                <? foreach ($item['DISPLAY_PROPERTIES']['ADVANTAGES']['VALUE'] as $key => $propVal){?>
                <div class="main-about__item animated" data-animation="fadeInLeftSmall">
                    <?=html_entity_decode($propVal['TEXT'])?>
                </div>
                <?} ?>
            </div>
            <a href="/about/" class="btn btn--big btn--transparent-l animatedSlow" data-animation="fadeInUp">О бренде</a>
        </div>
        <div class="main-about__img animatedWSlow" data-animation="fadeIn">
            <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>"
                 data-src="<?=$item['PREVIEW_PICTURE']['SRC']?>"
                 data-src-small=""
                 alt='<?= $item['PREVIEW_PICTURE']['ALT'] ?>'
                 title="<?=$item['PREVIEW_PICTURE']['TITLE']?>"
                 class="lazy lazy--replace">
        </div>
    </div>
<? endforeach; ?>