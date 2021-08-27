<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;

CJSCore::Init('currency');


$basket = Basket::loadItemsForFUser(
    ///Получение ID покупателя (НЕ ID пользователя!)
    Fuser::getId(),

    ///Текущий сайт
    Context::getCurrent()->getSite()
);

$isBasketEmpty = $basket->isEmpty();
?>
<script>
  BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);?>);
</script>

<div class="lk-carts">
    <?if(count($arResult['ITEMS'])>0):?>
    <? foreach ($arResult['ITEMS'] as $cart) { ?>
        <div class="lk-carts__item" data-toggle-wrap data-cart-container data-template-id="<?php echo $cart['ID']; ?>">
            <div class="lk-carts__header lk-carts__header--bg">
                <div class="lk-carts__param">
                    <span>Дата  создания:&nbsp;</span>
                    <span><?= $cart['CREATE_DATE'] ?></span>
                </div>
                <div class="lk-carts__param">
                    <span>Название:&nbsp;</span>
                    <span><?= $cart['NAME'] ?></span>
                </div>
                <div class="lk-carts__param">
                    <span>Юр.лицо:&nbsp;</span>
                    <span><?= $cart['CONTRAGENT'] ?></span>
                </div>
                <div class="lk-carts__param">
                    <span>Сумма:&nbsp;</span>
                    <span data-total-sum>
                        <?php echo $cart['TOTAL_PRICE']; ?>
                    </span>
                </div>
            </div>

            <?
            if (!empty($cart['DESCRIPTION'])) {
                ?>
                <div class="lk-carts__header">
                    <div class="lk-carts__description"><?= $cart['DESCRIPTION']; ?></div>
                </div>
                <?
            }
            ?>

            <div class="lk-carts__header">
                <a
                    <?php if (empty($cart['PRODUCTS'])) { ?>
                        href="javascript:Am.modals.showDialog('/local/include/modals/lk_saved_carts_empty_message.php');"
                    <?php } elseif (true === $isBasketEmpty) { ?>
                        href="javascript:void(0);"
                    <?php } else { ?>
                        href="javascript:Am.modals.showDialog('/local/include/modals/lk_saved_carts_form.php?cart-id=<?php echo $cart['ID']; ?>');"
                    <?php } ?>

                    title="Добавить в корзину"
                    class="btn btn--transparent btn--big"
                    <?php if (!empty($cart['PRODUCTS'])) { ?>
                        data-savedcart-id="<?= $cart['ID'] ?>" <?php echo $isBasketEmpty ? 'data-cart-add' : ''; ?>
                    <?php } ?>
                >
                    <span>Добавить в корзину</span>

                    <?php if (true === $isBasketEmpty) { ?>
                        <span class="tooltip">
                            <span class="tooltip__icon">
                                <svg class='i-icon'>
                                    <use xlink:href='#icon-tooltip-alert'/>
                                </svg>
                            </span>
                            <span class="tooltip__text">
                                По клику заказ в полном составе добавляется в корзину
                            </span>
                        </span>
                    <?php } ?>
                </a>
                <a
                    <?php if (empty($cart['PRODUCTS'])) { ?>
                        href="javascript:Am.modals.showDialog('/local/include/modals/lk_saved_carts_empty_message.php');"
                    <?php } else { ?>
                        href="javascript:void(0);"
                    <?php } ?>
                    title="Оформить заказ"
                    class="btn btn--transparent btn--big"

                    <?php if (!empty($cart['PRODUCTS'])) { ?>
                        data-savedcart-id="<?= $cart['ID'] ?>"
                        data-cart-restore
                    <?php } ?>
                >
                    <span>Оформить заказ</span>
                    <span class="tooltip">
                        <span class="tooltip__icon">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-tooltip-alert'/>
                            </svg>
                        </span>
                        <span class="tooltip__text">
                            По клику заказ в полном составе добавляется в корзину, а Ваша текущая корзина сохраняется в "сохраненных корзинах". При этом Вы переходите к оформлению заказа
                        </span>
                    </span>
                </a>
                <a href="javascript:void(0);"
                   title="Удалить"
                   class="lk-carts__del"
                   data-savedcart-id="<?= $cart['ID'] ?>"
                   data-cart-delete>
                    <span>Удалить</span>
                    <span class="tooltip">
                    <span class="tooltip__icon">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-tooltip-alert'/>
                        </svg>
                    </span>
                    <span class="tooltip__text">
                        По клику корзина удалится из сохраненных
                    </span>
                </span>
                </a>

                <? if (!empty($cart['PRODUCTS'])) {?>
                    <a href="javascript:void(0);" class="link-toggle" title="Показать товары" data-toggle-btn>
                        <span>Показать товары</span>
                        <span>Скрыть товары</span>
                        <div class="plus"></div>
                    </a>
                <?php } ?>
            </div>

            <? if (!empty($cart['PRODUCTS'])) {?>
                <div class="lk-carts__content hidden" data-toggle-list>

                    <?php if (!empty($cart['GHOST_PRODUCTS'])) { ?>
                        <div class="basket-item basket-item--top">
                            <div class="title-1">
                                <span>Из списка скрыты товары, которые на данный момент отсутствуют в каталоге</span>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="basket-item basket-item--top">
                        <div class="basket-item__description">Наименование</div>
                        <div class="basket-item__article">Артикул</div>
                        <div class="basket-item__state">Остаток</div>
                        <div class="basket-item__price">Цена</div>
                        <div class="basket-item__amount">Количество в упаковке</div>
                        <div class="basket-item__count">Количество</div>
                        <div class="basket-item__price">Сумма</div>
                    </div>

                    <?
                    foreach ($cart['PRODUCTS'] as $product) {
                        if (!empty($product['NAME'])) { ?>
                            <div class="basket-item">
                                <div class="basket-item__description">

                                    <a href="<?php echo $product['DETAIL_PAGE_URL']; ?>" title="<?= $product['NAME']; ?>">
                                        <span class="basket-item__title"><?= $product['NAME'] ?></span>
                                    </a>
                                </div>

                                <div class="basket-item__article">
                                    <div class="basket-item__article">
                                        <?= $product['ART_NUMBER']; ?>
                                    </div>
                                </div>

                                <div class="basket-item__state">
                                    <div class="basket-item__t">Остаток</div>
                                    <? if ($product['CATALOG_QUANTITY'] >= 1000): ?>
                                        <span class="green">Много</span>
                                    <? elseif ($product['CATALOG_QUANTITY'] <= 0): ?>
                                        <span class="red">Нет в наличии</span>
                                    <? else: ?>
                                        <div>
                                            <span class="tooltip">
                                                <span class="tooltip__icon">
                                                    <svg class='i-icon'>
                                                        <use xlink:href='#icon-tooltip-alert'/>
                                                    </svg>
                                                </span>
                                                <span class="tooltip__text" style="display: none;">
                                                    Свободный остаток: <?= $product['CATALOG_QUANTITY']; ?> шт. <br>
                                                    <? if ($product['RESERV_BALANCE']['UF_VREZERVE']) { ?>
                                                        Резервный остаток: <?= $product['RESERV_BALANCE']['UF_VREZERVE']; ?> шт.
                                                    <? } ?>
                                                </span>
                                            </span>
                                        </div>
                                    <? endif ?>
                                </div>

                                <div class="basket-item__price">
                                    <div class="basket-item__t">Цена</div>
                                    <span><?= $product['PRICE'] ?></span>
                                    <div class="basket-item__text">цена за 1 шт</div>
                                </div>

                                <div class="basket-item__amount">
                                    <?php if (true === $product['SHOW_MEASURE_TEXT']) { ?>
                                        <div class="basket-item__t">Количество в упаковке</div>
                                        <span><?= $product['KOLICHESTVO_V_UPAKOVKE']; ?> шт/уп</span>
                                    <?php } ?>
                                </div>

                                <div class="basket-item__count">
                                    <div class="basket-item__t">Количество</div>
                                    <div class="b-count" data-input-count>
                                        <button type="button" data-input-count-btn="minus" class="b-count__btn b-count__btn--minus"></button>
                                        <input class="b-count__input"
                                               data-input-count-input
                                               data-input-count-not-val
                                               data-measure
                                               data-itemid="<?php echo $product['PRODUCT_ID']; ?>"
                                               data-value="<?= $product['QUANTITY'] ?>"
                                               value="<?= $product['QUANTITY'] ?>">
                                        <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"></button>
                                    </div>
                                </div>

                                <div class="basket-item__price">
                                    <div class="basket-item__t">Итого</div>
                                    <span data-price-sum="<?php echo $product['ORIGIN_PRICE']; ?>"
                                          data-itemid="<?php echo $product['PRODUCT_ID']; ?>"
                                          data-product-total-sum="<?php echo $product['ORIGIN_SUM']; ?>"
                                    >
                                        <?= $product['SUM'] ?>
                                    </span>
                                </div>
                            </div>
                        <? } ?>
                    <? } ?>
                </div>
            <? } ?>
        </div>
    <? } ?>
    <?else:?>
        <h3>У Вас нет сохраненных корзин.</h3>
        <h3>Вернуться в <a href="/catalog/" class="link" title="каталог">каталог</a></h3>
    <?endif?>
</div>
