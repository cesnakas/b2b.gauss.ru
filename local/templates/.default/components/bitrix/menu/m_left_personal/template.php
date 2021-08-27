<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult["ALL_ITEMS"]))
    return;

CJSCore::Init(); ?>

<div class="m-menu" data-m-menu="burger">
    <?
    foreach ($arResult['ALL_ITEMS'] as $key => $item) { ?>
        <a href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a>
    <? } ?>

    <a href="/catalog/" class="btn btn--transparent-l">Перейти в каталог</a>
</div>