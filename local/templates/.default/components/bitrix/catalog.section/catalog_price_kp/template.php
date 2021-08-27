<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult["ITEMS"]) >= 1) { ?>
    <?
    $currencyList = '';
    if (!empty($arResult['CURRENCIES'])) {
        $templateLibrary[] = 'currency';
        $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
    }
    $templateData = array(
        'TEMPLATE_LIBRARY' => $templateLibrary,
        'CURRENCIES' => $currencyList
    );
    unset($currencyList, $templateLibrary);

    $arParams["BASKET_ITEMS"] = ($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
    $arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
    $name_section = $arResult["NAME"];
    ?>
    <div class="title-2"><?= $name_section ?></div>

    <div class="basket-item basket-item--top">
        <div class="basket-item__article">Артикул</div>
        <div class="basket-item__count">Количество</div>
        <div class="basket-item__description">Наименование</div>
        <div class="basket-item__price">Цена, шт.</div>
        <div class="basket-item__actions"></div>
    </div>

    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <div class="basket-item" data-container-item>
            <div class="basket-item__article">
                <span><?= $arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] ?></span>
            </div>
            <div class="basket-item__count" data-input-count>
                <div class="b-count">
                    <button type="button" data-input-count-btn="minus" class="b-count__btn b-count__btn--minus"></button>
                    <input placeholder="0"
                           data-value="1"
                           value="1"
                           data-input-mask="number10"
                           data-input-count-input
                           class="b-count__input"
                           name="<?= $arItem["ID"] ?>"
                           type="text">
                    <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"></button>
                </div>
            </div>
            <div class="basket-item__description">
                <span class="basket-item__title"><?= $arItem["NAME"] ?></span>
            </div>
            <div class="basket-item__price">
                <span class="basket-item__title"><?= $arItem['PRICE'] ?>&nbsp;₽</span>
            </div>
            <div class="basket-item__actions">
                <div class="plus plus--cross" data-item-delete></div>
            </div>
        </div>
    <? endforeach; ?>

<? } else { ?>
    <? // Если нет товаров?>
    <? $name_section = $arResult["NAME"]; ?>
    <p class="red">
        К сожалению в разделе <?= $name_section ?> товаров нет.
    </p>
<? } ?>

<script>
    BX.message({
        QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
        QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
        ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
        ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
    });
</script>