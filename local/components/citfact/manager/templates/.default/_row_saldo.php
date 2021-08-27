<? use Citfact\Tools\Tools;

if ($saldo != 0) { ?>
    <div class="account-table__row account-table__saldo">
        <div class="account-table__client"><b>Остаток</b></div>
        <div class="account-table__check"></div>
        <div class="account-table__item">
            <div class="account-table__value"><b><?= Tools::formatNumber($saldo); ?></b></div>
        </div>
        <div class="account-table__item-mobile">
            <div class="account-table__mobile-title">Остаток</div>
            <div class="account-table__value"><b><?= Tools::formatNumber($saldo); ?></b></div>
        </div>
        <div class="account-table__item"></div>
        <div class="account-table__progress"></div>
        <div class="account-table__debt"></div>
    </div>
<? } ?>