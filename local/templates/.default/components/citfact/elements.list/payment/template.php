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
<? if ($arResult['ITEMS']) { ?>
    <div class="title-1"><span>Оплата</span></div>
    <p style="color:#FF2D2D">
        <?
        $APPLICATION->IncludeComponent("bitrix:main.include", "",
            [
                "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                "PATH" => '/local/include/areas/delivery/payment-text.php',    // Путь к файлу области
            ],
            false
        ); ?>
    </p>
    <? foreach ($arResult['ITEMS'] as $item) { ?>
        <?
        $this->AddEditAction($item['ID'], $item['ADD_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_ADD"));
        $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="payment" id="<?=$this->GetEditAreaId($item['ID']);?>">
            <div class="payment__icon">
                <svg class='i-icon'>
                    <use xlink:href='#<?=$item['PROPERTY_ICON_VALUE']?>'/>
                </svg>
            </div>
            <div class="payment__text">
                <span><?=$item['NAME']?></span>
                <p><?=$item['PREVIEW_TEXT']?></p>
            </div>
        </div>
    <? }
} ?>
