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
<div class="title-1"><span>Как добраться</span></div>
<div class="b-tabs" data-tab-group>
    <div class="b-tabs-head" data-tab-header>
        <? $i = 1;
        foreach ($arResult['SECTIONS'] as $section) { ?>
            <a href="javascript:void(0);"
               class="b-tabs-link <? if ($i == 1): ?>active<? endif; ?>"
               data-tab-btn="<?= $i; ?>">
                <?= $section['NAME'] ?>
            </a>
            <? $i++;
        } ?>
    </div>
    <div class="b-tabs__content" data-tab-content>
        <? $j = 1;
        foreach ($arResult['SECTIONS'] as $section) { ?>
            <div class="b-tabs__item <? if ($j == 1): ?>active<? endif; ?>" data-tab-body="<?= $j; ?>">
                <? foreach ($section['ITEMS'] as $item) { ?>
                    <?
                    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="title-4" id="<?= $this->GetEditAreaId($item['ID']); ?>"><?= $item['NAME'] ?></div>
                    <p><?= $item['PREVIEW_TEXT'] ?></p>
                <? } ?>
            </div>
            <? $j++;
        } ?>
    </div>
</div>