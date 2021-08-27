<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['URL' => '/shipping-payment/company/', 'ANCHOR' => 'Доставка транспортной компанией'],
    ['URL' => '/shipping-payment/courier/', 'ANCHOR' => 'Собственная транспортная доставка'],
    ['URL' => '/shipping-payment/pickup/', 'ANCHOR' => 'Самовывоз'],
];
?>
<div class="b-tabs-head">
    <? foreach ($pathList as $path) : ?>
        <a href="<?= $path['URL']; ?>" class="b-tabs-link<? if ($dir == $path['URL']): ?> active<? endif; ?>"><?= $path['ANCHOR']; ?></a>
    <? endforeach; ?>
</div>
