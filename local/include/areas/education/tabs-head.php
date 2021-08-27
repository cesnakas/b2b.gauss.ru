<?php
$dir = $APPLICATION->GetCurDir();
$pathList = [
    ['URL' => '/education/presentation/', 'ANCHOR' => 'Наши возможности'],
    ['URL' => '/education/tests/', 'ANCHOR' => 'Самостоятельное обучение'],
    ['URL' => '/education/educational_videos/', 'ANCHOR' => 'Обучающие видео'],
    ['URL' => '/education/webinars/', 'ANCHOR' => 'Вебинары'],
    ['URL' => '/education/gauss-academy/', 'ANCHOR' => 'Академия Gauss'],
]
?>
<div class="b-tabs-head" data-tab-header>
    <? foreach ($pathList as $path) : ?>
        <a href="<?= $path['URL']; ?>"
           class="b-tabs-link <? echo ($dir == $path['URL']) ? 'active' : '' ?>" title="<?= $path['ANCHOR']; ?>"><?= $path['ANCHOR']; ?></a>
    <? endforeach; ?>
</div>