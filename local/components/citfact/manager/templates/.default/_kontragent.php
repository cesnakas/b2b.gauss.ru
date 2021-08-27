<?php
use Citfact\Tools\Tools;
?>
<div class="account-table__row" data-xml-id="<?=$kontragent['UF_XML_ID']?>">
    <div class="account-table__client">
        <div class="b-checkbox">
            <label for="agent-<?= $kontragent['ID'] ?>" class="b-checkbox__label">
                <input type="checkbox" class="b-checkbox__input" id="agent-<?= $kontragent['ID'] ?>"
                       data-type="excludeKontragent" data-id="<?= $kontragent['ID'] ?>"
                    <?= (isset($kontragent['EXCLUDE']) && $kontragent['EXCLUDE']) ? '' : 'checked'?>
                >
                <span class="b-checkbox__box">
                    <span class="b-checkbox__line b-checkbox__line--short"></span>
                    <span class="b-checkbox__line b-checkbox__line--long"></span>
                </span>
            </label>
        </div>
        <div class="account-table__number"><?=$id+1?></div>
        <a class="account-table__name" href="<?=$kontragent['URL']?>"><?=$kontragent['NAME']?></a>
    </div>
    <div class="account-table__check">
        <?=$kontragent['USE_PORTAL'] ? '<div class="account-table__checkmark"></div>' : ''?>
    </div>
    <div class="account-table__item">
        <?if($kontragent['PLAN'] == 0):?>
            <div class="account-table__value" data-plan-<?=$kontragent['ID']?>>
                <?=Tools::formatNumber($kontragent['AUTOPLAN']);?>
            </div>
            <div class="tooltip">
                <div class="tooltip__icon">
                    <svg class="i-icon">
                        <use xlink:href="#icon-tooltip-alert"></use>
                    </svg>
                </div>
                <div class="tooltip__text">
                    <div>Необходимо заполнить актуальное значение</div>
                </div>
            </div>
        <?else:?>
            <div class="account-table__value" data-plan-<?=$kontragent['ID']?>>
                <?=Tools::formatNumber($kontragent['PLAN']);?>
            </div>
        <?endif;?>
    </div>
    <div class="account-table__item" data-fact-<?=$kontragent['ID']?>>
        <?=Tools::formatNumber($kontragent['FACT']);?>
    </div>
    <div class="account-table__item account-table__item-mobile">
        <div class="account-table__mobile-title">План/Факт</div>
        <div class="account-table__value-wrapper" data-plan-fact-mobile-<?=$kontragent['ID']?>>
            <?if($kontragent['PLAN'] == 0):?>
                <div class="account-table__value">
                    <?=Tools::formatNumber($kontragent['AUTOPLAN']);?>
                </div>
                <div class="tooltip">
                    <div class="tooltip__icon">
                        <svg class="i-icon">
                            <use xlink:href="#icon-tooltip-alert"></use>
                        </svg>
                    </div>
                    <div class="tooltip__text">
                        <div>Необходимо заполнить актуальное значение</div>
                    </div>
                </div>
            <?else:?>
                <?=Tools::formatNumber($kontragent['PLAN']);?>
            <?endif;?>
            /
            <?=Tools::formatNumber($kontragent['FACT']);?>
        </div>
    </div>
    <div class="account-table__progress">
        <div class="account-table__mobile-title">% выполнения</div>
        <div class="account-table__progress-line">
            <div class="account-table__progress-thumb" data-percent-progress-<?=$kontragent['ID']?> style="width: <?=$kontragent['PERCENT'] <=100 ? $kontragent['PERCENT'] : 100?>%;"></div>
        </div>
        <div class="account-table__progress-percent" data-percent-<?=$kontragent['ID']?>><?=$kontragent['PERCENT']?>%</div>
    </div>
    <div class="account-table__debt">
        <div class="account-table__mobile-title">ПДЗ</div>
        <div class="account-table__value" data-pdz-<?=$kontragent['ID']?>>
            <?=Tools::formatNumber($kontragent['PDZ']);?>
        </div>
    </div>
</div>