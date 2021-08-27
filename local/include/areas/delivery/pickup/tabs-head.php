<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['ANCHOR' => 'Офис'],
    ['ANCHOR' => 'Склад'],
];
?>
<div class="b-tabs-head" data-tab-header>
    <? $i = 1;
    foreach ($pathList as $path) : ?>
        <a href="javascript:void(0);" class="b-tabs-link<? if ($i == 1): ?> active<? endif; ?>"
           data-tab-btn="<?= $i ?>">
            <?= $path['ANCHOR']; ?>
        </a>
        <? $i++;
    endforeach; ?>
</div>