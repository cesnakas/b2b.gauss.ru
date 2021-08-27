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
<?if($arParams['IS_FOOTER'] === true):?>
    <div class="f__socials">
        <? foreach ($arResult['ITEMS'] as $item) { ?>
            <?
            $this->AddEditAction($item['ID'], $item['ADD_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_ADD"));
            $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <a id="<?=$this->GetEditAreaId($item['ID']);?>"
               href="<?= $item['PROPERTY_LINK_VALUE']; ?>"
               target="_blank"
               class="f__social f__social--<?=$item['CODE']?>">
                <svg class='i-icon'>
                    <use xlink:href='#<?=$item['PROPERTY_ICON_VALUE']?>'/>
                </svg>
            </a>
        <? } ?>
    </div>
<?else:?>
    <div class="h-menu-d__socials animated fadeInUp">
        <? foreach ($arResult['ITEMS'] as $item) { ?>
            <?
            $this->AddEditAction($item['ID'], $item['ADD_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_ADD"));
            $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <a id="<?=$this->GetEditAreaId($item['ID']);?>"
               href="<?= $item['PROPERTY_LINK_VALUE']; ?>"
               target="_blank"
               class="h-menu-d__social h-menu-d__social--<?=$item['CODE']?>">
                <svg class='i-icon'>
                    <use xlink:href='#<?=$item['PROPERTY_ICON_VALUE']?>'/>
                </svg>
            </a>
        <? } ?>
    </div>
<?endif;?>