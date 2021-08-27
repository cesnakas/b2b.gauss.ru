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
/** @var MailBufferTemplate $component */

if (empty($arResult['ITEMS'])) {
    return;
}

$hasImages = false;
foreach ($arResult['ITEMS'] as $item) {
   if ($item['PICTURE']['SRC']) {
       $hasImages = true;
   }
}
?>


<table width="100%" style="font-size: 14px;border-spacing: 0;padding: 0 40px 50px 40px;">
    <tbody>
    <tr>
        <? if ($hasImages) { ?>
            <td style="
            padding-top: 13px;
            font-weight:bold;
            padding-bottom: 14px;
            border-top: 1px solid #CBD2DB;
            font-size: 14px;
            color: #2F3744;">Изображение</td>
        <? } ?>
        <td style="padding:0;padding-top: 13px;font-weight:bold;padding-bottom: 14px;border-top: 1px solid #CBD2DB;color: #2F3744;">Наименование</td>
        <td style="padding:0;padding-top: 13px;font-weight:bold;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;color: #2F3744;">Количество</td>
        <td style="padding:0;padding-top: 13px;font-weight:bold;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;color: #2F3744;">Стоимость</td>
    </tr>

    <?
    $amountItems = count($arResult['ITEMS']);
    $i = 0;

    foreach ($arResult['ITEMS'] as $item) {
        $i++;
        $withBorderBottom = $i === $amountItems; ?>
        <tr>
            <? if ($hasImages) { ?>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;border-bottom: 1px solid #CBD2DB;color: #2F3744;">
                    <? if ($item['PICTURE']['SRC']) { ?>
                        <a href="<?= $arResult['FULL_DIR']; ?><?= $item['DETAIL_PAGE_URL']; ?>" style="color:#2e6eb6;text-decoration:none" title="<?= $item['NAME']; ?>">
                            <img src="<?= $arResult['FULL_DIR']; ?><?= $item['PICTURE']['SRC']; ?>" title="<?= $item['NAME']; ?>" alt="<?= $item['NAME']; ?>">
                        </a>
                    <? } ?>
                </td>
            <? } ?>

            <td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;<?php echo $withBorderBottom ? 'border-bottom: 1px solid #CBD2DB;' : ''; ?>color: #2F3744;">
                <a href="<?= $arResult['FULL_DIR']; ?><?= $item['DETAIL_PAGE_URL']; ?>" style="color:#2e6eb6;text-decoration:none" title="<?= $item['NAME']; ?>">
                    <?= $item['NAME']; ?>
                </a>
            </td>
            <td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left:16px;border-top: 1px solid #CBD2DB;<?php echo $withBorderBottom ? 'border-bottom: 1px solid #CBD2DB;' : ''; ?>color: #2F3744;">
                <?= $item['BASKET']['QUANTITY']; ?>
            </td>
            <td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left:16px;border-top: 1px solid #CBD2DB;<?php echo $withBorderBottom ? 'border-bottom: 1px solid #CBD2DB;' : ''; ?>color: #2F3744;">
                <?= $item['BASKET']['FINAL_PRICE']; ?>
            </td>
        </tr>
    <? } ?>

    </tbody>
</table>