<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arResult */
/** @var array $arParams */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var OrderMake $component */

$this->setFrameMode(false);
CJSCore::Init(array('currency'));
$page = $APPLICATION->GetCurPage();
?>

<div class="order" id="order-checkout-wrap">
    <div class="order__main">
        <form action="<?= $page; ?>" id="order-checkout-form" class="b-form">
            <input type="hidden" name="save" value="Y" />

            <div class="order__content">
                <div class="title-2">Доставка</div>

                <div class="order__delivery">
                    <div class="b-checkbox b-checkbox--radio">
                        <label class="b-checkbox__label">
                            <input type="radio" id="DELIVERY_ID" name="DELIVERY_ID" value="2" class="b-checkbox__input" checked>
                            <span class="b-checkbox__box"></span>
                            <span class="b-checkbox__text">Самовывоз</span>
                        </label>
                    </div>
                    <div class="b-checkbox b-checkbox--radio">
                        <label class="b-checkbox__label">
                            <input type="radio" id="DELIVERY_ID" name="DELIVERY_ID" value="3" class="b-checkbox__input">
                            <span class="b-checkbox__box"></span>
                            <span class="b-checkbox__text">Собственная курьерская доставка</span>
                        </label>
                    </div>
                    <div class="b-checkbox b-checkbox--radio">
                        <label class="b-checkbox__label">
                            <input type="radio" id="DELIVERY_ID" name="DELIVERY_ID" value="4" class="b-checkbox__input">
                            <span class="b-checkbox__box"></span>
                            <span class="b-checkbox__text">Доставка транспортной компанией</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="title-2">Товары в заказе</div>
            <div class="order__t">
                <div class="basket-item basket-item--top">
                    <div class="basket-item__description">Наименование</div>
                    <div class="basket-item__price">Цена</div>
                    <div class="basket-item__price">Сумма</div>
                </div>

                <? foreach ($arResult['BASKET_ITEMS'] as $item) { ?>
                    <div class="basket-item">
                        <div class="basket-item__description">
                            <a href="<?= $item['DETAIL_PAGE_URL']; ?>" title="<?= $item['NAME']; ?>" target="_blank">
                                <span class="basket-item__title">
                                    <?= $item['NAME']; ?>
                                </span>
                            </a>

                            <div class="basket-item__article">
                                Артикул:&nbsp;&nbsp;<?= $item['PROPERTY_CML2_ARTICLE']; ?>
                            </div>
                        </div>

                        <div class="basket-item__price">
                            <div class="basket-item__t">Цена</div>
                            <span><?= $item['BASKET']['PRICE']; ?> ₽</span>
                            <div class="basket-item__text">цена за 1 шт</div>
                        </div>

                        <div class="basket-item__price">
                            <div class="basket-item__t">Итого</div>
                            <span><?= $item['BASKET']['FINAL_PRICE']; ?> ₽</span>
                        </div>
                    </div>
                <? } ?>

                <div class="b-form__item b-form__item--textarea" data-f-item>
                    <span class="b-form__label" data-f-label>Комментарий к заказу</span>

                    <textarea data-f-field></textarea>

                    <span class="b-form__text">
                        Некорректно заполнено поле
                    </span>
                </div>

            </div>

        </form>
    </div>
    <div class="order__sidebar" data-fix-sidebar>
        <div class="order-total" data-fix-item>

            <div class="title-2">Ваш заказ</div>

            <div class="order-total__item">
                <span>Товаров на сумму:</span>
                <span>9 000,00 ₽</span>
            </div>

            <div class="order-total__item">
                <span>Доставка:</span>
                <span>500,00 ₽</span>
            </div>

            <div class="order-total__item">
                <span>Общий вес:</span>
                <span>22.600 кг.</span>
            </div>

            <div class="order-total__item">
                <span>Объем:</span>
                <span>5 м <sup>3</sup></span>
            </div>

            <div class="order-total__item order-total__item--sum">
                <span>Итого:</span>
                <span>5 760, 00 ₽</span>
            </div>

            <a href="javascript:void(0);" data-order-checkout-submit class="btn btn--transparent" title="Оформить заказ">Оформить заказ</a>

            <div class="order-total__pp">
                Нажав на кнопку "отправить заказ", я даю свое согласие на
                обработку персональных данных в соответствии с указанными
                здесь условиями
            </div>
        </div>
    </div>

</div>

<?if ($component->isAjax !== true){?>
    <script type="text/javascript">
      if (typeof BX.Currency === 'object') {
        BX.Currency.setCurrencyFormat('RUB', <? echo CUtil::PhpToJSObject($arResult['CURRENCY'], false, true); ?>);
      }
      // signedParameters - перечень ключей параметров компонента
      OrderMake.init(<?=json_encode([
          'signedParameters' => $component->getSignedParameters(),
          'wrapId' => '#order-checkout-wrap',
          'currency' => $arResult['CURRENCY'],
          'isAjax' => $component->isAjax,
      ])?>);
    </script>
<?}?>