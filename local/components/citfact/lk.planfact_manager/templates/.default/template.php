<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(false);
?>

<div class="lk__section lk__section--manager" data-tab-group>
    <div class="b-tabs-head" data-tab-header>
        <a class="b-tabs-link active"
           href="javascript:void(0)"
           data-tab-btn="1">
            План контрагентов
        </a>
        <a class="b-tabs-link"
           href="javascript:void(0)"
           data-tab-btn="2">
            План менеджера
        </a>
    </div>

    <div data-tab-content>
        <div class="b-tabs__item active" data-tab-body="1">
            <form id="form_plan_fact" action="<?= $APPLICATION->GetCurUri("", false) ?>" method="post" class="b-form"
                  data-form-validation>

                <? if ($arResult['PLAN_FACT_SAVE']): ?>
                    <? if ($arResult['PLAN_FACT_SAVE'] === 'SUCCESS'): ?>
                        <div class="b-form__item success">
                        <span class="b-form__text">
                            Данные сохранены для выбранного юрлица!
                        </span>
                        </div>
                    <? else: ?>
                        <div class="b-form__item error">
                        <span class="b-form__text">
                            <?= $arResult['ERROR']; ?>
                        </span>
                        </div>
                    <? endif ?>
                <? endif ?>
                <p>На данной странице Вам нужно заполнить план продаж для каждого юр.&nbsp;лица на каждый месяц выбранного года. На основании
                    этих данных будет рассчитан квартальный и годовой план продаж.</p>
                <p>На основании этих данных будет рассчитана информация, отображаемая на странице ЛК Менеджера. </p>
                <p>Для внесения данных выберите юридическое лицо, по которому будет производится редактирование плана, выберите нужный год,
                    заполните план по каждому месяцу, далее сохраните введенные данные, нажав кнопку "Добавить".</p>

                <?php

                $dir = $APPLICATION->GetCurDir();
                $pos = strpos($dir, '/personal/');
                $isProfile = ($pos !== false) ? true : false; ?>
                <? if (count($arResult['USER_MANAGERS']) > 0): ?>
                <div id="manager_list_default" class="b-form__item b-form__item--select" data-f-item >
                    <span class="b-form__label" data-f-label>Менеджер</span>
                    <select onchange="reloadPlanFactData(this);" class="<?= ($isProfile) ? '' : 'select--white'?> js-select-search"
                            name="managers"
                            id="managers"
                            data-user-id="<?= $USER->GetID() ?>" data-f-field data-select2-id="managers" tabindex="-1" aria-hidden="true">
                        <? foreach ($arResult['USER_MANAGERS'] as $manager): ?>
                            <option value="<?= $manager['ID'] ?>" <?= $manager['ID'] == $arResult['CURRENT_MANAGER_FILTER'] ? 'selected' : '' ?>>
                                <?= $manager['UF_NAME'] ?>
                            </option>
                        <? endforeach; ?>
                    </select>
                </div>
                <input type="hidden" id="filter_manager_plan" name="filter_manager_plan" value="<?= $arResult['FILTER_MANAGER_PLAN'] ?>">
                <? endif; ?>
                <div id="contragent_list_default" class="b-form__item b-form__item--select" data-f-item >
                    <span class="b-form__label" data-f-label>Юридическое лицо</span>
                    <select onchange="reloadPlanFactData(this);" class="<?= ($isProfile) ? '' : 'select--white'?> js-select-search"
                            name="contragents"
                            id="contragents"
                            data-user-id="<?= $USER->GetID() ?>" data-f-field data-select2-id="contragents" tabindex="-1" aria-hidden="true">
                        <? foreach ($arResult['CONTRAGENTS'] as $contragent): ?>
                            <option value="<?= $contragent['UF_XML_ID'] ?>" <?= $contragent['UF_XML_ID'] === $arResult['CURRENT_CONTRAGENT'] ? 'selected' : '' ?>>
                                <?= $contragent['UF_NAME'] ?>
                            </option>
                        <? endforeach; ?>
                    </select>
                </div>
                <?
                $currentYear = date('Y');
                $years = [$currentYear + 1, $currentYear, $currentYear - 1, $currentYear - 2, $currentYear - 3];
                ?>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>Год</span>
                    <select name="year" id="year" data-f-item onchange="reloadPlanFactData(this)">
                        <? foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <? if ($year == $arResult['year']): ?> selected <? endif; ?>>
                                <?= $year ?> г.
                            </option>
                        <? endforeach ?>
                    </select>
                </div>

                <input type="hidden"
                       id="operation"
                       name="operation"
                       value="save_data">
                <?foreach ($arResult['MONTHS']['TITLES'] as $key => $month) {?>
                    <div class="b-form__item" data-f-item>
                        <span class="b-form__label" data-f-label><?=$month?></span>
                        <input type="text"
                               id="<?=$arResult['MONTHS']['NAMES_IDS'][$key]?>"
                               name="<?=$arResult['MONTHS']['NAMES_IDS'][$key]?>"
                               placeholder=""
                               maxlength="255"
                               data-f-field
                               data-bitness
                               data-number
                               data-required="Y"
                               value="<?=number_format($arResult[$arResult['MONTHS']['NAMES_IDS'][$key]], 0, '.', ' ') ? : 0?>">
                        <span class="b-form__text" data-form-error></span>
                    </div>
                <?}?>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>I Квартал</span>
                    <input type="text"
                           id="first_quarter"
                           name="first_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['first_quarter'], 0, '.', ' ') ? : 0?>"
                           data-mask="timeMask"
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>

                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>II Квартал</span>
                    <input type="text"
                           id="second_quarter"
                           name="second_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['second_quarter'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>III Квартал</span>
                    <input type="text"
                           id="third_quarter"
                           name="third_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['third_quarter'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>IV Квартал</span>
                    <input type="text"
                           id="fourth_quarter"
                           name="fourth_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['fourth_quarter'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>
                <div class="b-form__item plan_for_year" data-f-item>
                    <span class="b-form__label" data-f-label>План на год</span>
                    <input type="text"
                           id="plan_for_year"
                           name="plan_for_year"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['plan_for_year'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>

                <div class="b-form__bottom">
                    <button class="btn btn--transparent btn--big"
                            type="submit"
                            name="save"
                            value="Сохранить изменения"
                            data-agree-submit="WEB_FORM_AJAX">Добавить
                    </button>
                </div>
                <br>
                <br>
                <small>
                    <span>I квартал: январь, февраль и март;</span>
                    <br>
                    <span>II квартал: апрель, май и июнь;</span>
                    <br>
                    <span>III квартал: июль, август и сентябрь;</span>
                    <br>
                    <span>IV квартал: октябрь, ноябрь и декабрь.</span>
                    <br>
                </small>
            </form>
        </div>
        <div class="b-tabs__item" data-tab-body="2">
            <form id="form_default_plan_fact" action="<?= $APPLICATION->GetCurUri("", false) ?>" method="post" class="b-form"
                  data-form-validation>

                <? if ($arResult['PLAN_FACT_SAVE_MANAGER']): ?>
                    <? if ($arResult['PLAN_FACT_SAVE_MANAGER'] === 'SUCCESS'): ?>
                        <div class="b-form__item success">
                        <span class="b-form__text">
                            Данные сохранены для выбранного менеджера!
                        </span>
                        </div>
                    <? else: ?>
                        <div class="b-form__item error">
                        <span class="b-form__text">
                            <?= $arResult['ERROR']; ?>
                        </span>
                        </div>
                    <? endif ?>
                <? endif ?>
                <p>На данной странице Вам нужно заполнить план продаж для менеджера на каждый месяц выбранного года.
                    На основании этих данных будет рассчитан квартальный и годовой план продаж.</p>
                <p>На основании этих данных будет рассчитана информация, отображаемая на странице ЛК Менеджера.</p>
                <p>Для внесения данных выберите менеджера, по которому будет производится редактирование плана, выберите нужный год,
                    заполните план по каждому месяцу, далее сохраните введенные данные, нажав кнопку "Добавить"</p>

                <?php

                $dir = $APPLICATION->GetCurDir();
                $pos = strpos($dir, '/personal/');
                $isProfile = ($pos !== false) ? true : false; ?>
                <?
                if (count($arResult['USER_MANAGERS']) > 0): ?>
                    <div id="manager_list_plan" class="b-form__item b-form__item--select" data-f-item >
                        <span class="b-form__label" data-f-label>Менеджер</span>
                        <select onchange="reloadPlanFactData(this);" class="<?= ($isProfile) ? '' : 'select--white'?> js-select-search"
                                name="setFilterManagerPlan"
                                id="setFilterManagerPlan"
                                data-user-id="<?= $USER->GetID() ?>" data-f-field data-select2-id="setFilterManagerPlan" tabindex="-1" aria-hidden="true">
                            <? foreach ($arResult['USER_MANAGERS'] as $manager): ?>
                                <option value="<?= $manager['ID'] ?>" <?= $manager['ID'] == $arResult['CURRENT_MANAGER_ID'] ? 'selected' : '' ?>>
                                    <?= $manager['UF_NAME'] ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                <? endif; ?>
                <input type="hidden" id="filter_manager_plan_form2"
                       name="filter_manager_plan_form2" value="<?= $arResult['CURRENT_MANAGER_ID'] ?>">
                <?
                $currentYear = date('Y');
                $years = [$currentYear + 1, $currentYear, $currentYear - 1, $currentYear - 2, $currentYear - 3];
                ?>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>Год</span>
                    <select name="year" id="year" data-f-item onchange="reloadPlanFactData(this)">
                        <? foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <? if ($year == $arResult['general_plan']['year']): ?> selected <? endif; ?>>
                                <?= $year ?> г.
                            </option>
                        <? endforeach ?>
                    </select>
                </div>
                <input type="hidden"
                       id="operation_default"
                       name="manager_default"
                       value="<?=$arResult['CURRENT_MANAGER'];?>">

                <input type="hidden"
                       id="operation_default"
                       name="operation_default"
                       value="save_default_data">
                <?foreach ($arResult['MONTHS']['TITLES'] as $key => $month) {?>
                    <div class="b-form__item" data-f-item>
                        <span class="b-form__label" data-f-label><?=$month?></span>
                        <input type="text"
                               id="<?=$arResult['MONTHS']['NAMES_IDS'][$key]?>"
                               name="<?=$arResult['MONTHS']['NAMES_IDS'][$key]?>"
                               placeholder=""
                               maxlength="255"
                               data-f-field
                               data-bitness
                               data-number
                               data-required="Y"
                               value="<?=number_format($arResult['general_plan'][$arResult['MONTHS']['NAMES_IDS'][$key]], 0, '.', ' ') ? : 0?>">
                        <span class="b-form__text" data-form-error></span>
                    </div>
                <?}?>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>I Квартал</span>
                    <input type="text"
                           id="first_quarter"
                           name="first_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['general_plan']['first_quarter'], 0, '.', ' ') ? : 0?>"
                           data-mask="timeMask"
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>

                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>II Квартал</span>
                    <input type="text"
                           id="second_quarter"
                           name="second_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['general_plan']['second_quarter'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>III Квартал</span>
                    <input type="text"
                           id="third_quarter"
                           name="third_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['general_plan']['third_quarter'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>
                <div class="b-form__item" data-f-item>
                    <span class="b-form__label" data-f-label>IV Квартал</span>
                    <input type="text"
                           id="fourth_quarter"
                           name="fourth_quarter"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['general_plan']['fourth_quarter'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>
                <div class="b-form__item plan_for_year" data-f-item>
                    <span class="b-form__label" data-f-label>План на год</span>
                    <input type="text"
                           id="plan_for_year"
                           name="plan_for_year"
                           placeholder=""
                           maxlength="255"
                           data-f-field
                           data-required="Y"
                           value="<?= number_format($arResult['general_plan']['plan_for_year'], 0, '.', ' ') ? : 0 ?>"
                           data-mask=""
                           disabled="disabled">
                    <span class="b-form__text" data-form-error></span>
                </div>

                <div class="b-form__bottom">
                    <button class="btn btn--transparent btn--big"
                            type="submit"
                            name="save"
                            value="Сохранить изменения"
                            data-agree-submit="WEB_FORM_AJAX">Добавить
                    </button>
                </div>
                <br>
                <br>
                <small>
                    <span>I квартал: январь, февраль и март;</span>
                    <br>
                    <span>II квартал: апрель, май и июнь;</span>
                    <br>
                    <span>III квартал: июль, август и сентябрь;</span>
                    <br>
                    <span>IV квартал: октябрь, ноябрь и декабрь.</span>
                    <br>
                </small>
            </form>
        </div>
    </div>
</div>