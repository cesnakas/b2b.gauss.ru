<?php
/**
 * @var MainProfile2Component $component
 */
$paddingLeft += 30;
$levelStructure++;

use Citfact\Tools\Tools; ?>
    <!--header-->
    <div class="account-table__wrap" data-toggle-wrap="">
        <!-- Верхний уровень -->
        <!-- Выпадающий список 1 уровня -->
        <div class="account-table__items">
            <div class="account-table__wrap" data-toggle-wrap="">
                <? if ($item->id != $component->planDataProvider->getCurManagerId()) { ?>
                    <div class="account-table__row manager" data-xml-id="<?= $item->info['UF_XML_ID'] ?>"
                         data-id-manager="<?= $item->id ?>"
                         style="padding-left: <?= $paddingLeft ?>px;">
                        <div data-toggle-btn data-toggle-manager-<?= $item->id ?> data-manager="<?= $item->id ?>"></div>
                        <div class="account-table__agent account-table__agent--main">
                            <div class="b-checkbox">
                                <label for="manager-<?= $item->id ?>" class="b-checkbox__label">
                                    <input type="checkbox" class="b-checkbox__input" id="manager-<?= $item->id ?>"
                                           data-type="excludeManager" data-id="<?= $item->id ?>"
                                        <?= ($item->isExclude()) ? '' : 'checked' ?>
                                    >
                                    <span class="b-checkbox__box">
                                        <span class="b-checkbox__line b-checkbox__line--short"></span>
                                        <span class="b-checkbox__line b-checkbox__line--long"></span>
                                    </span>
                                </label>
                            </div>
                            <a class="account-table__name"><?= $item->getName() ?></a>
                        </div>
                        <div class="account-table__check"></div>
                        <div class="account-table__item">
                            <div class="account-table__value" data-mplan-<?= $item->id ?>="">
                                <?= Tools::formatNumber($item->getPlan()) ?>
                            </div>
                            <? if ($item->isCalculated() && $item->getPlan()) {
                                require('_tooltip_calculated.php');
                            } ?>
                        </div>
                        <div class="account-table__item" data-mfact-<?= $item->id ?>="">
                            <?= Tools::formatNumber($item->getFact()) ?>
                        </div>
                        <div class="account-table__item account-table__item-mobile">
                            <div class="account-table__mobile-title">План/Факт</div>
                            <div class="account-table__value-wrapper" data-mplan-fact-mobile-<?= $item->id ?>="">
                                <?= Tools::formatNumber($item->getPlan()) ?> / 0
                                <? if ($item->isCalculated() && $item->getPlan()) {
                                    require('_tooltip_calculated.php');
                                } ?>
                            </div>
                        </div>
                        <div class="account-table__progress">
                            <div class="account-table__mobile-title">% выполнения</div>
                            <div class="account-table__progress-line">
                                <div class="account-table__progress-thumb" data-mpercent-progress-<?= $item->id ?>=""
                                     style="width: <?= $item->getPercentFact() <= 100 ? $item->getPercentFact() : 100 ?>%;
                                             background-color: <?= $item->getColorProgressLine() ?> "></div>
                            </div>
                            <div class="account-table__progress-percent"
                                 data-mpercent-<?= $item->id ?>=""><?= round($item->getPercentFact()) ?>%
                            </div>
                        </div>
                        <div class="account-table__debt">
                            <div class="account-table__mobile-title">ПДЗ</div>
                            <div class="account-table__value" data-mpdz-<?= $item->id ?>="">
                                <?= Tools::formatNumber($item->getPdz()) ?>
                            </div>
                        </div>
                    </div>
                <? } ?>
                <div class="account-table__items" data-toggle-list=""
                    <?= ($levelStructure == 1) ? 'style="display: block;"' : '' ?>>
                    <div class="account-table__wrap account-table__clients" data-toggle-wrap="">
                        <div class="account-table__row account-table__saldo" data-saldo-wrap-id-<?= $item->id ?>=""
                            <?= ($item->getSaldo() == 0 || $arResult['IS_SEARCH_STRING']) ? 'style="display: none"' : '' ?>>
                            <div class="account-table__client"><b>Остаток</b></div>
                            <div class="account-table__check"></div>
                            <div class="account-table__item">
                                <div class="account-table__value">
                                    <b data-saldo-<?= $item->id ?>=''><?= Tools::formatNumber($item->getSaldo()) ?></b>
                                </div>
                            </div>
                            <div class="account-table__item-mobile">
                                <div class="account-table__mobile-title">Остаток</div>
                                <div class="account-table__value">
                                    <b data-saldo-<?= $item->id ?>=''><?= Tools::formatNumber($item->getSaldo()) ?></b>
                                </div>
                            </div>
                            <div class="account-table__item"></div>
                            <div class="account-table__progress"></div>
                            <div class="account-table__debt"></div>
                        </div>
                        <? if ($item->kontragents) { ?>
                            <div class="account-table__filters account-table__filters--client">
                                <div class="account-table__btn--client filter__client" data-toggle-manager-client-<?= $item->id ?> data-toggle-btn data-manager="client-<?= $item->id ?>">
                                    Клиент
                                </div>
                            </div>
                        <? }
                        $kontragentNumber = 0;
                        ?>
                        <div data-toggle-list>
                            <?
                            foreach ($item->kontragents as $kontragent) {
                                $kontragentNumber++;
                                ?>
                                <? require('_kontragent.php') ?>
                            <? } ?>
                        </div>

                    </div>
                        <? foreach ($item->sub as $item) { ?>
                            <? require('_row.php') ?>
                        <? } ?>

                </div>
            </div>
        </div>
    </div>
<?php
$paddingLeft -= 30;
$levelStructure--;
?>