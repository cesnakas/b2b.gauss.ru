<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['URL' => '/personal/feedback/review/', 'ANCHOR' => 'Отзыв'],
    ['URL' => '/personal/feedback/suggestions/', 'ANCHOR' => 'Предложения по улучшению работы личного кабинета'],
    ['URL' => '/personal/feedback/claim/', 'ANCHOR' => 'Претензия'],
];
?>
<div class="b-tabs-head">
    <? foreach ($pathList as $path) : ?>
        <a href="<?= $path['URL']; ?>" class="b-tabs-link<? if ($dir == $path['URL']): ?> active<? endif; ?>"><?= $path['ANCHOR']; ?></a>
    <? endforeach; ?>
</div>