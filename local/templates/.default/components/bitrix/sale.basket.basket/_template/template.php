<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

$documentRoot = Main\Application::getDocumentRoot();

$curPage = '?' . $arParams["ACTION_VARIABLE"] . '=';
$curPageDelayed = $APPLICATION->GetCurPage() . '?' . $arParams["ACTION_VARIABLE_DELAYED"] . '=';
$arUrls = array(
    "delete" => $curPage . "delete&id=#ID#",
    "delay" => $curPage . "delay&id=#ID#",
    "add" => $curPage . "add&id=#ID#",
);
$arUrlsDelayed = array(
    "delete" => $curPageDelayed . "delete&id=#ID#&favourite=y", // остаемся в избранном. Обработчик в модуле
    "delay" => $curPageDelayed . "delay&id=#ID#&favourite=y", // остаемся в избранном. Обработчик в модуле
    "add" => $APPLICATION->GetCurPage() . $curPage . "add&id=#ID#&quantity=#QUANTITY#&" . $arParams["ACTION_VARIABLE_ADD"] . '=Y', // переходим в корзину
);
unset($curPage);

$arBasketJSParams = array(
    'SALE_DELETE' => GetMessage("SALE_DELETE"),
    'SALE_DELAY' => GetMessage("SALE_DELAY"),
    'SALE_TYPE' => GetMessage("SALE_TYPE"),
    'TEMPLATE_FOLDER' => $templateFolder,
    'DELETE_URL' => $arUrls["delete"],
    'DELAY_URL' => $arUrls["delay"],
    'ADD_URL' => $arUrls["add"],
    'EVENT_ONCHANGE_ON_START' => (!empty($arResult['EVENT_ONCHANGE_ON_START']) && $arResult['EVENT_ONCHANGE_ON_START'] === 'Y') ? 'Y' : 'N'
);
?>

    <script type="text/javascript">
      var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
    </script>
<?


if (empty($arParams['TEMPLATE_THEME']))
{
	$arParams['TEMPLATE_THEME'] = Main\ModuleManager::isModuleInstalled('bitrix.eshop') ? 'site' : 'blue';
}

if ($arParams['TEMPLATE_THEME'] === 'site')
{
	$templateId = preg_match('/^eshop_adapt/', $templateId) ? 'eshop_adapt' : $templateId;
	$arParams['TEMPLATE_THEME'] = Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', $component->getSiteId());
}

if (!empty($arParams['TEMPLATE_THEME']))
{
	if (!is_file($documentRoot.'/bitrix/css/main/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
	{
		$arParams['TEMPLATE_THEME'] = 'blue';
	}
}

if (!isset($arParams['DISPLAY_MODE']) || !in_array($arParams['DISPLAY_MODE'], array('extended', 'compact')))
{
	$arParams['DISPLAY_MODE'] = 'extended';
}

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY']))
{
	$arParams['TOTAL_BLOCK_DISPLAY'] = array('top');
}

if (empty($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';
}

if (is_string($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
}

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

if ($arParams['USE_GIFTS'] === 'Y')
{
	$giftParameters = array(
		'SHOW_PRICE_COUNT' => 1,
		'PRODUCT_SUBSCRIPTION' => 'N',
		'PRODUCT_ID_VARIABLE' => 'id',
		'PARTIAL_PRODUCT_PROPERTIES' => 'N',
		'USE_PRODUCT_QUANTITY' => 'N',
		'ACTION_VARIABLE' => 'actionGift',
		'ADD_PROPERTIES_TO_BASKET' => 'Y',

		'BASKET_URL' => $APPLICATION->GetCurPage(),
		'APPLIED_DISCOUNT_LIST' => $arResult['APPLIED_DISCOUNT_LIST'],
		'FULL_DISCOUNT_LIST' => $arResult['FULL_DISCOUNT_LIST'],

		'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
		'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_SHOW_VALUE'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

		'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
		'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
		'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
		'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
		'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
		'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
		'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
		'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
		'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
		'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
		'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
		'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
		'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

		'LINE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],

		'DETAIL_URL' => isset($arParams['GIFTS_DETAIL_URL']) ? $arParams['GIFTS_DETAIL_URL'] : null
	);
}

\CJSCore::Init(array('fx', 'popup', 'ajax'));

$this->addExternalCss($templateFolder.'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');

$this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs($templateFolder.'/js/action-pool.js');
$this->addExternalJs($templateFolder.'/js/filter.js');
$this->addExternalJs($templateFolder.'/js/component.js');

$mobileColumns = isset($arParams['COLUMNS_LIST_MOBILE'])
	? $arParams['COLUMNS_LIST_MOBILE']
	: $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

$jsTemplates = new Main\IO\Directory($documentRoot.$templateFolder.'/js-templates');
/** @var Main\IO\File $jsTemplate */
foreach ($jsTemplates->getChildren() as $jsTemplate)
{
	include($jsTemplate->getPath());
}

$displayModeClass = $arParams['DISPLAY_MODE'] === 'compact' ? ' basket-items-list-wrapper-compact' : '';
if (isset($_GET['favourite'])) {
	include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket_items_delayed.php");
	return;
}
if (empty($arResult['ERROR_MESSAGE']))
{
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.gift.basket',
			'.default',
			$giftParameters,
			$component
		);
	}

	if ($arResult['BASKET_ITEM_MAX_COUNT_EXCEEDED'])
	{
		?>
		<div id="basket-item-message">
			<?=Loc::getMessage('SBB_BASKET_ITEM_MAX_COUNT_EXCEEDED', array('#PATH#' => $arParams['PATH_TO_BASKET']))?>
		</div>
		<?
	}
	?>

	<div id="basket-root" class="basket">
		<?
		if (
			$arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
			&& in_array('top', $arParams['TOTAL_BLOCK_DISPLAY'])
		)
		{
			?>
			<div class="row">
				<div class="col-xs-12" data-entity="basket-total-block"></div>
			</div>
			<?
		}
		?>

		<div class="row">
			<div class="col-xs-12">
				<div class="alert alert-warning alert-dismissable" id="basket-warning" style="display: none;">
					<span class="close" data-entity="basket-items-warning-notification-close">&times;</span>
					<div data-entity="basket-general-warnings"></div>
					<div data-entity="basket-item-warnings">
						<?=Loc::getMessage('SBB_BASKET_ITEM_WARNING')?>
					</div>
				</div>
			</div>
		</div>
        
        <div class="basket__inner" id="basket-items-list-wrapper">

            <div class="basket-top">
                <div class="basket-top__company">
                    <span>Выбрать юр.лицо, от которого будет оформлен заказ:</span>

                    <? $APPLICATION->IncludeComponent(
                        "citfact:contragent.list",
                        ".default",
                        Array()
                    ); ?>

                </div>
                
                <a href="javascript:void(0);" id="save-basket" class="basket-top__item btn--transparent">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-save'/>
                    </svg>
                    <span>Сохранить корзину</span>
                    <div class="tooltip">
                        <span class="tooltip__icon">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-tooltip-question'/>
                            </svg>
                        </span>
                        <span class="tooltip__text">
                            Корзина сохраняется в персональном разделе клиента. <br>
                            Сохраненную корзину можно добавить снова к заказу
                        </span>
                    </div>
                </a>
                <a href="<?=empty($_SERVER['HTTPS']) ? 'http' : 'https'?>://<?=$_SERVER['HTTP_HOST'] ?>/local/modules/citfact.sitecore/admin/download_excel.php" class="basket-top__item basket-top__item--download btn--transparent">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-file'/>
                    </svg>
                    <span>Скачать заказ</span>
                    <div class="tooltip">
                        <span class="tooltip__icon">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-tooltip-question'/>
                            </svg>
                        </span>
                        <span class="tooltip__text">
                            Вы можете скачать заказ в формате xls
                        </span>
                    </div>
                </a>
                
                <a href="javascript:void(0);"  class="basket-top__clear" id="clear-basket" >
                    <div class="plus plus--cross"></div>
                    <span>Очистить корзину</span>
                </a>
            </div>

            <div class="basket-items-list-container" id="basket-items-list-container">

                <div class="basket-items-list-overlay" id="basket-items-list-overlay" style="display: none;"></div>
                
                <div class="basket-items-list" id="basket-item-list">
                    <div class="basket-search-not-found" id="basket-item-list-empty-result" style="display: none;">
                        <div class="basket-search-not-found-icon"></div>
                        <div class="basket-search-not-found-text title-2">
                            <?=Loc::getMessage('SBB_FILTER_EMPTY_RESULT')?>
                        </div>
                    </div>
                    
                    <div class="basket-item basket-item--top" id="basket-item-list-titles">
                        <div class="basket-item__img"></div>
                        <div class="basket-item__description">Наименование</div>
                        <div class="basket-item__state">Остаток</div>
                        <div class="basket-item__price">Цена</div>
                        <div class="basket-item__amount">Количество в упаковке</div>
                        <div class="basket-item__count basket-item__count--cart">Количество</div>
                        <div class="basket-item__price">Сумма</div>
                        <div class="basket-item__actions"></div>
                    </div>
                    
                    <div class="basket__items" id="basket-item-table">
                        <div class="title-2 loading">
                            Корзина грузится...
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="basket__bottom">
                <div class="basket__filter b-form" data-entity="basket-filter">
                    <div class="b-form__item" data-f-item>
                        <span class="b-form__label" data-f-label>Поиск</span>
                        <input type="text"
                               data-f-field
                               data-entity="basket-filter-input">
                    </div>
                    <div class="btn btn--grey btn--big" data-entity="basket-filter-clear-btn">Очистить</div>
                </div>
                <div class="basket-total__wrapper">
                    <div class="basket__found">
                        <div class="basket-items-list-header-filter-item active"
                             data-entity="basket-items-count" data-filter="all" style="display: none;"></div>
                        <?/*<div class="basket-items-list-header-filter-item"
                       data-entity="basket-items-count" data-filter="similar" style="display: none;"></div>
                    <div class="basket-items-list-header-filter-item"
                       data-entity="basket-items-count" data-filter="warning" style="display: none;"></div>
                    <div class="basket-items-list-header-filter-item"
                       data-entity="basket-items-count" data-filter="delayed" style="display: none;"></div>
                    <div class="basket-items-list-header-filter-item"
                       data-entity="basket-items-count" data-filter="not-available" style="display: none;"></div>*/?>
                    </div>

                    <?
                    if (
                        $arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
                        && in_array('bottom', $arParams['TOTAL_BLOCK_DISPLAY'])
                    )
                    {
                        ?>
                        <div class="basket-total" data-entity="basket-total-block"></div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>
	</div>
	<?
    include($_SERVER["DOCUMENT_ROOT"] . "/local/include/modals/templateorder.php");
	if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency'))
	{
		CJSCore::Init('currency');

		?>
		<script>
			BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
		</script>
		<?
	}

    $arParams['DEFERRED_REFRESH'] = 'N'; /// Необходимо установить для того,
                                         /// чтобы не было двух запросов на обновление элементов корзины
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
    $messages = Loc::loadLanguageFile(__FILE__);
	?>
	<script>
		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
        document.addEventListener('AppLib.Ready', function (e) {
          BX.Sale.BasketComponent.init({
            result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            template: '<?=CUtil::JSEscape($signedTemplate)?>',
            signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
            siteId: '<?=$component->getSiteId()?>',
            ajaxUrl: '<?=CUtil::JSEscape($component->getPath() . '/ajax.php')?>',
            templateFolder: '<?=CUtil::JSEscape($templateFolder)?>'
          });
        });
	</script>
	<?
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.gift.basket',
			'.default',
			$giftParameters,
			$component
		);
	}
} else {
    ?>
    <div id="basket-root" class="basket">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning alert-dismissable" id="basket-warning" style="display: none;">
                    <span class="close" data-entity="basket-items-warning-notification-close">&times;</span>
                    <div data-entity="basket-general-warnings"></div>
                    <div data-entity="basket-item-warnings">
                        <?=Loc::getMessage('SBB_BASKET_ITEM_WARNING')?>
                    </div>
                </div>
            </div>
        </div>

        <div class="basket__inner" id="basket-items-list-wrapper">

            <div class="basket-items-list-container" id="basket-items-list-container">

                <div class="basket-items-list-overlay" id="basket-items-list-overlay" style="display: none;"></div>

                <div class="basket-items-list" id="basket-item-list">
                    <div class="basket-search-not-found" id="basket-item-list-empty-result" style="display: none;">
                        <div class="basket-search-not-found-icon"></div>
                        <div class="basket-search-not-found-text title-2">
                            <?=Loc::getMessage('SBB_FILTER_EMPTY_RESULT')?>
                        </div>
                    </div>


                </div>
            </div>
            <H2><?=$arResult['ERROR_MESSAGE']?></H2>
        </div>
    </div>
    <?
}