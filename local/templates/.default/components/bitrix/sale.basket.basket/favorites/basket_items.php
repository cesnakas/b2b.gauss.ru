<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */

use Citfact\Sitecore\CatalogHelper\ElementRepository;
use Bitrix\Main\Localization\Loc;

global $USER;
if (!empty($arResult["ERROR_MESSAGE"]))
    ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn = false;
$bPriceType = false;

if ($normalCount > 0) {?>
    <div class="cart">
        <form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="basket_form" id="basket_form"
              class="aside aside--right">
            <div class="aside-main" id="basket_cont">
                <div class="cart-top b-section-small">
                    <a href="#confirm_modal" class="cart-top__link" id="clear-basket">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-trash'></use>
                        </svg>
                        <?= Loc::getMessage('DEL_ALL_GOODS') ?>
                    </a>
                    <? if ($USER->IsAuthorized()) { ?>
                        <a href="#" class="cart-top__link" id="saveTemplateOrder">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-save'></use>
                            </svg>
                            <?= Loc::getMessage('SAVE_TEMPLATE_ORDER') ?>
                        </a>
                        <a href="/local/include/modals/templateorder-notify.php" data-modal="ajax"
                           class="cart-top__link">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-ring'></use>
                            </svg>
                            <?= Loc::getMessage('CREATE_TEMPLATE_NOTIFY') ?>
                        </a>
                    <? } ?>
                </div>
                <?
                foreach ($arResult["GRID"]["ROWS"] as $key => $arManufactured) {
                    echo "TEST";
                        ?>
                        <div class="cart__section" id="basket_items">
                            <div class="title-3"><?= $arManufactured['NAME'] ?></div>
                            <div class="cart__table">
                                <div class="cart-header">
                                    <div>
                                        <?= Loc::getMessage('NAME_GOODS') ?>
                                    </div>
                                    <div>
                                        <?= Loc::getMessage('PRICE_DISCOUNT') ?>
                                    </div>
                                    <div>
                                        <?= Loc::getMessage('PRICE_WITHOUT_DISCOUNT') ?>
                                    </div>
                                    <div>
                                        <?= Loc::getMessage('QUANTITY_GOODS') ?>
                                    </div>
                                    <div>
                                        <?= Loc::getMessage('SUM_GOODS') ?>
                                    </div>
                                    <div></div>
                                </div>
                                <?
                                foreach ($arManufactured["ITEMS"] as $arItem) { ?>
                                    <?
                                    if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y") {
                                        if ($arItem['PROPERTY_MIN_ORDER_VALUE'])
                                            $i = 1;
                                        else
                                            $i = 0;
                                        if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0) {
                                            $url = $arItem["PREVIEW_PICTURE_SRC"];
                                        } elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0) {
                                            $url = $arItem["DETAIL_PICTURE_SRC"];
                                        } else {
                                            $url = Citfact\SiteCore\Core::NO_PHOTO_SRC;
                                        }
                                        ?>
                                        <div class="cart-item b-cart__item"
                                             id="<?= $arItem["ID"] ?>" data-diff-price="<?=($arItem['DIFF_PRICE'])?:0?>">
                                            <div class="cart__title">
                                                <div>
                                                    <img src="<?= $url ?>" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>">
                                                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                                                        <?= $arItem["NAME"] ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart__price">

                                                <div class="cart__m-title"><?= Loc::getMessage('PRICE_DISCOUNT_MOBILE') ?></div>

                                                <span><?=$arItem['PRICE'], $arItem['~CURRENCY'];?></span>
                                                <div class="cart__text" style="display: none"
                                                     id="min_order_<?= $arItem["ID"] ?>" data-min-order="<?= $arItem["ID"] ?>">
                                                    <?= Loc::getMessage('RESTRICT_MIN_ORDER') ?>
                                                </div>

                                            </div>
                                            <div class="cart__price">
                                                <div class="cart__m-title"><?= Loc::getMessage('PRICE_WITHOUT_DISCOUNT_MOBILE') ?></div>
                                                <span>
                                                    <?=$arItem['PRICE_WITHOUT_DISCOUNT_CONVERTED']?: $arItem['PRICE'], $arItem['~CURRENCY'];?>
                                                    </span>
                                            </div>

                                            <?
                                            $ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
                                            $min = (isset($arItem['PROPERTY_MIN_ORDER_VALUE']) && $arItem['PROPERTY_MIN_ORDER_VALUE'] > 0) ? $arItem["PROPERTY_MIN_ORDER_VALUE"] : 0;
                                            $max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"" . $arItem["AVAILABLE_QUANTITY"] . "\"" : "";
                                            $useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
                                            $useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
                                            if (!isset($arItem["MEASURE_RATIO"])) {
                                                $arItem["MEASURE_RATIO"] = 1;
                                            }
                                            ?>

                                            <div class="cart__value">
                                                <div class="b-count">
                                                    <div class="b-count__wrap" data-input-count="">
                                                        <button type="button"
                                                                class="b-count__btn b-count__btn--minus"

                                                                data-input-count-btn="minus"></button>
                                                        <span class="b-count__input-wrap">
                                                             <input class="b-count__input" type="text"
                                                                    id="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                                                                    name="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                                                                    maxlength="12"
                                                                    min="<?= $min ?>"
                                                                    <?= $max ?>
                                                                    step="<?= $ratio ?>"
                                                                    value="<?= ($min > 0 && $min > $arItem["QUANTITY"]) ? $min : $arItem["QUANTITY"] ?>"
                                                                    onchange="updateQuantity('QUANTITY_INPUT_<?= $arItem["ID"] ?>', '<?= $arItem["ID"] ?>', <?= $ratio ?>, <?= $useFloatQuantityJS ?>, <?= $min ?>)"
                                                                    data-input-count-input>
                                                             <input type="hidden" id="QUANTITY_<?= $arItem['ID'] ?>"
                                                                    name="QUANTITY_<?= $arItem['ID'] ?>"
                                                                    value="<?= $arItem["QUANTITY"] ?>"/>
                                                        </span>
                                                        <button type="button" class="b-count__btn b-count__btn--plus"

                                                                data-input-count-btn="plus"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cart__sum">

                                                <div class="cart__m-title"><?= Loc::getMessage('SUM_GOODS_MOBILE') ?></div>
                                                <span id="sum_<?= $arItem["ID"] ?>">
                                                    <?= $arItem['SUM_VALUE'], $arItem['~CURRENCY']?>
                                                </span>
                                            </div>
                                            <div class="cart__actions">
                                                <a href="<?= str_replace("#ID#", $arItem["ID"], $arUrls["delete"]) ?>"
                                                   onclick="BX.showWait();">
                                                    <svg class='i-icon'>
                                                        <use xlink:href='#icon-basket'></use>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="cart__text mobile" style="display: none"
                                                 id="min_order_<?= $arItem["ID"] ?>" data-min-order="<?= $arItem["ID"] ?>">
                                                <?= Loc::getMessage('RESTRICT_MIN_ORDER') ?>
                                            </div>
                                        </div>
                                        <?
                                    } ?>

                                    <?
                                }
                                ?>
                            </div>
                            <div class="cart-options">
                                <?/*<div class="cart-options__inner">
                                    <div class="cart-options__item">
                                        <div class="cart-options__label">
                                            <?= Loc::getMessage('RECEIVE_METHOD') ?>
                                        </div>
                                        <div class="select-wrap">
                                            <?if($arManufactured["PICKUP"]){?>
                                                <select name="delivery" id="">
                                                    <option value="1"><?= Loc::getMessage('PICKUP') ?></option>
                                                    <option value="2"><?= Loc::getMessage('DELIVERY') ?></option>
                                                </select>
                                            <?} else {?>
                                                <select name="delivery" id="">
                                                    <option value="2" selected><?= Loc::getMessage('DELIVERY') ?></option>
                                                </select>
                                            <?}?>
                                        </div>
                                    </div>
                                </div>*/?>
                                <div class="cart-options__text">
                                    Примерные габариты: <?=$arManufactured["DIMENSIONS"]; ?>
                                </div>
                            </div>
                        </div>
                        <?

                }
                ?>

            </div>
            <div class="aside-sidebar">
                <div class="aside-sidebar__inner">
                    <div class="cart-sidebar">
                        <div class="cart-sidebar__item cart-sidebar__item--sum">
                            <span><?= Loc::getMessage('SUM_ORDERS') ?></span>
                            <span><span id="allSumORDER_FORMATED"><?= str_replace(" ", "&nbsp;", $arResult["allSum"], $arResult['CURRENCY'])?>
                        </div>
                        <div class="cart-sidebar__item">
                            <span><?= Loc::getMessage('SUM_BASKET') ?></span>
                            <span><span id="allSum_FORMATED"><?= str_replace(" ", "&nbsp;", $arResult["allSum"], $arResult['CURRENCY']) ?>
                        </div>
                        <div class="cart-sidebar__item">
                            <span><?= Loc::getMessage('SUM_DISCOUNT') ?></span>
                            <span><span id="DISCOUNT_PRICE_ALL"><?= str_replace(" ", "&nbsp;", $arResult["DISCOUNT_PRICE_ALL"], $arResult['CURRENCY']) ?>
                        </div>
                        <?/*<div class="cart-sidebar__item">
                            <span><?= Loc::getMessage('SUM_DELIVERY') ?></span>
                            <span>300 <?=$arItem['CURRENCY']?></span>
                        </div>*/?>
                        <div class="cart-sidebar__text">
                            <?= Loc::getMessage('PREORDER_INFO') ?>
                        </div>
                        <a href="<?= $arParams['PATH_TO_ORDER'] ?>" class="btn btn--red"><?= Loc::getMessage('CREATE_ORDER') ?></a>
                        <br><br><a href="<?= SITE_DIR; ?>catalog" class="btn btn--red"><?= Loc::getMessage('RETURN_TO_CATALOG') ?></a>
                    </div>
                </div>
            </div>

            <input type="hidden" id="column_headers" value="<?= CUtil::JSEscape(implode($arHeaders, ",")) ?>"/>
            <input type="hidden" id="templateFolder" value="<?= $templateFolder?>"/>
            <input type="hidden" id="offers_props"
                   value="<?= CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ",")) ?>"/>
            <input type="hidden" id="action_var" value="<?= CUtil::JSEscape($arParams["ACTION_VARIABLE"]) ?>"/>
            <input type="hidden" id="quantity_float" value="<?= $arParams["QUANTITY_FLOAT"] ?>"/>
            <input type="hidden" id="count_discount_4_all_quantity"
                   value="<?= ($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N" ?>"/>
            <input type="hidden" id="price_vat_show_value"
                   value="<?= ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N" ?>"/>
            <input type="hidden" id="hide_coupon" value="<?= ($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N" ?>"/>
            <input type="hidden" id="use_prepayment" value="<?= ($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N" ?>"/>
            <input type="hidden" id="auto_calculation"
                   value="<?= ($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y" ?>"/>
            <input type="hidden" name="BasketOrder" value="BasketOrder"/>
        </form>
    </div>
    <div id="confirm_modal" class="b-modal">
        <div class="plus plus--cross b-modal__close" data-modal-close></div>
        <div class="b-modal__title">Вы подтверждаете удаление?</div>
        <div class="b-modal__btns b-form__btns">
            <button class="btn btn--red" data-confirm="Y">Да</button>
            <button class="btn btn--grey" data-confirm="N">Нет</button>
        </div>
    </div>
    <?
} else {
    ?>
    <div class="b-cart-empty">
        <div class="b-cart-empty__text">
            <?= Loc::getMessage('YOUR_EMPTY_BASKET') ?>
        </div>

        <div class="b-cart-empty__message">
            <?= Loc::getMessage('EMPTY_BASKET') ?>
        </div>

        <div class="b-cart-empty__funcs">
            <a href="<?=$arParams['PATH_TO_CATALOG']?>" class="btn btn--red"><?= Loc::getMessage('VIEW_CATALOG') ?></a>
            <a href="<?=$arParams['PATH_TO_MANUFACTURED']?>" class="btn btn--yellow"><?= Loc::getMessage('VIEW_BRAND') ?></a>
        </div>
        <? $APPLICATION->IncludeFile(
            "local/include/areas/" . LANGUAGE_ID . "html/slider-detail-recent.php",
            Array(),
            Array("MODE" => "html")
        ); ?>
    </div>
    <?
}
?>