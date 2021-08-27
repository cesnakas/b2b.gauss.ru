<?php

/*
 * This file is part of the Studio Fact package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>

<div class="catalog__sort">
    <? if (!empty($arResult['SORT'])): ?>
        <div class="catalog__sort-label">
            <?= GetMessage('SORT_BY') ?>
        </div>
        <div class="catalog__sort-items">
            <? foreach ($arResult['SORT'] as $arSort): ?>
                <? if ($arSort['HIDE']) {
                    continue;
                } ?>
                <? $class = $arSort['ACTIVE'] == 'Y' && $arSort['ORDER'] == 'asc' ? '--up' : ''; ?>
                <a href="<?= $arSort['URL'] ?>"
                   title="<?= $arSort['NAME'] ?>"
                   class="catalog__sort-item catalog__sort-item<?= $class ?>">
                    <span><?= $arSort['NAME'] ?></span>
                </a>
            <? endforeach; ?>
        </div>
    <? endif ?>
    <? if ($arResult['VIEW']): ?>
        <div class="catalog-sort__item catalog-sort__view">
            <div class="catalog-sort__head">
                <h4><?= GetMessage('catalog') ?></h4>
            </div>
            <? foreach ($arResult['VIEW'] as $arView): ?>
                <? $active = $arView['ACTIVE'] == 'Y' ? ' active' : ''; ?>
                <a href="<?= $arView['URL'] ?>"
                   title="<?= $arView['URL'] ?>"
                   class="i-icon <?= $arView['CLASS'] ?><?= $active ?>"></a>
            <? endforeach; ?>
        </div>
    <? endif ?>
</div>