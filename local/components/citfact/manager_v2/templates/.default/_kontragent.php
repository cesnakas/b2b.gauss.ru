<?php
/**
 * @var \Plan\Models\KontragentModel $kontragent
 * @var int $kontragentNumber
 */

use Citfact\Tools\Tools; ?>
<div class="account-table__row kontragent" data-xml-id="<?= $kontragent->info['UF_XML_ID'] ?>"
     data-is-exclude="<?= $kontragent->isExclude() ?>"
     data-id-kontragent="<?= $item->id ?>"
     style="padding-left: <?= $paddingLeft ?>px;">
    <div class="account-table__client">
        <div class="b-checkbox">
            <label for="agent-<?= $kontragent->id ?>" class="b-checkbox__label">
                <input type="checkbox" class="b-checkbox__input" id="agent-<?= $kontragent->id ?>"
                       data-type="excludeKontragent" data-id="<?= $kontragent->id ?>"
                    <?= ($kontragent->isExclude()) ? '' : 'checked' ?>
                >
                <span class="b-checkbox__box">
                    <span class="b-checkbox__line b-checkbox__line--short"></span>
                    <span class="b-checkbox__line b-checkbox__line--long"></span>
                </span>
            </label>
        </div>
        <div class="account-table__number"><?= $kontragentNumber ?></div>
        <a class="account-table__name"
           href="<?= $kontragent->getUrl() ?>"><?= $kontragent->getName() ?></a>
    </div>
    <div class="account-table__check">
        <? if ($kontragent->isUsePortal()) { ?>
            <div class="account-table__checkmark"></div>
        <? } ?>
    </div>
    <div class="account-table__item">
        <div class="account-table__value" data-plan-<?= $kontragent->id ?>="">
            <?= Tools::formatNumber($kontragent->getPlan()) ?>
        </div>
        <? if ($kontragent->isCalculated() && $kontragent->getPlan()) {
            require('_tooltip_calculated.php');
        } ?>
    </div>
    <div class="account-table__item" data-fact-<?= $kontragent->id ?>="">
        <?= Tools::formatNumber($kontragent->getFact()) ?>
    </div>
    <div class="account-table__item account-table__item-mobile">
        <div class="account-table__mobile-title">План/Факт</div>
        <div class="account-table__value-wrapper" data-plan-fact-mobile-<?= $kontragent->id ?>="">
            <div class="account-table__value">
                <?= Tools::formatNumber($kontragent->getPlan()) ?>
            </div>
            <? if ($kontragent->isCalculated() && $kontragent->getPlan()) {
                require('_tooltip_calculated.php');
            } ?> / <?= Tools::formatNumber($kontragent->getFact()) ?>
        </div>
    </div>
    <div class="account-table__progress">
        <div class="account-table__mobile-title">% выполнения</div>
        <div class="account-table__progress-line">
            <div class="account-table__progress-thumb" data-percent-progress-<?= $kontragent->id ?>=""
                 style="width: <?= $kontragent->getPercentFact() <= 100 ? $kontragent->getPercentFact() : 100 ?>%;
                         background-color: <?= $kontragent->getColorProgressLine() ?> "></div>
        </div>
        <div class="account-table__progress-percent" data-percent-<?= $kontragent->id ?>="">
            <?= round($kontragent->getPercentFact()) ?>%
        </div>
    </div>
    <div class="account-table__debt">
        <div class="account-table__mobile-title">ПДЗ</div>
        <div class="account-table__value" data-pdz-<?= $kontragent->id ?>="">
            <?= Tools::formatNumber($kontragent->getPdz()) ?>
        </div>
    </div>
</div>