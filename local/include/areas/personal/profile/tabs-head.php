<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['URL' => '/personal/profile/', 'ANCHOR' => 'Личные данные'],
    ['URL' => '/personal/profile/company/', 'ANCHOR' => 'Данные компании'],
    ['URL' => '/personal/profile/shipping-addresses/', 'ANCHOR' => 'Адреса доставки'],
];
?>
<div class="b-tabs-head">
    <? foreach ($pathList as $path) : ?>
        <a href="<?= $path['URL']; ?>" class="b-tabs-link<? if ($dir == $path['URL']): ?> active<? endif; ?>"><?= $path['ANCHOR']; ?></a>
    <? endforeach; ?>
</div>