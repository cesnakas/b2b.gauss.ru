<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['URL' => '/personal/documents/technical-documentation/', 'ANCHOR' => 'Техническая документация'],
    ['URL' => '/personal/documents/invoice/', 'ANCHOR' => 'Счет-фактура на аванс'],
    ['URL' => '/personal/documents/act-of-reconciliation/', 'ANCHOR' => 'Акт сверки'],
    ['URL' => '/personal/documents/forwarding-receipt/', 'ANCHOR' => 'Экспедиторская расписка'],
];
?>
<div class="b-tabs-head">
    <? foreach ($pathList as $path) : ?>
        <a href="<?= $path['URL']; ?>" class="b-tabs-link<? if ($dir == $path['URL']): ?> active<? endif; ?>"><?= $path['ANCHOR']; ?></a>
    <? endforeach; ?>
</div>