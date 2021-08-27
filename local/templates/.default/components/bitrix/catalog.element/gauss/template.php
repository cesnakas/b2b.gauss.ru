<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use \Citfact\Sitecore\CatalogHelper\Price;
use Citfact\Sitecore\CatalogHelper\ListWaitHelper;

$listWaitHelper = new ListWaitHelper();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
$core = \Citfact\SiteCore\Core::getInstance();
$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => array(
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS' => $arResult['JS_OFFERS']
    )
);
unset($currencyList, $templateLibrary);


$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
    'STICKER_ID' => $mainId.'_sticker',
    'BIG_SLIDER_ID' => $mainId.'_big_slider',
    'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId.'_slider_cont',
    'OLD_PRICE_ID' => $mainId.'_old_price',
    'PRICE_ID' => $mainId.'_price',
    'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
    'PRICE_TOTAL' => $mainId.'_price_total',
    'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
    'QUANTITY_ID' => $mainId.'_quantity',
    'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
    'QUANTITY_UP_ID' => $mainId.'_quant_up',
    'QUANTITY_MEASURE' => $mainId.'_quant_measure',
    'QUANTITY_LIMIT' => $mainId.'_quant_limit',
    'BUY_LINK' => $mainId.'_buy_link',
    'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
    'COMPARE_LINK' => $mainId.'_compare_link',
    'TREE_ID' => $mainId.'_skudiv',
    'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
    'OFFER_GROUP' => $mainId.'_set_group_',
    'BASKET_PROP_DIV' => $mainId.'_basket_prop',
    'SUBSCRIBE_LINK' => $mainId.'_subscribe',
    'TABS_ID' => $mainId.'_tabs',
    'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
    'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
    $actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
        ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
        : reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer)
    {
        if ($offer['MORE_PHOTO_COUNT'] > 1)
        {
            $showSliderControls = true;
            break;
        }
    }
}
else
{
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$tShtukVUpakovke = \Citfact\SiteCore\Tools\DataAlteration::declension($actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'], [
    'шт.', 'шт.', 'шт.'
]);

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];

$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
    {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
    {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
    }
}
?>
    <div class="bx-catalog-element bx-<?=$arParams['TEMPLATE_THEME']?>" id="<?=$itemIds['ID']?>" data-wait-id="<?=$arResult['ID']?>"
         itemscope itemtype="http://schema.org/Product" data-item-container="<?= $actualItem['ID']; ?>">
        <div class="product">
            <?
            $description = '';
            if ($arResult['PREVIEW_TEXT']) {
                $description = strip_tags($arResult['~PREVIEW_TEXT']);
            } else if ($arResult['DETAIL_TEXT']) {
                $description = strip_tags($arResult['~DETAIL_TEXT']);
            } else {
                $description = strip_tags($arResult['NAME']);
            }
            ?>
            <link itemprop="description" content="<?= $description; ?>">
            <div class="product__inner">
            <!--Верстка и логика для вывода детальной картинки -->
                <div class="product__left">
                    <div class="product-slider">
                        <div class="product-slider__thumbs">
                            <? if (count($arResult['NEW_MORE_PHOTO']) + count($arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE']) + 1 >= 3): ?>
                                <div class="slider__arrow slider__arrow--prev" data-slider-a-prev="detail">
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-arrow-t"></use>
                                    </svg>
                                </div>
                            <? endif; ?>
                            <div class="swiper-container" data-slider="detailThumbs">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <? if ($arResult['DETAIL_PICTURE']['SRC']) { ?>
                                            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']['LOW']; ?>"
                                                 data-src="<?= $arResult['DETAIL_PICTURE']['SRC']['ORIGIN']; ?>"
                                                 data-src-small="<?= $arResult['DETAIL_PICTURE']['SRC']['MOBILE']; ?>"
                                                 class="lazy lazy--replace"
                                                 title="<?= $title; ?>"
                                                 alt="<?= $alt; ?>">
                                        <? } else { ?>
                                            <img src="<?= $core::NO_PHOTO_SRC; ?>"
                                                 class="lazy lazy--replace"
                                                 title="<?= $title; ?>"
                                                 alt="<?= $alt; ?>">
                                        <? } ?>
                                    </div>

                                    <? if (!empty($arResult['NEW_MORE_PHOTO'])) { ?>
                                        <? foreach ($arResult['NEW_MORE_PHOTO'] as $key => $value) { ?>
                                            <div class="swiper-slide">
                                                <img src="<?=$arResult['NEW_MORE_PHOTO']["$key"]["SRC"]['LOW']?>"
                                                     alt=""
                                                     title="">
                                            </div>
                                        <? } ?>
                                    <? } ?>

                                    <? if (!empty($arResult['IMAGES_360'])) { ?>
                                        <div class="swiper-slide">
                                            <img src="/local/client/img/360.png" alt="">
                                        </div>
                                    <? } ?>


                                    <? if (!empty($arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE'])): ?>
                                        <? foreach ($arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE'] as $key=>$value) { ?>
                                            <div class="swiper-slide">
                                                <img src="<?=$arResult['DETAIL_PICTURE']['SRC']['LOW']; ?>"
                                                     title=""
                                                     alt="">
                                                <svg class="i-icon">
                                                    <use xlink:href="#icon-youtube-red"></use>
                                                </svg>
                                            </div>
                                        <? } ?>
                                    <?endif;?>

                                    <? if (!empty($arResult['REVIEW_IN_CAROUSEL'])): ?>
                                        <? foreach ($arResult['REVIEW_IN_CAROUSEL'] as $key=>$value) { ?>
                                            <div class="swiper-slide">
                                                <img src="<?=$arResult['DETAIL_PICTURE']['SRC']['LOW']; ?>"
                                                     title=""
                                                     alt="">
                                                <svg class="i-icon">
                                                    <use xlink:href="#icon-youtube-red"></use>
                                                </svg>
                                            </div>
                                        <? } ?>
                                    <?endif;?>

                                </div>
                            </div>
                            <? if (count($arResult['NEW_MORE_PHOTO']) + count($arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE']) + 1 >= 3): ?>
                                <div class="slider__arrow slider__arrow--next" data-slider-a-next="detail">
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-arrow-t"></use>
                                    </svg>
                                </div>
                            <? endif; ?>
                        </div>
                        <div class="product-slider__inner">
                            <div class="swiper-container" data-slider="detail">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <? if ($arResult['DETAIL_PICTURE']['SRC']) { ?>
                                            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']['LOW']; ?>"
                                                 data-src="<?= $arResult['DETAIL_PICTURE']['SRC']['ORIGIN']; ?>"
                                                 data-src-small="<?= $arResult['DETAIL_PICTURE']['SRC']['MOBILE']; ?>"
                                                 class="lazy lazy--replace"
                                                 title="<?= $title; ?>"
                                                 alt="<?= $alt; ?>">
                                        <? } else { ?>
                                            <img src="<?= $core::NO_PHOTO_SRC; ?>"
                                                 class="lazy lazy--replace"
                                                 title="<?= $title; ?>"
                                                 alt="<?= $alt; ?>">
                                        <? } ?>
                                    </div>

                                    <? if (!empty($arResult['NEW_MORE_PHOTO'])) { ?>
                                        <? if (count($arResult['NEW_MORE_PHOTO']) == 1) { ?>
                                            <div class="swiper-slide">
                                                <img src="<?=$arResult['NEW_MORE_PHOTO']['0']["SRC"]['LOW']?>"
                                                       data-src="<?=$arResult['NEW_MORE_PHOTO']['0']["SRC"]['ORIGIN']?>"
                                                       data-src-small="<?=$arResult['NEW_MORE_PHOTO']["SRC"]['0']['MOBILE']?>"
                                                       class="lazy lazy--replace"
                                                >
                                            </div>
                                        <? } else {
                                            foreach ($arResult['NEW_MORE_PHOTO'] as $key=>$value) { ?>
                                                <div class="swiper-slide">
                                                    <img src="<?=$arResult['NEW_MORE_PHOTO']["$key"]["SRC"]['LOW']?>"
                                                         data-src="<?=$arResult['NEW_MORE_PHOTO']["$key"]["SRC"]['ORIGIN']?>"
                                                         data-src-small="<?=$arResult['NEW_MORE_PHOTO']["$key"]['SRC']['MOBILE']?>"
                                                         class="lazy lazy--replace"
                                                    >
                                                </div>
                                            <? }
                                        } ?>
                                    <? } ?>

                                    <? if (!empty($arResult['IMAGES_360'])) { ?>
                                        <div class="swiper-slide">
                                            <div class="cloudimage-360"
                                                 data-folder="/upload/ftp_images_360/<?= $arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']; ?>_360/"
                                                 data-filename="untitled.{index}.png"
                                                 data-amount="37"
                                                 data-autoplay
                                                 data-spin-reverse>
                                            </div>
                                        </div>
                                    <? } ?>

                                    <? if (!empty($arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE'])): ?>
                                        <?foreach ($arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE'] as $key=>$value) { ?>
                                            <div class="swiper-slide">
                                                <? echo $arResult['PROPERTIES']['YOUTUBE_VIDEO']['~VALUE'][$key]; ?>
                                            </div>
                                        <? } ?>
                                    <?endif;?>

                                    <? if (!empty($arResult['REVIEW_IN_CAROUSEL'])): ?>
                                        <?foreach ($arResult['REVIEW_IN_CAROUSEL'] as $key=>$value) { ?>
                                            <div class="swiper-slide">
                                                <? echo $value['IFRAME_LINK']; ?>
                                            </div>
                                        <? } ?>
                                    <?endif;?>
                                </div>
                                <div class="swiper-pagination slider__pagination" data-slider-p="detail"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?/*<div class="product-item-detail-slider-container" id="<?=$itemIds['BIG_SLIDER_ID']?>" style="display: none">
                    <div class="product-item-detail-slider-block
                    <?=($arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-item-detail-slider-block-square' : '')?>"
                         data-entity="images-slider-block">
                        <div class="product-item-detail-slider-images-container" data-entity="images-container">
                        </div>
                    </div>
                </div>*/?>
                <div class="product__right">
                    <div class="product-top">
                        <div class="product-top__top">
                            <? if($arResult['PROPERTIES']['NOVINKA']['VALUE']=='Да'): ?>
                                <div class="tag tag--new">
                                    NEW
                                </div>
                            <? endif; ?>
                            <h1 class="title-1">
                                <span><?= $name; ?></span>
                            </h1>
                            <div class="product-top__article">
                                <?= $arResult['PROPERTIES']['CML2_ARTICLE']['NAME'] ?>: <?= $arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']; ?>
                            </div>
                            <div class="product-storage">

                                <? if ($arParams['USER_AUTH']): ?>
                                    <div class="product-storage__item">
                                        <? if ($arResult['PRODUCT']['QUANTITY'] <= 0) { ?>
                                            <span class="red" style="margin-right: 5px">Нет в наличии </span>
                                            <span class="tooltip">
                                                <span class="tooltip__icon">
                                                    <svg class="i-icon">
                                                        <use xlink:href="#icon-tooltip-alert"></use>
                                                    </svg>
                                                </span>
                                                <span class="tooltip__text">
                                                    <span> Товар можно добавить в лист ожидания</span>
                                                </span>
                                            </span>
                                        <? } else {
                                            ?>
                                            <span>Свободный остаток:
                                                <?
                                                if ($arResult['PRODUCT']['QUANTITY'] >= 1000) {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_MANY']; // Много
                                                } else {
                                                    echo $arResult['PRODUCT']['QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'] . '.';
                                                }
                                                ?>
                                            </span>
                                            <?
                                        } ?>
                                    </div>
                                <? endif; ?>

                                <? if ($arParams['USER_AUTH']): ?>
                                    <a href="javascript:void(0);" title="Избранное"
                                       class="product-storage__item product-storage__item--favorite"
                                       data-add2favorites data-itemId="<?= $arResult['ID'] ?>">
                                    <?/* переключение активности иконки и текста на css от класса .active */?>
                                <? else: ?>
                                    <a href="/local/include/modals/auth.php?text=favorite" title="Избранное"
                                       data-modal="ajax" class="product-storage__item product-storage__item--favorite"
                                       data-add2favorites data-itemId="<?= $arResult['ID'] ?>">
                                <? endif; ?>
                                        <svg class='i-icon'>
                                            <use xlink:href='#icon-favorite'/>
                                        </svg>
                                        <span><?= GetMessage('QUANTITY_IN_FAVORITE')?></span>
                                        <span><?= GetMessage('QUANTITY_FAVORITE')?></span>
                                    </a>

                                <? if ($arParams['USER_AUTH']): ?>
                                    <div class="product-storage__item">
                                        <? if ($arResult['PRODUCT']['QUANTITY'] <= 0 || $arResult['PRODUCT']['QUANTITY'] < 1000): ?>
                                            <?php if ($arResult['RESERV_BALANCE']['UF_DATAPRIKHODA']) { ?>
                                                <span>Ожидаемая дата прихода: <span class="nowrap"><?= date("d-m-Y", strtotime($arResult['RESERV_BALANCE']['UF_DATAPRIKHODA']))?></span></span>
                                            <?php }
                                        endif; ?>

                                        <? if($arResult['PRODUCT']['QUANTITY'] > 0
                                            && $arResult['PRODUCT']['QUANTITY'] < 1000
                                            && $arResult['RESERV_BALANCE']['UF_VREZERVE']): ?>
                                            <span>
                                                    Резервный остаток:&nbsp;
                                                    <?= $arResult['RESERV_BALANCE']['UF_VREZERVE']; ?> <?=$actualItem['ITEM_MEASURE']['TITLE']?>.
                                                </span>
                                        <? endif; ?>
                                    </div>
                                <? endif; ?>

                                <? if ($arParams['USER_AUTH']): ?>
                                    <?php if (!empty($tShtukVUpakovke) && !empty($actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'])) { ?>
                                        <div class="product-storage__item">
                                            <svg class='i-icon'>
                                                <use xlink:href='#icon-storage'/>
                                            </svg>
                                            <span>Количество товара в упаковке:  <?php echo $tShtukVUpakovke; ?></span>
                                        </div>
                                    <?php } ?>
                                <? endif; ?>
                            </div>
                        </div>
                        <div class="product-top__bottom">
                            <?
                            foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName)
                            {
                                switch ($blockName)
                                {
                                    case 'rating':
                                        if ($arParams['USE_VOTE_RATING'] === 'Y')
                                        {
                                            ?>
                                                <?
                                                $APPLICATION->IncludeComponent(
                                                    'bitrix:iblock.vote',
                                                    'stars',
                                                    array(
                                                        'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                                        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                                        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                                        'ELEMENT_ID' => $arResult['ID'],
                                                        'ELEMENT_CODE' => '',
                                                        'MAX_VOTE' => '5',
                                                        'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
                                                        'SET_STATUS_404' => 'N',
                                                        'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
                                                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                                        'CACHE_TIME' => $arParams['CACHE_TIME']
                                                    ),
                                                    $component,
                                                    array('HIDE_ICONS' => 'Y')
                                                );
                                                ?>
                                            <?
                                        }

                                        break;
                                    case 'priceRanges':
                                        if ($arParams['USE_PRICE_COUNT'])
                                        {
                                            $showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
                                            $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
                                            ?>
                                                <?=$showRanges ? '' : 'style="display: none;"'?>
                                                 data-entity="price-ranges-block">
                                                <div class="product-item-detail-info-container-title">
                                                    <?=$arParams['MESS_PRICE_RANGES_TITLE']?>
                                                    <span data-entity="price-ranges-ratio-header">
                                                    (<?=(Loc::getMessage(
                                                            'CT_BCE_CATALOG_RATIO_PRICE',
                                                            array('#RATIO#' => ($useRatio ? $measureRatio : '1').' '.$actualItem['ITEM_MEASURE']['TITLE'])
                                                        ))?>)
                                                </span>
                                                </div>
                                                <dl class="product-item-detail-properties" data-entity="price-ranges-body">
                                                    <?
                                                    if ($showRanges)
                                                    {
                                                        foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range)
                                                        {
                                                            if ($range['HASH'] !== 'ZERO-INF')
                                                            {
                                                                $itemPrice = false;

                                                                foreach ($arResult['ITEM_PRICES'] as $itemPrice)
                                                                {
                                                                    if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
                                                                    {
                                                                        break;
                                                                    }
                                                                }

                                                                if ($itemPrice)
                                                                {
                                                                    ?>
                                                                    <dt>
                                                                        <?
                                                                        echo Loc::getMessage(
                                                                                'CT_BCE_CATALOG_RANGE_FROM',
                                                                                array('#FROM#' => $range['SORT_FROM'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
                                                                            ).' ';

                                                                        if (is_infinite($range['SORT_TO']))
                                                                        {
                                                                            echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                                                                        }
                                                                        else
                                                                        {
                                                                            echo Loc::getMessage(
                                                                                'CT_BCE_CATALOG_RANGE_TO',
                                                                                array('#TO#' => $range['SORT_TO'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
                                                                            );
                                                                        }
                                                                        ?>
                                                                    </dt>
                                                                    <dd><?=($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE'])?></dd>
                                                                    <?
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </dl>
                                            <?
                                            unset($showRanges, $useRatio, $itemPrice, $range);
                                        }

                                        break;
                                    case 'quantityLimit':
                                        if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
                                        {
                                            if ($haveOffers)
                                            {
                                                ?>
                                                <div id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
                                                    <div class="product-item-detail-info-container-title">
                                                        <?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
                                                        <span class="product-item-quantity" data-entity="quantity-limit-value"></span>
                                                    </div>
                                                </div>
                                                <?
                                            }
                                            else
                                            {
                                                if (
                                                    $measureRatio
                                                    && (float)$actualItem['PRODUCT']['QUANTITY'] > 0
                                                    && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                                                    && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                                                )
                                                {
                                                    ?>
                                                    <div id="<?=$itemIds['QUANTITY_LIMIT']?>">
                                                        <div class="product-item-detail-info-container-title">
                                                            <?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
                                                            <span class="product-item-quantity" data-entity="quantity-limit-value">
                                                            <?
                                                            if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
                                                            {
                                                                if ((float)$actualItem['PRODUCT']['QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                                                                {
                                                                    echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                                }
                                                                else
                                                                {
                                                                    echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                                }
                                                            }
                                                            else
                                                            {
                                                                echo $actualItem['PRODUCT']['QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
                                                            }
                                                            ?>
                                                        </span>
                                                        </div>
                                                    </div>
                                                    <?
                                                }
                                            }
                                        }

                                        break;
                                    case 'quantity':
                                        if ($arParams['USE_PRODUCT_QUANTITY'])
                                        {
                                            ?>
                                                <div class="product-top__item">
                                                    <span class="product-top__item-t">Ваша цена:</span>
                                                    <div class="price">
                                                        <div class="price__current">
                                                            <?=$price['PRINT_RATIO_PRICE']?>
                                                            / <?=$actualItem['ITEM_MEASURE']['TITLE']?>.
                                                        </div>
                                                        <? if ($arParams['USER_AUTH']): ?>
                                                            <div class="price__info">
                                                                <?
                                                                foreach ($arResult['EXTRA_PRICES'] as $idPrice => $value) {
                                                                    if (!empty($arResult['EXTRA_PRICES'][$idPrice]['PRICE'])) { ?>
                                                                        <div>
                                                                            <span><?= $arResult['EXTRA_PRICES'][$idPrice]['LABEL'] ?></span>
                                                                            <span><?= $arResult['EXTRA_PRICES'][$idPrice]['PRICE'] ?> ₽&nbsp;&nbsp;&nbsp;/
                                                                        <?= $actualItem['ITEM_MEASURE']['TITLE'] ?>.</span>
                                                                        </div>
                                                                    <? }
                                                                } ?>
                                                            </div>
                                                        <?endif; ?>
                                                    </div>
                                                </div>
                                                <div class="product-top__item">
                                                    <div class="basket-item__text" id="basket-item-upakovka-wrap-<?= $actualItem['ID'] ?>" <? if($actualItem['PRODUCT']['QUANTITY'] == 0) : ?>style="display: none" <? endif; ?>>
                                                        <span class="btn-add-box btn--small btn--grey" data-id="<?= $actualItem['ID'] ?>" id="button-quantity-<?= $actualItem['ID'] ?>">
                                                            Добавьте
                                                            <span data-max-quantity="<?= $actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE']?>" id="basket-item-upakovka-cnt-<?= $actualItem['ID'] ?>">
                                                                <? echo $actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] - 1 ?>
                                                            </span>
                                                            шт. до коробки
                                                        </span>
                                                    </div>
                                                    <div class="b-count" data-input-count="" id="b-count-<?= $actualItem['ID']?>">
                                                        <button type="button" data-input-count-btn="minus" class="b-count__btn b-count__btn--minus" data-elemId="<?= $arResult['ID'] ?>"></button>

                                                        <input class="b-count__input" type="text" value="1"
                                                               data-input-count-input="" data-input-count-not-val
                                                               data-itemId="<?= $arResult['ID'] ?>" data-measure>
                                                        <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus" data-elemId="<?= $arResult['ID'] ?>"></button>
                                                    </div>
                                                </div>
                                                <div class="product-top__item">
                                                    <span class="product-top__item-t"><?= GetMessage('CT_BCE_TOTAL'); ?>:</span>
                                                    <div class="price">
                                                        <div class="price__current" data-price-sum="<?=$price['RATIO_PRICE']?>"
                                                             data-itemId="<?= $arResult['ID'] ?>">
                                                            <?=$price['PRINT_RATIO_PRICE']?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?
                                        }
                                        break;
                                    case 'buttons':
                                        ?>
                                    <div class="product-top__buttons <?=($arResult['PRODUCT']['QUANTITY'] <= 0)?'product-top__buttons--wl': ''?>"> 
                                        <? if($arParams['USER_AUTH']): ?>
                                            <div class="btn btn-link btn--transparent btn--big tooltip__handle">
                                                <?php if (!empty($actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'])) { ?>
                                                    <span class="tooltip__handle-text" data-kolvo-vupakovke="<?= $actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE']; ?>"
                                                          data-itemId="<?= $arResult['ID'] ?>" style="display: none;">Данное количество товара в корзине не кратно упаковке.<br>В упаковке <?= $tShtukVUpakovke; ?></span>
                                                <?php } ?>

                                                <a data-add2basket data-itemId="<?= $arResult['ID'] ?>"
                                                   data-detail href="javascript:void(0);" title="Купить">
                                                    <span>Купить</span>
                                                </a>
                                            </div>
                                            <?if ($arResult['PRODUCT']['QUANTITY'] <= 0) {?>
                                                <div class="wait-list-block"></div>
                                            <?}?>
                                        <? else: ?>
                                            <div class="btn btn-link btn--transparent btn--big tooltip__handle">
                                                <a href="/local/include/modals/auth.php?text=auth"
                                                   data-modal="ajax"
                                                   title="<?=$arParams['MESS_BTN_ADD_TO_BASKET']?>">
                                                    <span><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></span>
                                                </a>
                                            </div>
                                        <? endif; ?>
                                            <?
                                            if ($showSubscribe)
                                            {
                                                ?>
                                                    <?
                                                    $APPLICATION->IncludeComponent(
                                                        'bitrix:catalog.product.subscribe',
                                                        '',
                                                        array(
                                                            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                                            'PRODUCT_ID' => $arResult['ID'],
                                                            'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                            'BUTTON_CLASS' => 'btn btn-default product-item-detail-buy-button',
                                                            'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                                            'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                        ),
                                                        $component,
                                                        array('HIDE_ICONS' => 'Y')
                                                    );
                                                    ?>
                                                <?
                                            }
                                            ?>
                                    </div>
                                        <?
                                        break;
                                }
                            }

                            if ($arParams['DISPLAY_COMPARE'])
                            {
                                ?>
                                <div class="product-item-detail-compare-container">
                                    <div class="product-item-detail-compare">
                                        <div class="checkbox">
                                            <label id="<?=$itemIds['COMPARE_LINK']?>">
                                                <input type="checkbox" data-entity="compare-checkbox">
                                                <span data-entity="compare-title"><?=$arParams['MESS_BTN_COMPARE']?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-tabs" data-tab-group>

                <div class="product-tabs__head" data-tab-header>
                    <a href="javascript:void(0);" class="product-tabs__link active" data-tab-btn="characteristics">Характеристики</a>
                    <?if(!empty($arResult['PROPERTIES']['REVIEWS']['VALUE']) || !empty($arResult['PROPERTIES']['UF_SECTION_REVIEW']['VALUE']) || !empty($arResult['REVIEW_IN_REVIEWS_BLOCK'])):?>
                        <a href="javascript:void(0);" class="product-tabs__link" data-tab-btn="reviews">Обзоры</a>
                    <?endif;?>
                    <?if(!empty($arResult['DETAIL_TEXT'])):?>
                        <a href="javascript:void(0);" class="product-tabs__link" data-tab-btn="description">Описание</a>
                    <?endif;?>
<!-- Разметка для вкладки упаковки-->
                    <a href="javascript:void(0);" class="product-tabs__link" data-tab-btn="pack">Упаковка</a>
                    <? if (!empty($arResult['DOCUMENTATIONS'])): ?>
                        <a href="javascript:void(0);" class="product-tabs__link" data-tab-btn="documentation">
                            <?= GetMessage('CT_BCE_CATALOG_DOCUMENTATION') ?>
                        </a>
                    <?endif;?>
                    <a href="javascript:void(0);" class="product-tabs__link" data-tab-btn="d-p">Оплата и доставка</a>
                </div>

                <div class="product-tabs__body" data-tab-content>
                    <div class="product-tabs__content active" data-tab-body="characteristics">
                        <div class="product__params">
                            <? foreach ($arResult['ITEM_PROPERTIES'] as $arItem): ?>
                                <?php if (!empty($arItem['VALUE'])): ?>
                                    <div>
                                        <span><?= $arItem['NAME'] ?>:</span>&nbsp;&nbsp;
                                        <span><?= $arItem['VALUE'] ?></span>
                                    </div>
                                <?php endif; ?>
                            <? endforeach; ?>
                        </div>
                    </div>

                    <div class="product-tabs__content" data-tab-body="reviews">
                        <!--Здесь получаем обзоры. Первое условие - для элементов, второе - для разделов, если нет обзоров для элементов-->
                        <div class="product-reviews">
                            <? if (!empty($arResult['REVIEWS'])) { ?>
                                <? foreach ($arResult['REVIEWS'] as $key=>$value) { ?>
                                    <a class="product-reviews__i" href="<?=$arResult['REVIEWS'][$key]['UF_LINK']?>" target="_blank">
                                        <?if(!empty($arResult['REVIEWS'][$key]["UF_FILE"])):?>
                                            <img src="<?=CFile::GetPath($arResult['REVIEWS'][$key]["UF_FILE"])?>"/>
                                        <?else:?>
                                            <img src="<?= $core::NO_PHOTO_SRC; ?>">
                                    <?endif;?>
                                        <span><?=$arResult['REVIEWS'][$key]['UF_NAME']?></span>
                                    </a>
                                <? }
                            } ?>
                            <? if (empty($arResult['REVIEWS']) && !empty($arResult['UF_SECTION_REVIEW'])) {
                                foreach ($arResult['UF_SECTION_REVIEW'] as $key=>$value ) { ?>
                                    <a class="product-reviews__i" href="<?=$arResult['REVIEWS'][$key]['UF_LINK']?>" target="_blank">
                                        <?if(!empty($arResult['REVIEWS'][$key]["UF_FILE"])):?>
                                            <img src="<?=CFile::GetPath($arResult['REVIEWS'][$key]["UF_FILE"])?>"/>
                                        <?else:?>
                                            <img src="<?= $core::NO_PHOTO_SRC; ?>">
                                        <?endif;?>
                                        <span><?=$arResult['REVIEWS'][$key]['UF_NAME']?></span>
                                    </a> 
                                <? }
                            }?>
                            <? if (!empty($arResult['REVIEW_IN_REVIEWS_BLOCK'])) {
                                foreach ($arResult['REVIEW_IN_REVIEWS_BLOCK'] as $key=>$review ) { ?>
                                    <a class="product-reviews__i" href="<?=$review['UF_LINK']?>" target="_blank">
                                        <?if($review['PICTURE']):?>
                                            <img src="<?=$review['PICTURE']?>"/>
                                        <?else:?>
                                            <img src="<?= $core::NO_PHOTO_SRC; ?>">
                                        <?endif;?>
                                        <span><?=$review['UF_NAME']?></span>
                                    </a>
                                <? }
                            }?>
                        </div>
                    </div>

                    <div class="product-tabs__content" data-tab-body="description">
                        <div class="product__description">
                            <? if ($arResult['DETAIL_TEXT']): ?>
                                <div class="title-1">
                                    <span><?= $arParams['MESS_DESCRIPTION_TAB'] ?></span>
                                </div>
                                <p>
                                    <?= $arResult['DETAIL_TEXT'] ?>
                                </p>
                            <? endif; ?>
                        </div>
                    </div>

<!--                    Разметка для вкладки упаковки-->

                    <div class="product-tabs__content" data-tab-body="pack">
                        <div class="product-pack">
                            <div class="product-pack__column">
                                <div class="product-pack__title">Индивидуальная упаковка</div>
                                <div class="product-pack__items">
                                    <div>
                                        <span>Кол-во:</span>&nbsp;&nbsp;
                                        <span>1 шт.</span>
                                    </div>
                                    <div>
                                        <span>Вес:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['INDIVIDUAL_PACKAGE_WEIGHT']['VALUE']?> кг</span>
                                    </div>
                                    <div>
                                        <span>Объем:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['INDIVIDUAL_PACKAGE_VOLUME']['VALUE']?> м<sup>3</sup></span>
                                    </div>
                                    <div>
                                        <span>Длина:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['INDIVIDUAL_PACKAGE_LENGTH']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Ширина:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['INDIVIDUAL_PACKAGE_WIDTH']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Высота:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['INDIVIDUAL_PACKAGE_HEIGHT']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Штрихкод:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['INDIVIDUAL_PACKAGE_BARCODE']['VALUE']?> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-pack__column">
                                <div class="product-pack__title">Групповая упаковка</div>
                                <div class="product-pack__items">
                                    <div>
                                        <span>Кол-во:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_QUANTITY']['VALUE']?> шт.</span>
                                    </div>
                                    <div>
                                        <span>Вес:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_WEIGHT']['VALUE']?> кг</span>
                                    </div>
                                    <div>
                                        <span>Объем:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_VOLUME']['VALUE']?> м<sup>3</sup></span>
                                    </div>
                                    <div>
                                        <span>Длина:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_LENGTH']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Ширина:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_WIDTH']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Высота:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_HEIGHT']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Штрихкод:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['GROUP_PACKAGE_BARCODE']['VALUE']?> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-pack__column">
                                <div class="product-pack__title">Транспортная упаковка</div>
                                <div class="product-pack__items">
                                    <div>
                                        <span>Кол-во:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_QUANTITY']['VALUE']?> шт.</span>
                                    </div>
                                    <div>
                                        <span>Вес:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_WEIGHT']['VALUE']?> кг</span>
                                    </div>
                                    <div>
                                        <span>Объем:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_VOLUME']['VALUE']?> м<sup>3</sup></span>
                                    </div>
                                    <div>
                                        <span>Длина:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_LENGTH']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Ширина:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_WIDTH']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Высота:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_HEIGHT']['VALUE']?> мм</span>
                                    </div>
                                    <div>
                                        <span>Штрихкод:</span>&nbsp;&nbsp;
                                        <span><?=$arResult['PROPERTIES']['TRANSPORT_PACKAGE_BARCODE']['VALUE']?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product-tabs__content" data-tab-body="documentation">
                        <? if (!empty($arResult['DOCUMENTATIONS'])): ?>
                            <div class="product-docs">
                                <? foreach ($arResult['DOCUMENTATIONS'] as $DOCUMENTATION): ?>
                                    <div class="product-docs__i">
                                        <span class="product-docs__text"><?= $DOCUMENTATION['UF_NAZNACHENIE'] ?></span>
                                        <a class="product-docs__btn" href="<?= $DOCUMENTATION['FILE'] ?>" download="<?= $DOCUMENTATION['UF_IMYAFAYLA'] ?>">
                                            <svg class="i-icon">
                                                <use xlink:href="#icon-file"/>
                                            </svg>
                                            <span>Скачать</span>
                                        </a>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        <? endif; ?>
                    </div>

                    <div class="product-tabs__content" data-tab-body="d-p">
                        <div class="product__d-p">
                            <div>
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    ".default",
                                    array(
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/include/areas/catalog/element/delivery_list.php"
                                    ),
                                    false
                                ); ?>
                            </div>
                            <div>
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    ".default",
                                    array(
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/include/areas/catalog/element/paysystems_list.php"
                                    ),
                                    false
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <meta itemprop="name" content="<?=$name?>" />
        <meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
        <?
        if ($haveOffers)
        {
            foreach ($arResult['JS_OFFERS'] as $offer)
            {
                $currentOffersList = array();

                if (!empty($offer['TREE']) && is_array($offer['TREE']))
                {
                    foreach ($offer['TREE'] as $propName => $skuId)
                    {
                        $propId = (int)substr($propName, 5);

                        foreach ($skuProps as $prop)
                        {
                            if ($prop['ID'] == $propId)
                            {
                                foreach ($prop['VALUES'] as $propId => $propValue)
                                {
                                    if ($propId == $skuId)
                                    {
                                        $currentOffersList[] = $propValue['NAME'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                $offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
                ?>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?=htmlspecialcharsbx(implode('/', $currentOffersList))?>" />
				<meta itemprop="price" content="<?=$offerPrice['RATIO_PRICE']?>" />
				<meta itemprop="priceCurrency" content="<?=$offerPrice['CURRENCY']?>" />
				<link itemprop="availability" href="http://schema.org/<?=($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
			</span>
                <?
            }

            unset($offerPrice, $currentOffersList);
        }
        else
        {
            ?>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?=$price['RATIO_PRICE']?>" />
			<meta itemprop="priceCurrency" content="<?=$price['CURRENCY']?>" />
			<link itemprop="availability" href="http://schema.org/<?=($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
		</span>
            <?
        }
        ?>
    </div>
<?
if ($haveOffers)
{
    $offerIds = array();
    $offerCodes = array();

    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

    foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
    {
        $offerIds[] = (int)$jsOffer['ID'];
        $offerCodes[] = $jsOffer['CODE'];

        $fullOffer = $arResult['OFFERS'][$ind];
        $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

        $strAllProps = '';
        $strMainProps = '';
        $strPriceRangesRatio = '';
        $strPriceRanges = '';

        if ($arResult['SHOW_OFFERS_PROPS'])
        {
            if (!empty($jsOffer['DISPLAY_PROPERTIES']))
            {
                foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
                {
                    $current = '<dt>'.$property['NAME'].'</dt><dd>'.(
                        is_array($property['VALUE'])
                            ? implode(' / ', $property['VALUE'])
                            : $property['VALUE']
                        ).'</dd>';
                    $strAllProps .= $current;

                    if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
                    {
                        $strMainProps .= $current;
                    }
                }

                unset($current);
            }
        }

        if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
        {
            $strPriceRangesRatio = '('.Loc::getMessage(
                    'CT_BCE_CATALOG_RATIO_PRICE',
                    array('#RATIO#' => ($useRatio
                            ? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
                            : '1'
                        ).' '.$measureName)
                ).')';

            foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
            {
                if ($range['HASH'] !== 'ZERO-INF')
                {
                    $itemPrice = false;

                    foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
                    {
                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
                        {
                            break;
                        }
                    }

                    if ($itemPrice)
                    {
                        $strPriceRanges .= '<dt>'.Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_FROM',
                                array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
                            ).' ';

                        if (is_infinite($range['SORT_TO']))
                        {
                            $strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                        }
                        else
                        {
                            $strPriceRanges .= Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_TO',
                                array('#TO#' => $range['SORT_TO'].' '.$measureName)
                            );
                        }

                        $strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';
                    }
                }
            }

            unset($range, $itemPrice);
        }

        $jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
        $jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
        $jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
        $jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
    }

    $templateData['OFFER_IDS'] = $offerIds;
    $templateData['OFFER_CODES'] = $offerCodes;
    unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

    $jsParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'ALT' => $alt,
            'TITLE' => $title,
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null
        ),
        'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
        'VISUAL' => $itemIds,
        'DEFAULT_PICTURE' => array(
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
        ),
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'NAME' => $arResult['~NAME'],
            'CATEGORY' => $arResult['CATEGORY_PATH']
        ),
        'BASKET' => array(
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        ),
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $skuProps
    );
}
else
{
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
    {
        ?>
        <div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
            {
                foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo)
                {
                    ?>
                    <input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
                    <?
                    unset($arResult['PRODUCT_PROPERTIES'][$propId]);
                }
            }

            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties)
            {
                ?>
                <table>
                    <?
                    foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo)
                    {
                        ?>
                        <tr>
                            <td><?=$arResult['PROPERTIES'][$propId]['NAME']?></td>
                            <td>
                                <?
                                if (
                                    $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
                                    && $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
                                )
                                {
                                    foreach ($propInfo['VALUES'] as $valueId => $value)
                                    {
                                        ?>
                                        <label>
                                            <input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]"
                                                   value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"checked"' : '')?>>
                                            <?=$value?>
                                        </label>
                                        <br>
                                        <?
                                    }
                                }
                                else
                                {
                                    ?>
                                    <select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]">
                                        <?
                                        foreach ($propInfo['VALUES'] as $valueId => $value)
                                        {
                                            ?>
                                            <option value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"selected"' : '')?>>
                                                <?=$value?>
                                            </option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                    <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
            ?>
        </div>
        <?
    }

    $jsParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'ALT' => $alt,
            'TITLE' => $title,
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null
        ),
        'VISUAL' => $itemIds,
        'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'PICT' => reset($arResult['MORE_PHOTO']),
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
            'ITEM_PRICES' => $arResult['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
            'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
            'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
            'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
            'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
            'MAX_QUANTITY' => $arResult['PRODUCT']['QUANTITY'],
            'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'CATEGORY' => $arResult['CATEGORY_PATH']
        ),
        'BASKET' => array(
            'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        )
    );
    unset($emptyProductProperties);
}

if ($arParams['DISPLAY_COMPARE'])
{
    $jsParams['COMPARE'] = array(
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH']
    );
}

$jsParams['COUNT_PACK'] = $arResult['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'];
?>
    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>',
            QUANTITY_TITLE: '<?=GetMessageJS('QUANTITY_TITLE')?>',
        });

        var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<? if ($actualItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] == 1) : ?>
    <script>
        document.querySelector(`#basket-item-upakovka-wrap-${<?= $actualItem['ID']?>}`).style.display = 'none';
    </script>
<? endif;
unset($actualItem, $itemIds, $jsParams);