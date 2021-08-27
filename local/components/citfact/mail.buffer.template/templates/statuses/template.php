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
    return '';
}
?>

<table width="100%" style="font-size: 14px;border-spacing: 0;padding: 50px 40px;">
    <thead>
        <tr>
            <th style="padding:0;font-size: 22px;text-align: left;font-weight: 500;padding-bottom: 15px; color: #2F3744" colspan="2">Изменились статусы по отгрузке следующих товаров:</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:0;padding-top: 13px;font-weight:bold;padding-bottom: 14px;border-top: 1px solid #CBD2DB;font-size: 14px;color: #2F3744;">Наименование</td>
            <td style="padding:0;padding-top: 13px;font-weight:bold;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;color: #2F3744;">Количество</td>
            <td style="padding:0;padding-top: 13px;font-weight:bold;padding-bottom: 14px;padding-left: 16px;border-top: 1px solid #CBD2DB;color: #2F3744;">Статус</td>
        </tr>

        <?php foreach ($arResult['ITEMS'] as $item) {
            $i++;
            $withBorderBottom = $i === $amountItems; ?>
            <tr>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;border-top: 1px solid #CBD2DB;<?php echo $withBorderBottom ? 'border-bottom: 1px solid #CBD2DB;' : ''; ?>color: #2F3744;">
                    <?php echo $item['NAME']; ?>
                </td>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left:16px;border-top: 1px solid #CBD2DB;<?php echo $withBorderBottom ? 'border-bottom: 1px solid #CBD2DB;' : ''; ?>color: #2F3744;">
                    <?php echo $item['AMOUNT']; ?>
                </td>
                <td style="padding:0;padding-top: 13px;padding-bottom: 14px;padding-left:16px;border-top: 1px solid #CBD2DB;<?php echo $withBorderBottom ? 'border-bottom: 1px solid #CBD2DB;' : ''; ?>color: #2F3744;">
                    <?php echo $item['STATUS']; ?>
                </td>
            </tr>
        <?php } ?>

    </tbody>
</table>