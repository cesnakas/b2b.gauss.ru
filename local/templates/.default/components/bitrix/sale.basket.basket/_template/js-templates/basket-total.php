<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
	<div class="basket-total__inner" data-entity="basket-checkout-aligner">
        <div class="basket-total__info basket-total-info">
            <div class="basket-total-info__left">
                <div class="basket-total__sum basket-total__sum--small basket-total__sum--available">
                    <span>В наличии:</span>
                    <span>{{{AVAILABLE_SUM}}}</span>
                </div>

                <div class="basket-total__sum basket-total__sum--small basket-total__sum--not-available">
                    <span>Нет в наличии:</span>
                    <span>{{{NOT_AVAILABLE_SUM}}}</span>
                </div>
            </div>
            <div class="basket-total-info__right">
                <div class="basket-total__sum">
                    <span><?=Loc::getMessage('SBB_TOTAL')?>:</span>
                    <div class="total-price__wrapper">
                        <span id="total-price" data-entity="basket-total-price">{{{PRICE_WITHOUT_DISCOUNT_FORMATED}}}*</span>
                        <span class="discount_price view" data-entity="basket-total-price-discount">&nbsp;{{{PRICE_FORMATED_NEW}}}</span>
                    </div>
                </div>

                <div class="basket-total__sum basket-total__sum--small">
                    <span>Сумма НДС:</span>
                    <span>{{{VAT_SUM_FORMATED}}}</span>
                </div>
                <div class="basket__coupon">

                    <input type="hidden"
                           placeholder="<?=Loc::getMessage('SBB_COUPON_ENTER')?>"
                           data-entity="basket-coupon-input">
                    <input class="checkbox-discount" type="checkbox" id="checkbox-discount" data-entity="coupon-input-checkbox" data-coupon="{{{COUPON}}}" value="TWO_PERCENT" {{{CHECKBOX}}}>
                    <label for="checkbox-discount"> Скидка 2% пользователям портала</label>
                </div>
            </div>
            <div class="basket-total-info__bottom">
                <span>*Предварительный итог. <br>Точная сумма будет доступна после оформлении заказа</span>
            </div>
        </div>

        <button class="btn btn--transparent btn--big{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}} basket-total__submit"
                data-entity="basket-checkout-button">
            <?=Loc::getMessage('SBB_ORDER')?>
        </button>
	</div>
    
    <?
    if ($arParams['HIDE_COUPON'] !== 'Y')
    {
        ?>
        <div class="basket-coupons" style="display: none">
            {{#COUPON_LIST}}
            <div class="basket-coupons__item {{CLASS}}">
                <div class="basket-coupons__text" title="{{JS_CHECK_CODE}}">
                    <span>{{COUPON}}</span>

                    <img src="/local/client/img/galochka.svg"
                         alt="">
                </div>
                <div class="plus plus--cross" data-entity="basket-coupon-delete" data-coupon="{{COUPON}}"></div>
            </div>
            {{/COUPON_LIST}}
        </div>
        <?
    }
    ?>
</script>