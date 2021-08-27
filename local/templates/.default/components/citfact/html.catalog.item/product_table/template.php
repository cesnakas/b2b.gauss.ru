<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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
$item = $arParams['ITEM'];
global $USER;
$isPageFavorite = ($arParams['IS_PAGE_FAVOURITE'] == 'Y');
$tShtukVUpakovke = \Citfact\SiteCore\Tools\DataAlteration::declension($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'], [
    'штука', 'штук', 'штук'
]);

?>
<div class="p" data-item-container="<?= $item['ID'] ?>" id="<?php echo $arParams['AREA_ID']; ?>" itemscope itemtype="http://schema.org/Product">
    <?if($arParams['IS_USER_AUTHORIZED']):?>
        <a href="javascript:void(0);" class="p__favorite"
           data-add2favorites
           <? if ($isPageFavorite) { ?>
               data-remove-item="1"
           <? } ?>

           data-itemId="<?= $item['ID'] ?>">
            <? if ($isPageFavorite) { ?>
                <span class="tooltip">
                    <span class="tooltip__icon">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-favorite'/>
                        </svg>
                    </span>
                    <span class="tooltip__text" style="display: none;">
                        Удалить из избраннного
                    </span>
                </span>
            <? } else { ?>
                <svg class='i-icon'>
                    <use xlink:href='#icon-favorite'/>
                </svg>
            <? } ?>
        </a>
    <?else:?>
        <a href="/local/include/modals/auth.php" data-modal="ajax" class="p__favorite <?/* active */?>" >
            <svg class='i-icon'>
                <use xlink:href='#icon-favorite'/>
            </svg>
        </a>
    <?endif?>
    
    <?if($item['PROPERTIES']['NOVINKA']['VALUE']==='Да'):?>
        <div class="tag tag--new">
            NEW
        </div>
    <?endif?>

    <?
    $description = '';
    if ($item['PREVIEW_TEXT']) {
        $description = strip_tags($item['~PREVIEW_TEXT']);
    } else if ($item['DETAIL_TEXT']) {
        $description = strip_tags($item['~DETAIL_TEXT']);
    } else {
        $description = strip_tags($item['NAME']);
    }
    ?>
    <link itemprop="image" content="<?=$item['IMG']?>">
    <link itemprop="description" content="<?= $description; ?>">
    <div class="p__top">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" rel="nofollow" title="<?= $item['NAME'] ?>" class="p__img">
            <img src="<?=$item['IMG']?>"
                 data-src="<?=$item['IMG']?>"
                 title="<?= $item['NAME'] ?>"
                 alt="<?= $item['NAME'] ?>"
                 class="lazy lazy--replace">
        </a>
    </div>

    <div class="p__middle">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $item['NAME'] ?>" class="p__title" itemprop="name">
            <?= $item['NAME'] ?>
        </a>
        <div class="p__params">
            <div class="p__param">
                <div>Наличие</div>
                <? if ($USER->IsAuthorized()) { ?>
                    <div>
                        <?if($item['PRODUCT']['QUANTITY']>=1000):?>
                            <span class="green">Много</span>
                        <?elseif ($item['PRODUCT']['QUANTITY'] <= 0):?>
                            <span class="red">Нет в наличии
                                    <span class="tooltip">
                                        <span class="tooltip__icon">
                                            <svg class="i-icon">
                                                <use xlink:href="#icon-tooltip-alert"></use>
                                            </svg>
                                        </span>
                                        <span class="tooltip__text">
                                             <?php if ($item['RESERV_BALANCE']['UF_DATAPRIKHODA']) { ?>
                                                <span>Ожидаемая&nbsp;дата&nbsp;поступления: <?= date("d-m-Y", strtotime($item['RESERV_BALANCE']['UF_DATAPRIKHODA'])) ?>.</span>
                                                <br>
                                             <?php }
                                             if ($item['PRODUCT']['QUANTITY'] <= 0) {?>
                                                 <div class="wait-list-block"></div>
                                             <? } ?>
                                        </span>
                                    </span>
                            </span>
                        <?else: ?>
                            <div class="yellow">
                                Мало
                                <span class="tooltip">
                                    <span class="tooltip__icon">
                                        <svg class='i-icon'>
                                            <use xlink:href='#icon-tooltip-alert'/>
                                        </svg>
                                    </span>
                                    <span class="tooltip__text" style="display: none;">
                                        Свободный остаток: <?= $item['PRODUCT']['QUANTITY']; ?> шт. <br>
                                        <? if ($item['RESERV_BALANCE']['UF_VREZERVE']) { ?>
                                            Резервный остаток: <?= $item['RESERV_BALANCE']['UF_VREZERVE']; ?> шт.
                                        <? } ?>
                                    </span>
                                </span>
                            </div>
                        <?endif?>
                    </div>
                <? } ?>
            </div>
            <div class="p__param">
                <div>
                    Артикул:
                </div>
                <div><?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE'];?></div>
            </div>
            <?php

            $propertiesCount = 0;

            foreach ($item['DISPLAY_PROPERTIES'] as $arProp) {
                $propertiesCount++;

                if ($propertiesCount <= 3) {?>
                    <div class="p__param">
                        <div><?=$arProp['NAME']?></div>
                        <div><?=$arProp['VALUE']?></div>
                    </div>
                <?php } ?>

            <?php } ?>
        </div>
    </div>
    <div class="p__bottom">
    
        <?if ($item['PRICE'] != ''){?>
            <div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <?
                $priceColReverse = true;
                $showOldPrice = true;
                $inlineCard = true;
                ?>
                <div class="price__current<?= $item['PRICE_MAIN'] != ' price__current--sale' ? '' : ''?>">
                    <?=$item['PRICE']?>&nbsp;₽
                </div>
            
                <?if ($item['PRICE_MAIN'] != ''){?>
                    <div class="price__old"><?=$item['PRICE_MAIN']?>&nbsp;₽</div>
                <?}?>
            
                <?if ($item["RAW_PRICE"]):?>
                    <meta itemprop="price" content="<?=number_format((float)$item["RAW_PRICE"], 2, '.', '')?>">
                    <meta itemprop="priceCurrency" content="RUB">
                <?endif;?>

                <?if ($item['PRODUCT']['QUANTITY']):?>
                    <link itemprop="availability" href="http://schema.org/InStock">
                <?else:?>
                    <link itemprop="availability" href="http://schema.org/PreOrder">
                <?endif;?>
            </div>

            <div class="p__btns">
                    <?if($arParams['IS_USER_AUTHORIZED']):?>
                        <div class="btn btn--transparent" id="btn--transparent-<?= $item['ID'] ?>" data-add2basket data-itemId="<?= $item['ID'] ?>">Купить</div>
                    <?else:?>
                        <a href="/local/include/modals/auth.php" data-modal="ajax" title="Купить" class="btn btn--transparent">Купить</a>
                    <?endif?>
                    <div class="p__btns-w">
                        <?
                        //если по каким-то причинам отсутствует значение "количество в упаковке", то автоматически присваиваем единицу
                        if($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] == '') {
                            $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] = 1;
                        }
                        ?>
                        <?$countBottomText = true;?> 
                        <div class="b-count tooltip__handle"  id="b-count-<?= $item['ID']?>">
                        <span class="tooltip__handle-text" data-kolvo-vupakovke="<?=$item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE']; ?>"  data-itemId="<?= $item['ID'] ?>" style="display: none;">
                            Данное количество товара в корзине не кратно упаковке.<br>В упаковке <?= $tShtukVUpakovke; ?>
                        </span>
                            <div class="b-count" data-input-count="" id="b-count-<?= $item['ID']?>">
                                <button type="button" data-input-count-btn="minus"
                                        class="b-count__btn b-count__btn--minus" data-elemId="<?= $item['ID'] ?>"></button>

                                <input class="b-count__input" type="text" value="1" data-input-count-input=""
                                       data-input-count-not-val data-itemId="<?= $item['ID'] ?>" data-measure
                                       autocomplete="on">

                                <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"
                                        data-elemId="<?= $item['ID'] ?>"></button>
                            </div>
                        </div>
                        <div class="basket-item__text" id="basket-item-upakovka-wrap-<?= $item['ID'] ?>" <?if($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] == 1) : ?>style="display: none" <? endif; ?>>
                            <div>Кол-во не кратно упаковке.</div>
                            <span class="btn-add-box btn--small btn--grey" data-id="<?= $item['ID'] ?>" id="button-quantity-<?= $item['ID'] ?>">
                                Добавьте
                                <span data-max-quantity="<?= $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] ?>" id="basket-item-upakovka-cnt-<?= $item['ID'] ?>">
                                    <? echo $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] - 1 ?>
                                </span>
                        шт. до коробки
                    </span>
                        </div>
                    </div>

            </div>
        <?}?>
    </div>
</div>
