<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(false);
$year = $arResult['year'];
$factDataByMonths = array_values($arResult['FACT_DATA_BY_MONTHS'][$year]);

$planDataByQuarters = array_values($arResult['PLAN_DATA_BY_QUARTERS'][$year]);
$factDataByQuarters = array_values($arResult['FACT_DATA_BY_QUARTERS'][$year]);

$planDataForYear = $arResult['PLAN_DATA_FOR_YEAR'][$year];
$factDataForYear = $arResult['FACT_DATA_FOR_YEAR'][$year];

$yearsFact = array_keys($arResult['PLAN_DATA_FOR_YEAR']);
$yearsPlan = array_keys($arResult['FACT_DATA_FOR_YEAR']);

$years = [];
if (!empty($yearsFact) && !(empty($yearsPlan))) {
    $years = array_merge($yearsFact, $yearsPlan);
} else if (!empty($yearsFact)) {
    $years = $yearsFact;
} else if (!empty($yearsPlan)) {
    $years = $yearsPlan;
}
$years = array_unique($years);
rsort($years);

$isNotEmptyData = (!empty($factDataByMonths) || !empty($planDataByQuarters) || !empty($factDataByQuarters) || !empty($factDataForYear) || !empty($planDataForYear));
?>
<div class="lk-chart">
    <div class="lk__section">
        <?if(empty($arParams['XML_ID'])):?>
        <p>Выберите юр.лицо от которого будет оформлен заказ. <br>
            Пользователя можно изменять в разделе "Персональные данные" и "Корзина".</p>
        <form action="" class="b-form">
            <? $APPLICATION->IncludeComponent(
                "citfact:contragent.list",
                "personal",
                Array(
                    'LABEL_NOT_ITEMS' => 'Юридические лица не созданы'
                )
            ); ?>
        </form>
        <?endif;?>
        <p>Бонусная программа — один из видов программ поощрения покупателей, При правильном подходе, является
            механизмом по первичному привлечению клиентов и сбору информации, которая
            поможет узнать портреты групп покупателей, определить их ценность и уровень возможных маркетинговых
            вложений в каждую из этих групп.</p>
    </div>


    <? if ($isNotEmptyData) { ?>
        <div class="lk__section">
            <div class="lk-chart__top">
                <script>
                    window.dataChart = [
                        [
                            [],  /* план */
                            <?=json_encode($factDataByMonths)?>, /* факт */
                            ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]
                        ],
                        [
                            <?=json_encode($planDataByQuarters)?>, /* план */
                            <?=json_encode($factDataByQuarters)?>, /* факт */
                            ["1", "2", "3", "4"]
                        ],
                        [
                            [<?=$planDataForYear?>], /* план */
                            [<?=$factDataForYear?>], /* факт */
                            ["<?=$year?>"]
                        ]
                    ];
                </script>
                <?
                $activeTab = $arResult['active_tab'];
                if ($activeTab === false) {
                    if (!empty($factDataByMonths)) {
                        $activeTab = 0;
                    } elseif (!empty($factDataByQuarters) || !empty($planDataByQuarters)) {
                        $activeTab = 1;
                    } elseif (!empty($planDataForYear) || !empty($factDataForYear)) {
                        $activeTab = 2;
                    }
                }
                ?>
                <div class="lk-chart__links">
                    <? if (!empty($factDataByMonths)) { ?>
                        <a href="javascript:void(0);" title="Месяц" class="lk-chart__link <?= $activeTab == 0 ? 'active' : '' ?>"
                           data-chart-link="0">Месяц
                        </a>
                    <? } ?>
                    <? if (!empty($factDataByQuarters) || !empty($planDataByQuarters)) { ?>
                        <a href="javascript:void(0);" title="Квартал" class="lk-chart__link <?= $activeTab == 1 ? 'active' : '' ?>"
                           data-chart-link="1">Квартал
                        </a>
                    <? } ?>
                    <? if (!empty($planDataForYear) || !empty($factDataForYear)) { ?>
                        <a href="javascript:void(0);" title="Год" class="lk-chart__link <?= $activeTab == 2 ? 'active' : '' ?>"
                           data-chart-link="2">Год
                        </a>
                    <? } ?>
                </div>

                <div class="lk-chart__legend lk-chart__legend--plan hidden">
                    <span>План</span>
                </div>
                <div class="lk-chart__legend lk-chart__legend--fact hidden">
                    <span>Факт</span>
                </div>
                <?
                $currentYear = date('Y');
                ?>
                <form id="form_plan_fact" method="post">
                    <input type="hidden" name="active_tab" id="active_tab" value="0">
                    <select name="year" id="year" onchange="reloadPlanFactData(this)">
                        <? foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <? if ($year == $arResult['year']): ?> selected <? endif; ?>>
                                <?= $year ?>
                            </option>
                        <? endforeach ?>
                    </select>
                </form>
            </div>
            <canvas data-chart></canvas>
        </div>
    <? } ?>
</div>