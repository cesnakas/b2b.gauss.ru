<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<script type="text/javascript">
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>

<div class="b-filter" data-filter>
    <form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" class="b-form" method="get">

        <? // Максимальная и минимальная цена
        // VALUE - минимальное или максимальное значение
        // HTML_VALUE - текущее введенное значение
        $minPrice = 0;
        $maxPrice = 1000000;
        $curMinPrice = 0;
        $curMaxPrice = 1000000;
        foreach ($arResult["ITEMS"] as $key => $arItem) //prices
        {
            $key = $arItem["ENCODED_ID"];
            if (isset($arItem["PRICE"])) {
                if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                    continue;
                $minPrice = floor($arItem["VALUES"]["MIN"]["VALUE"]);
                $maxPrice = ceil($arItem["VALUES"]["MAX"]["VALUE"]);
                $curMinPrice = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
                $curMaxPrice = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
            }
        }
        if (!$curMinPrice) {
            $curMinPrice = $minPrice;
        }
        if (!$curMaxPrice) {
            $curMaxPrice = $maxPrice;
        }
        ?>

        <? foreach ($arResult["ITEMS"] as $key => $arItem)//prices
        {
            $key = $arItem["ENCODED_ID"];
            if (isset($arItem["PRICE"])):
                if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                    continue;
                ?>
                <div class="b-filter__item" data-toggle-wrap>
                    <div class="b-filter__title active" data-toggle-btn>
                        Цена
                    </div>
                    <div class="b-filter__content active" data-toggle-list>

                        <div class="b-range-slider b-range-slider--price" data-toggle-list data-range="filter-count"
                             data-range-min="<?= $minPrice ?>"
                             data-range-max="<?= $maxPrice ?>"
                             data-start-min="<?= $curMinPrice ?>"
                             data-start-max="<?= $curMaxPrice ?>"
                             data-units="₽">
                            <div class="b-range-slider__inputs">
                                <input type="text"
                                       data-range-input="left"
                                       name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                       id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                       value="<?= $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                       onkeyup="smartFilter.keyup(this)">
                                <input type="text"
                                       data-range-input="right"
                                       name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                       id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                       value="<?= $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                       onkeyup="smartFilter.keyup(this)">
                            </div>
                            
                            <div class="b-range-slider__slider" data-range-slider></div>
                        </div>

                    </div>

                    <div class="filter__result-wrap"></div>
                </div>
            <?endif;?>
        <?}?>
        <? //not prices
        foreach ($arResult["ITEMS"] as $key => $arItem) {
            if (
                empty($arItem["VALUES"])
                || isset($arItem["PRICE"])
            )
                continue;
            
            if (
                $arItem["DISPLAY_TYPE"] == "A"
                && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
            )
                continue;
            ?>
            <?

            ///TODO галогенные лампы, аналог КЛЛ
            $eValue = '';
            switch ($arItem['CODE']) {
                case 'GARANTIYA_MES':
                    $eValue = 'мес.';
                    break;
                case 'MOSHCHNOST_LAMPY_VT':
                    $eValue = 'Вт';
                    break;
                case 'SVETOVOY_POTOK_LM':
                    $eValue = 'лм';
                    break;
                case 'DLINA_MM':
                    $eValue = 'мм';
                    break;
                default:
                    $eValue = '';
            }
            
            switch ($arItem["DISPLAY_TYPE"]) {
                
                case "A": //NUMBERS_WITH_SLIDER ?>
                    
                
                    <? // Максимальная и минимальная цена
                    // VALUE - минимальное или максимальное значение
                    // HTML_VALUE - текущее введенное значение
                    $minPrice = $arItem["VALUES"]["MIN"]["VALUE"];
                    $maxPrice = $arItem["VALUES"]["MAX"]["VALUE"];
                    $curMinPrice = $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?: $arItem["VALUES"]["MIN"]["VALUE"];
                    $curMaxPrice = $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?: $arItem["VALUES"]["MAX"]["VALUE"];
                    ?>
                    <div class="b-filter__item" data-toggle-wrap>
                        <div class="b-filter__title <?if($arItem["DISPLAY_EXPANDED"] == "Y"){?>active<?}?>" data-toggle-btn>
                            <?=$arItem['NAME']?>
                        </div>
                        <div class="b-filter__content <?if($arItem["DISPLAY_EXPANDED"] == "Y"){?>active<?}?>" data-toggle-list>
                            <div class="b-range-slider" data-toggle-list data-range="filter-count"
                                 data-range-min="<?= $minPrice ?>"
                                 data-range-max="<?= $maxPrice ?>"
                                 data-start-min="<?= $curMinPrice ?>"
                                 data-start-max="<?= $curMaxPrice ?>"
                                 data-units="<?= $eValue ?>">
                                <div class="b-range-slider__inputs">
                                    <input type="text"
                                           data-range-input="left"
                                           name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                           id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                           value="<?= $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                           onkeyup="smartFilter.keyup(this)">
                                    <input type="text"
                                           data-range-input="right"
                                           name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                           id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                           value="<?= $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                           onkeyup="smartFilter.keyup(this)">
                                </div>
                                
                                <div class="b-range-slider__slider" data-range-slider></div>
                            </div>
                        </div>
                        <div class="filter__result-wrap"></div>
                    </div>
                    <? break; ?>
                
                <? default://CHECKBOXES?>
                <?if($arItem["CODE"] == 'SERIES' && $arParams['LAST_SECTION'] ===  true || $arItem["CODE"] != 'SERIES'):?>
                    <div class="b-filter__item" data-toggle-wrap>
                        <div class="b-filter__title <? if($arItem["DISPLAY_EXPANDED"] == "Y"){?>active<?}?>" data-toggle-btn>
                            <?= $arItem["NAME"] ?>
                        </div>
                        <div class="b-filter__content <?if ($arItem["DISPLAY_EXPANDED"] == "Y"){?>active<?}?>" data-toggle-list>
                            <? foreach ($arItem["VALUES"] as $val => $ar) {
                                if (!$ar["VALUE"]) continue;
                                $value = $ar['VALUE'];
                                if (is_numeric($value)) {
                                }
                                ?>
                                <div class="b-checkbox">
                                    <label for="<? echo $ar["CONTROL_ID"] ?>" class="b-checkbox__label <? echo $ar["DISABLED"] ? 'disabled' : '' ?>" <? echo $ar["DISABLED"] ? 'disabled' : '' ?>
                                           data-role="label_<?= $ar["CONTROL_ID"] ?>" >

                                        <input type="checkbox" class="b-checkbox__input"
                                               value="<? echo $ar["HTML_VALUE"] ?>"
                                               name="<? echo $ar["CONTROL_NAME"] ?>"
                                               id="<? echo $ar["CONTROL_ID"] ?>"
                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                            <? echo $ar["DISABLED"] ? 'disabled' : '' ?>
                                               onchange="smartFilter.click(this)"
                                        >
                                        <span class="b-checkbox__box">
                                        <span class="b-checkbox__line b-checkbox__line--short"></span>
                                        <span class="b-checkbox__line b-checkbox__line--long"></span>
                                    </span>
                                        <?= $value; ?>
                                    </label>
                                </div>
                            <?}?>
                        </div>
                        <div class="filter__result-wrap"></div>
                    </div>
                <?endif;?>
                    <? break; ?>
                <? } ?>
        <? } ?>


        <div class="filter__result" id="modef" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>>
            <a href="#" class="filter__result-count">
                <? echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
            </a>
        </div>

        <div class="b-filter__btns">

            <button class="btn btn--grey"
                    type="submit"
                    id="set_filter"
                    name="set_filter">
                <span><?=GetMessage("CT_BCSF_SET_FILTER")?> <span id="modef_count_mobile"></span></span>
            </button>

            <button type="submit"
                    id="del_filter"
                    name="del_filter">
                <span class="link-underline-d"><?=GetMessage("CT_BCSF_DEL_FILTER")?></span>
            </button>
        </div>
    </form>
</div>
