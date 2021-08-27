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
<? if ($component->isAjax == true) {
    $GLOBALS['APPLICATION']->RestartBuffer();
} ?>
<div class="lk-main">
    <div class="lk-main__columns">
        <div class="lk-main__column">
            <div class="lk-main__item">
                <div class="lk-main__head">
                    <div class="lk-main__title">
                        Личная информация
                    </div>
                </div>
                <div class="lk-main__params">
                    <div class="lk-main__param">
                        <div>Название компании</div>
                        <div>ООО "Емэйл Маркетинг Монтаж"</div>
                    </div>
                    <div class="lk-main__param">
                        <div>Ф.И.О</div>
                        <div>Мирославский Артур Владимирович</div>
                    </div>
                    <div class="lk-main__param">
                        <div>Телефон</div>
                        <div>+7 (000) 000-00-00</div>
                    </div>
                    <div class="lk-main__param">
                        <div>E-mail</div>
                        <div><a href="mailto:mail@gauss.ru">mail@gauss.ru</a></div>
                    </div>
                </div>
            </div>
            <div class="lk-main__item">
                <div class="lk-main__head">
                    <div class="lk-main__title">
                        Заказы клиентов
                    </div>
                    <a href="#" class="link-underline" title="Подробнее">Подробнее</a>
                </div>
                <div class="lk-main__params">
                    <div class="lk-main__param">
                        <div>Заказов в работе:</div>
                        <div><a href="#" title="<?= $arParams['COUNT_ORDERS'] ?>">10</a></div>
                    </div>
                </div>
            </div>
            <div class="lk-main__item">
                <div class="lk-main__head">
                    <div class="lk-main__title">
                        Дебиторская задолженность
                    </div>
                    <a href="#" class="link-underline" title="Подробнее">Подробнее</a>
                </div>
                <div class="debmoney">
                    <b>1 000 000 руб.</b>
                </div>
                <div class="lk-main__text">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "",
                        [
                            "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                            "PATH" => '/local/include/areas/personal/orders-text.php',    // Путь к файлу области
                        ],
                        false
                    ); ?>
                </div>
            </div>
        </div>
        <div class="lk-main__column">
            <div class="lk-main__item">
                <div class="lk-main__head">
                    <div class="lk-main__title">
                        План-факт
                    </div>
                </div>
                <? if ($isNotEmptyData) { ?>
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
    </div>
</div>

<? if ($component->isAjax === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
} ?>
