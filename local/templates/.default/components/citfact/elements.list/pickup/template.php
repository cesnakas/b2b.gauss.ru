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
    <div class="contacts__inner">
        <? foreach ($arResult['ITEMS'] as $item) { ?>
            <?
            $this->AddEditAction($item['ID'], $item['ADD_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_ADD"));
            $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="contacts__info" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                <div class="contacts__title">
                    <?= $item['NAME'] ?>
                </div>
                <div class="contacts__table">
                    <? if ($item['PROPERTY_ADDRESS_VALUE']) { ?>
                        <div>
                            <div>Адрес:</div>
                            <div><?= $item['PROPERTY_ADDRESS_VALUE'] ?></div>
                        </div>
                    <? } ?>
                    <? if ($item['PROPERTY_PHONE_VALUE']) { ?>
                        <div>
                            <div>Телефон:</div>
                            <div><a href="tel:<?= $item['PROPERTY_PHONE_VALUE'] ?>" class="link"><?= $item['PROPERTY_PHONE_VALUE'] ?></a></div>
                        </div>
                    <? } ?>
                    <? if ($item['PROPERTY_EMAIL_VALUE']) { ?>
                        <div>
                            <div>E-mail:</div>
                            <div><a href="mailto:<?= $item['PROPERTY_EMAIL_VALUE'] ?>" class="link"><?= $item['PROPERTY_EMAIL_VALUE'] ?></a></div>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>
    </div>
<? } ?>