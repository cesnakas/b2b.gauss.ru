<?php

use Citfact\Tools\Tools;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);
?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="account">
    <div class="account-summary">
        <div class="account__charts">
            <div class="account__graph">
                <div class="account__tab-wrapper">
                    <a href="<?=$arResult['CURRENT']['MONTH']?>"
                       class="choice-item js-account-tab <?=$APPLICATION->GetCurUri("", false) == $arResult['CURRENT']['MONTH'] || $arResult['SELECTED_PERIOD'] == 'month' ? "is-active" : ""?>"
                       data-tab-name="tab-month">
                       Месяц
                    </a>
                    <a href="<?=$arResult['CURRENT']['QUARTER']?>"
                       class="choice-item js-account-tab <?=$APPLICATION->GetCurUri("", false) == $arResult['CURRENT']['QUARTER'] || $arResult['SELECTED_PERIOD'] == 'quarter' ? "is-active" : ""?>"
                       data-tab-name="tab-quarter">
                       Квартал
                    </a>
                    <a href="<?=$arResult['CURRENT']['YEAR']?>"
                       class="choice-item js-account-tab <?=$APPLICATION->GetCurUri("", false) == $arResult['CURRENT']['YEAR'] || $arResult['SELECTED_PERIOD'] == 'year' ? "is-active" : ""?>"
                       data-tab-name="tab-year">
                       Год
                    </a>
                </div>
                <div class="account__period js-year">
                    <span class="account__year js-date-picker">Период: <?=$arResult['PERIOD_DESC']?></span>
                    <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" style="display:none;" />
                </div>
            </div>
            <div class="account__chart-wrapper">
                <div class="account__chart-item js-account-chart-item">
                    <div class="account__chart">
                        <canvas id="myChartRed" style="height:150px; width:150px" data-current="<?=$arResult['ALL_PDZ']?>"
                                data-max="<?=$arResult['ALL_DZ']?>"></canvas>
                        <div class="account__chart-percent"></div>
                    </div>
                    <div class="chart__description">
                        <div class="chart__plan-fact" data-pdz><?=Tools::formatNumber($arResult['ALL_PDZ']);?></div>
                        <div class="chart__plan-fact" data-dz> / <?=Tools::formatNumber($arResult['ALL_DZ']);?></div>
                        <div class="chart__about">
                            ПДЗ/ДЗ
                            <? if($arResult['DEFAULT_PERIOD']){ ?>
                                <div class="tooltip">
                                    <div class="tooltip__icon">
                                        <svg class="i-icon">
                                            <use xlink:href="#icon-tooltip-alert"></use>
                                        </svg>
                                    </div>
                                    <div class="tooltip__text">
                                        Данные по ПДЗ/ДЗ на текущий момент. Воспользуйтесь фильтром
                                        для просмотра данных за определенный период
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="account__chart-item js-account-chart-item">
                    <div class="account__chart">
                        <canvas id="myChartGreen" style="height:150px; width:150px" data-current="<?=$arResult['ALL_FACT']?>"
                                data-max="<?=$arResult['ALL_PLAN']?>"></canvas>
                        <div class="account__chart-percent"></div>
                    </div>
                    <div class="chart__description">
                        <p class="chart__plan-fact chart__plan-fact--green" data-fact><?=Tools::formatNumber($arResult['ALL_FACT']);?></p>
                        <p class="chart__plan-fact" data-plan> / <?=Tools::formatNumber($arResult['ALL_PLAN']);?></p>
                        <p class="chart__about">Факт/План</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="account__options">
            <form class="b-form" id="form_plan_fact">
                <? if (count($arResult['USER_MANAGERS'][0]) > 0): ?>
                    <div class="b-form__item account__select tab-year js-tab-content" data-f-item>
                        <select class="select--gray"
                                onchange="reloadPlanFactData(this);"
                                name="director"
                                id="director" data-f-field>
                            <option value="">Руководитель</option>
                            <? foreach ($arResult['USER_MANAGERS'][0] as $manager): ?>
                                <option value="<?= $manager['ID'] ?>" <?= $manager['ID'] == $arResult['DIRECTOR_FILTER'] ? 'selected' : '' ?>>
                                    <?= $manager['UF_NAME'] ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                <? endif; ?>
                <? if (count($arResult['USER_MANAGERS'][1]) > 0): ?>
                    <div class="b-form__item account__select tab-year js-tab-content" data-f-item>
                        <select class="select--gray"
                                onchange="reloadPlanFactData(this);"
                                name="managers"
                                id="managers" data-f-field>
                            <option value="">Менеджер</option>
                            <? foreach ($arResult['USER_MANAGERS'][1] as $manager): ?>
                                <option value="<?= $manager['ID'] ?>" <?= $manager['ID'] == $arResult['MANAGER_FILTER'] ? 'selected' : '' ?>>
                                    <?= $manager['UF_NAME'] ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                <? endif; ?>
                <? if (count($arResult['USER_MANAGERS'][2]) > 0): ?>
                    <div class="b-form__item account__select tab-year js-tab-content" data-f-item>
                        <select class="select--gray"
                                onchange="reloadPlanFactData(this);"
                                name="managers-tm"
                                id="managers-tm" data-f-field>
                            <option value="">ТМ</option>
                            <? foreach ($arResult['USER_MANAGERS'][2] as $manager): ?>
                                <option value="<?= $manager['ID'] ?>" <?= $manager['ID'] == $arResult['MANAGER_TM_FILTER'] ? 'selected' : '' ?>>
                                    <?= $manager['UF_NAME'] ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                <? endif; ?>
                <? if (count($arResult['USER_MANAGERS']) > 0): ?>
                    <? $setFilter = !empty($arResult['DIRECTOR_FILTER']) || !empty($arResult['MANAGER_FILTER']) || !empty($arResult['MANAGER_TM_FILTER']);?>
                    <div class="b-form__bottom">
                        <a href="<?=$APPLICATION->GetCurPage(false)?>" class="btn btn--transparent" <?=$setFilter ? '' : 'style="opacity: 0.5;pointer-events: none;"'?>>Сбросить</a>
                        <button class="btn btn--transparent" type="submit" name="save" value="" data-agree-submit="WEB_FORM_AJAX">Применить</button>
                    </div>
                <? endif; ?>
                <input type="hidden"
                       id="period"
                       name="period"
                       value="<?=$arParams['PERIOD'] ?? 'month_' . date('m') . '_' . date('Y')?>">
                <input type="hidden"
                       name="q"
                       value="<?= str_replace('"', '', $_REQUEST['q']) ?>">
            </form>
        </div>
    </div>
    <div class="account__filterz js-mobile-filter">
        <div class="plan-filter b-form__item account__select" data-f-item>
            <label for="mobile-filter"></label>
            <select class="js-plan-filter select--white" name="graph" id="mobile-filter" data-f-item>
                <?if ($_REQUEST['sort'] == 'percent' && $_REQUEST['dir'] == 'desc') {?>
                    <option class="js-mobile-options" value="percent_asc" selected>По % выполнения (по возрастанию)</option>
                <?} else {?>
                    <option class="js-mobile-options" value="percent_desc">По % выполнения (по убыванию)</option>
                <?}
                if ($_REQUEST['sort'] == 'plan' && $_REQUEST['dir'] == 'desc') {?>
                    <option class="js-mobile-options" value="plan_asc" selected>По плану (по возрастанию)</option>
                <?} else {?>
                    <option class="js-mobile-options" value="plan_desc">По плану (по убыванию)</option>
                <?}
                if ($_REQUEST['sort'] == 'fact' && $_REQUEST['dir'] == 'desc') {?>
                    <option class="js-mobile-options" value="fact_asc" selected>По факту (по возрастанию)</option>
                <?} else {?>
                    <option class="js-mobile-options" value="fact_desc">По факту (по убыванию)</option>
                <?}?>
            </select>
        </div>
    </div>
    <div class="account-table">

        <div class="account-table-header">
            <form class="b-form account-search" data-f-item action="<?= $APPLICATION->GetCurUri("", false) ?>" method="get">
                <div class="account-search__item">
                    <div class="account-search__label" data-f-label>
                        <span class="account-search__label-full">Поиск по Клиентам</span>
                        <span class="account-search__label-short">Клиент</span>
                    </div>
                    <input type="text"
                           name="q"
                           id="client"
                           maxlength="50"
                           autocomplete="off"
                           data-f-field
                           value="<?= str_replace('"', '', $_REQUEST['q']) ?>">
                    <button type="submit">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-search'/>
                        </svg>
                    </button>
                    <button type="submit" name="ACTION_CLEAR" value="clear" class="clear" id="clear">
                        <span class="plus plus--cross"></span>
                    </button>
                </div>
            </form>
            <div class="account-table__filters">
                <div class="filter__portal sort_table <?=$_REQUEST['sort'] == 'portal' && $_REQUEST['dir'] == 'asc' ? 'is-active' : ''?>" data-sort="portal" data-direction="<?=$_REQUEST['sort'] == 'portal' && $_REQUEST['dir'] == 'desc' ? 'asc' : 'desc'?>">Портал</div>
                <div class="filter__plan sort_table <?=$_REQUEST['sort'] == 'plan' && $_REQUEST['dir'] == 'asc' ? 'is-active' : ''?>" data-sort="plan" data-direction="<?=$_REQUEST['sort'] == 'plan' && $_REQUEST['dir'] == 'desc' ? 'asc' : 'desc'?>">План</div>
                <div class="filter__fact sort_table <?=$_REQUEST['sort'] == 'fact' && $_REQUEST['dir'] == 'asc' ? 'is-active' : ''?>" data-sort="fact" data-direction="<?=$_REQUEST['sort'] == 'fact' && $_REQUEST['dir'] == 'desc' ? 'asc' : 'desc'?>">Факт</div>
                <div class="filter__progress sort_table <?=$_REQUEST['sort'] == 'percent' && $_REQUEST['dir'] == 'asc' ? 'is-active' : ''?>" data-sort="percent" data-direction="<?=$_REQUEST['sort'] == 'percent' && $_REQUEST['dir'] == 'desc' ? 'asc' : 'desc'?>">% выполнения</div>
                <div class="filter__debt sort_table <?=$_REQUEST['sort'] == 'pdz' && $_REQUEST['dir'] == 'asc' ? 'is-active' : ''?>" data-sort="pdz" data-direction="<?=$_REQUEST['sort'] == 'pdz' && $_REQUEST['dir'] == 'desc' ? 'asc' : 'desc'?>">ПДЗ</div>
            </div>
        </div>
        <!--header-->
        <? require_once('kontragents.php');?>
    </div>
</div>