<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['URL' => '/marketing-support/trading-equipment-pos-materials/', 'ANCHOR' => 'Торговое оборудование и POS материалы'],
    ['URL' => '/marketing-support/customized-solutions/', 'ANCHOR' => 'Индивидуальные решения'],
    ['URL' => '/marketing-support/souvenirs/', 'ANCHOR' => 'Сувенирная продукция'],
    ['URL' => '/marketing-support/promotional-materials/', 'ANCHOR' => 'Презентации о продукте'],
    ['URL' => '/marketing-support/katalogi-i-listovki/', 'ANCHOR' => 'Каталоги и листовки'],
]
?>
<div class="b-tabs-head">
    <? foreach ($pathList as $path) : ?>
        <?if($path['ANCHOR'] == "Презентации о продукте"):?>
            <a href="<?= $path['URL']; ?>" class="b-tabs-link<? if ($dir == $path['URL']): ?> active<? endif; ?>"><?= $path['ANCHOR']; ?></a>
        <?endif;?>
    <? endforeach; ?>
    <? foreach ($pathList as $path) : ?>
        <?if(!($path['ANCHOR'] == "Презентации о продукте")):?>
            <a href="<?= $path['URL']; ?>" class="b-tabs-link<? if ($dir == $path['URL']): ?> active<? endif; ?>"><?= $path['ANCHOR']; ?></a>
        <?endif;?>
    <? endforeach; ?>
</div>