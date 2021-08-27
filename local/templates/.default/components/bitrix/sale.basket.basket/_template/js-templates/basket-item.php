<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */


$usePriceInAdditionalColumn = in_array('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$useSumColumn = in_array('SUM', $arParams['COLUMNS_LIST']);
$useActionColumn = in_array('DELETE', $arParams['COLUMNS_LIST']);

$restoreColSpan = 2 + $usePriceInAdditionalColumn + $useSumColumn + $useActionColumn;

$positionClassMap = array(
	'left' => 'basket-item-label-left',
	'center' => 'basket-item-label-center',
	'right' => 'basket-item-label-right',
	'bottom' => 'basket-item-label-bottom',
	'middle' => 'basket-item-label-middle',
	'top' => 'basket-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
?>
<script id="basket-item-template" type="text/html">
    <div class="basket-item basket-item--basket"
         id="basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">


        {{#SHOW_RESTORE}}

        <div class="basket-items-list-item-notification" colspan="<?=$restoreColSpan?>">

            <div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="basket-item-height-aligner-{{ID}}">
                {{#SHOW_LOADING}}
                <div class="basket-items-list-item-overlay"></div>
                {{/SHOW_LOADING}}
                <div class="basket-items-list-item-removed-container">
                    <div>
                        <?=Loc::getMessage('SBB_GOOD_CAP')?> <strong>{{NAME}}</strong> <?=Loc::getMessage('SBB_BASKET_ITEM_DELETED')?>.
                    </div>
                    <div class="basket-items-list-item-removed-block link">
                        <a href="javascript:void(0)" data-entity="basket-item-restore-button">
                            <?=Loc::getMessage('SBB_BASKET_ITEM_RESTORE')?>
                        </a>
                        <span class="basket-items-list-item-clear-btn" data-entity="basket-item-close-restore-button"></span>
                    </div>
                </div>
            </div>

        </div>
        {{/SHOW_RESTORE}}
        {{^SHOW_RESTORE}}

        <a href="{{DETAIL_PAGE_URL}}" class="basket-item__img" title="">
            <!--Заменить ссылку на изображение. В случае отсутствия - выводить заглушку.-->
            <img src="{{IMAGE_URL}}" alt="" title="">
        </a>

        <div class="basket-item__description">
            
            {{#DETAIL_PAGE_URL}}
            <a href="{{DETAIL_PAGE_URL}}" data-entity="basket-item-name">
                {{/DETAIL_PAGE_URL}}
                
                <span class="basket-item__title">
                    {{NAME}}
                </span>

                {{#DETAIL_PAGE_URL}}
            </a>
            {{/DETAIL_PAGE_URL}}
            
            <div class="basket-item__article">
                Артикул&nbsp;&nbsp;{{{CML2_ARTICLE}}}
            </div>
        </div>

        {{#OSTATOK_AVAILABLE}}
            <div class="basket-item__state basket-item__state--tooltip {{OSTATOK_CLASS}} ">
                <div class="basket-item__t">Остаток</div>
                <span class="basket-item__state-value">{{OSTATOK_AVAILABLE}}</span>
                <!--Добавить проверку: отображать подсказку только в том случае, если товара нет в наличии и указана ожидаемая дата поступления-->
                {{#OUT_OF_STOCK}}
                {{#DATAPRIKHODA}}
                <span class="tooltip">
                    <span class="tooltip__icon">
                        <svg class="i-icon">
                            <use xlink:href="#icon-tooltip-alert"></use>
                        </svg>
                    </span>
                    <span class="tooltip__text">
                        <!--Добавить реальную ожидаемую дату поступления-->
                        <span>Ожидаемая дата поступления: {{DATAPRIKHODA}}</span>
                    </span>
                </span>
                {{/DATAPRIKHODA}}
                {{/OUT_OF_STOCK}}
            </div>
        {{/OSTATOK_AVAILABLE}}

        {{#RESERVE_AVAILABLE}}
            <div class="basket-item__state basket-item__state--tooltip yellow ">
                <div class="basket-item__t">Остаток</div>
                <!--Добавлен вывод фразы "Мало"-->
                <span class="basket-item__state-value">Мало</span>
                <span class="tooltip">
                    <span class="tooltip__icon">
                        <svg class="i-icon">
                            <use xlink:href="#icon-tooltip-alert"></use>
                        </svg>
                    </span>
                    <span class="tooltip__text">
                        <span>Свободный остаток: {{RESERVE_AVAILABLE}}&nbsp;шт.</span>
                        {{#RESERVE_VREZERVE}}
                            <span>Резервный остаток: {{RESERVE_VREZERVE}}&nbsp;шт.</span>
                        {{/RESERVE_VREZERVE}}
                    </span>
                </span>
            </div>
        {{/RESERVE_AVAILABLE}}

        <div class="basket-item__price">
            <div class="basket-item__t">Цена</div>
            <span id="basket-item-price-{{ID}}">
                {{{PRICE_FORMATED}}}
            </span>
            <div class="basket-item__text"><?=Loc::getMessage('SBB_BASKET_ITEM_PRICE_FOR')?> {{MEASURE_RATIO}} {{MEASURE_TEXT}}</div>
        </div>

        <div class="basket-item__amount">
            {{#POKAZAT_KOLICHESTVO_V_UPAKOVKE}}
                <div class="basket-item__t">Количество в упаковке</div>
                <span>{{{KOLICHESTVO_V_UPAKOVKE}}} шт/уп</span>
            {{/POKAZAT_KOLICHESTVO_V_UPAKOVKE}}
        </div>
        
        <div class="basket-item__count basket-item__count--cart">
            <div class="basket-item__t">Количество</div>
            <div class="b-count {{#NOT_AVAILABLE}} disabled{{/NOT_AVAILABLE}}"
                 data-entity="basket-item-quantity-block">

                <button type="button" class="b-count__btn b-count__btn--minus" data-entity="basket-item-quantity-minus"></button>

                <input class="b-count__input"
                       data-value="{{QUANTITY}}"
                       data-entity="basket-item-quantity-field"
                       id="basket-item-quantity-{{ID}}"
                       value="{{QUANTITY}}"
                       autocomplete="off"
                       {{#NOT_AVAILABLE}} disabled="disabled"{{/NOT_AVAILABLE}}>

                <button type="button" class="b-count__btn b-count__btn--plus" data-entity="basket-item-quantity-plus"></button>
            </div>

            {{#KOLICHESTVO_V_UPAKOVKE}}
                <span class="basket-item__text" id="basket-item-upakovka-wrap-{{ID}}">Кол-во не кратно упаковке.
                <br><span class="btn-add-box btn--small btn--grey" data-id="{{ID}}" data-count-box-calc="">
                        Добавьте<span id="basket-item-upakovka-cnt-{{ID}}"></span>шт. до коробки
                    </span>
                </span>
            {{/KOLICHESTVO_V_UPAKOVKE}}
        </div>

        

        <div class="basket-item__price">
            <div class="basket-item__t">Итого</div>
            <span id="basket-item-sum-price-{{ID}}">
                {{{SUM_PRICE_FORMATED}}}
            </span>
        </div>
        
        <div class="basket-item__actions">
            <div class="plus plus--cross" data-entity="basket-item-delete"></div>
        </div>

        <div class="basket-item__text basket-item__text--m hidden">
            <div>Кол-во не кратно упаковке.</div>
            <div class="btn-add-box btn--small btn--grey" data-id="{{ID}}" data-count-box-calc="">
                Добавьте<span></span>шт. до коробки
            </div>
        </div>

        {{/SHOW_RESTORE}}
    </div>
</script>