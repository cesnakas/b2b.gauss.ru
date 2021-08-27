<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
	<div class="basket-total__inner" data-entity="basket-checkout-aligner">
		<?
		if ($arParams['HIDE_COUPON'] !== 'Y')
		{
			?>
			<div class="basket__coupon">
                <input type="text"
                       id=""
                       placeholder="<?=Loc::getMessage('SBB_COUPON_ENTER')?>"
                       data-entity="basket-coupon-input">
			</div>
			<?
		}
		?>
        
        <div class="basket-total__info">
            <div class="basket-total__sum">
                <span><?=Loc::getMessage('SBB_TOTAL')?>:</span>
                <span data-entity="basket-total-price"> {{{PRICE_FORMATED}}} </span>
            </div>
        </div>

        <button class="btn btn--transparent btn--big{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}"
                data-entity="basket-checkout-button">
            Перейти в корзину
        </button>
	</div>
    
    <?
    if ($arParams['HIDE_COUPON'] !== 'Y')
    {
        ?>
        <div class="basket-coupons">
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