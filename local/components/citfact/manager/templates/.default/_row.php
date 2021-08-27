<?php use Citfact\Tools\Tools;

$subManager['ALL_PERCENT'] = 0;
if ($subManager['ALL_PLAN'] != 0) {
    $subManager['ALL_PERCENT'] = round($subManager['ALL_FACT'] * 100 / $subManager['ALL_PLAN']);
}?>
<div class="account-table__row" data-xml-id="<?= $idManager ?>">
    <div data-toggle-btn data-toggle-manager-<?= $subManager['ID'] ?> data-manager="<?= $subManager['ID'] ?>"></div>
    <div class="account-table__agent <?=$firstLevel ? 'account-table__agent--main' : ''?>">
        <div class="b-checkbox">
            <label for="manager-<?= $subManager['ID'] ?>" class="b-checkbox__label">
                <input type="checkbox" class="b-checkbox__input" id="manager-<?= $subManager['ID'] ?>"
                       data-type="excludeManager" data-id="<?= $subManager['ID'] ?>"
                       <?= (isset($subManager['EXCLUDE']) && $subManager['EXCLUDE']) ? '' : 'checked'?>
                >
                <span class="b-checkbox__box">
                    <span class="b-checkbox__line b-checkbox__line--short"></span>
                    <span class="b-checkbox__line b-checkbox__line--long"></span>
                </span>
            </label>
        </div>
        <a class="account-table__name"><?=$subManager['NAME']?></a>
    </div>
    <div class="account-table__check"></div>
    <div class="account-table__item">
        <?if($subManager['ALL_PLAN'] == 0):?>
            <div class="account-table__value" data-mplan-<?=$subManager['ID']?>>
                <?=Tools::formatNumber($subManager['ALL_AUTOPLAN']);?>
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
            <div class="account-table__value" data-mplan-<?=$subManager['ID']?>>
                <?=Tools::formatNumber($subManager['ALL_PLAN']);?>
            </div>
        <?endif;?>
    </div>
    <div class="account-table__item" data-mfact-<?=$subManager['ID']?>>
        <?=Tools::formatNumber($subManager['ALL_FACT']);?>
    </div>
    <div class="account-table__item account-table__item-mobile">
        <div class="account-table__mobile-title">План/Факт</div>
        <div class="account-table__value-wrapper" data-mplan-fact-mobile-<?=$subManager['ID']?>>
            <?if($subManager['ALL_PLAN'] == 0):?>
                <div class="account-table__value">
                    <?=Tools::formatNumber($subManager['ALL_AUTOPLAN']);?>
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
                <?=Tools::formatNumber($subManager['ALL_PLAN']);?>
            <?endif;?>
            /
            <?=Tools::formatNumber($subManager['ALL_FACT']);?>
        </div>
    </div>
    <div class="account-table__progress">
        <div class="account-table__mobile-title">% выполнения</div>
        <div class="account-table__progress-line">
            <div class="account-table__progress-thumb" data-mpercent-progress-<?=$subManager['ID']?> style="width: <?=$subManager['ALL_PERCENT'] <=100 ? $subManager['ALL_PERCENT'] : 100?>%;"></div>
        </div>
        <div class="account-table__progress-percent" data-mpercent-<?=$subManager['ID']?>><?=$subManager['ALL_PERCENT']?>%</div>
    </div>
    <div class="account-table__debt">
        <div class="account-table__mobile-title">ПДЗ</div>
        <div class="account-table__value" data-mpdz-<?=$subManager['ID']?>>
            <?=Tools::formatNumber($subManager['ALL_PDZ']);?>
        </div>
    </div>
</div>