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
$tShtukVUpakovke = \Citfact\SiteCore\Tools\DataAlteration::declension($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'], [
    'штука', 'штук', 'штук'
]);

?>
<div class="c-t__item" data-item-container="<?= $item['ID'] ?>" id="<?php echo $arParams['AREA_ID']; ?>" itemscope
     itemtype="http://schema.org/Product">

    <div class="c-t__checkbox">
        <div class="b-checkbox">
            <label for="table-<?= $item['ID'] ?>" class="b-checkbox__label">
                <input type="checkbox" class="b-checkbox__input" id="table-<?= $item['ID'] ?>"
                       data-item-id="<?= $item['ID'] ?>" data-item-price="<?= $item['MIN_PRICE']['VALUE'] ?>"
                       data-catalog-select-item>
                <span class="b-checkbox__box">
                     <span class="b-checkbox__line b-checkbox__line--short"></span>
                     <span class="b-checkbox__line b-checkbox__line--long"></span>
                </span>
            </label>
        </div>
    </div>



    <?
    $description = '';
    if ($item['PREVIEW_TEXT']) {
        $description = strip_tags($item['~PREVIEW_TEXT']);
    } else if ($item['DETAIL_TEXT']) {
        $description = strip_tags($item['~DETAIL_TEXT']);
    } else {
        $description = strip_tags($item['NAME']);
    }
    $imgPreview = '';
    $imgPreviewResize = '';
    if ($item['PREVIEW_PICTURE']["SRC"]) {
        $imgPreview = $item['PREVIEW_PICTURE']["SRC"];
        $imgPreviewResize = CFile::ResizeImageGet(
            $item['PREVIEW_PICTURE']['ID'],
            ['width' => 158, 'height' => 158]
        )['src'];
    } else if ($item['DETAIL_PICTURE']["SRC"]) {
        $imgPreview = $item['DETAIL_PICTURE']["SRC"];
        $imgPreviewResize = CFile::ResizeImageGet(
            $item['DETAIL_PICTURE']['ID'],
            ['width' => 158, 'height' => 158]
        )['src'];
    } else {
        $imgPreviewResize = \Citfact\SiteCore\Core::NO_PHOTO_SRC;
    }
    ?>
    <link itemprop="image" content="<?= $item['IMG'] ?>">
    <link itemprop="description" content="<?= $description; ?>">
    <div class="image">
        <img src="<?= $imgPreviewResize ?>" alt="">
    </div>
    <div class="c-t__content" id="c-t__content-<?= $item['ID'] ?>">

        <div class="c-t__tag">
            <? if ($item['PROPERTIES']['NOVINKA']['VALUE'] === 'Да'): ?>
                <div class="tag tag--new">
                    NEW
                </div>
            <? endif ?>
        </div>

        <div class="c-t__inner">
            <div class="c-t__name">
                <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="c-t__title" itemprop="name"
                   data-img="<?= $imgPreview ?>"><?= $item['NAME'] ?>
                </a>
                <div id="follower" class="follower">

                </div>
                <? if ($item['DISPLAY_PROPERTIES']) { ?>
                    <div class="c-t__params">
                        <? foreach ($item['DISPLAY_PROPERTIES'] as $arProp) { ?>
                            <span><?= $arProp['NAME'] ?>&nbsp;<?= $arProp['VALUE'] ?></span>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
            <div class="c-t__article">
                <div class="c-t__m">Артикул:</div>
                <?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?>
            </div>
            <div class="c-t__status">
                <div class="c-t__m">Наличие:</div>
            <? if ($arParams['IS_USER_AUTHORIZED']): ?>
                    <? if ($item['PRODUCT']['QUANTITY'] >= 1000): ?>
                        <span class="green">Много</span>
                    <? elseif ($item['PRODUCT']['QUANTITY'] <= 0): ?>
                        <span class="red">Нет в наличии
                                <span class="tooltip"> 
                                        <span class="tooltip__icon">
                                            <svg class="i-icon">
                                                <use xlink:href="#icon-tooltip-alert"></use>
                                            </svg>
                                        </span>
                                           <span class="tooltip__text">
                                            <?php if ($item['RESERV_BALANCE']['UF_DATAPRIKHODA']) { ?>
                                                <span>Ожидаемая&nbsp;дата&nbsp;поступления: <?= date("d-m-Y", strtotime($item['RESERV_BALANCE']['UF_DATAPRIKHODA'])) ?>. </span>
                                                <br>
                                            <?php }
                                             if ($item['PRODUCT']['QUANTITY'] <= 0) {?>
                                                 <div class="wait-list-block"></div>
                                             <? } ?>
                                        </span>
                                </span>
                        </span>
                    <? else: ?>
                        <span class="yellow">
                            Мало
                            <span class="tooltip">
                                <span class="tooltip__icon">
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-tooltip-catalog"></use>
                                    </svg>
                                </span>
                                <span class="tooltip__text">
                                    Свободный остаток: <?= $item['PRODUCT']['QUANTITY']; ?> шт. <br>
                            <? if ($item['RESERV_BALANCE']['UF_VREZERVE']) { ?>
                                Резервный остаток: <?= $item['RESERV_BALANCE']['UF_VREZERVE']; ?> шт.
                            <? } ?>
                                </span>
                            </span>
                        </span>
                    <? endif ?>
              <? endif; ?>
              </div>
            <div class="c-t__price">
                <div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <?
                    $priceColReverse = true;
                    $showOldPrice = true;
                    $inlineCard = true;
                    ?>
                    <div class="price__current<?= $item['PRICE_MAIN'] != ' price__current--sale' ? '' : '' ?>"><?= $item['PRICE'] ?>
                        &nbsp;Руб./шт.
                    </div>

                    <? if ($item['PRICE_MAIN'] != '') { ?>
                        <div class="price__old"><?= $item['PRICE_MAIN'] ?>&nbsp;Руб./шт.</div>
                    <? } ?>



                    <? if ($item["RAW_PRICE"]): ?>
                        <meta itemprop="price" content="<?= number_format((float)$item["RAW_PRICE"], 2, '.', '') ?>">
                        <meta itemprop="priceCurrency" content="RUB">
                    <? endif; ?>

                    <? if ($item['PRODUCT']['QUANTITY']): ?>
                        <link itemprop="availability" href="http://schema.org/InStock">
                    <? else: ?>
                        <link itemprop="availability" href="http://schema.org/PreOrder">
                    <? endif; ?>
                </div>
            </div>
            <div class="c-t__btns">
                <div class="c-t__value" style="display: block;">
                    <div class="b-count" data-input-count="" id="b-count-<?= $item['ID']?>">
                        <button type="button" data-input-count-btn="minus"
                                class="b-count__btn b-count__btn--minus" data-elemId="<?= $item['ID'] ?>"></button>

                        <input class="b-count__input" type="text" value="1" data-input-count-input=""
                               data-input-count-not-val data-itemId="<?= $item['ID'] ?>" data-measure
                               autocomplete="on">

                        <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"
                                data-elemId="<?= $item['ID'] ?>"></button>
                    </div>
                    <? //если по каким-то причинам отсутствует значение "количество в упаковке", то автоматически присваиваем единицу
                    if($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] == '') {
                        $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] = 1;
                    } 
                   ?>
                    <div class="c-t__count-info" id="basket-item-upakovka-wrap-<?= $item['ID'] ?>" <? if($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] == 1) : ?>style="display: none" <? endif; ?>>
                        <div class="c-t__count-text">Кол-во не кратно упаковке.</div>
                        <span class="btn-add-box btn--small" data-id="<?= $item['ID'] ?>" id="button-quantity-<?= $item['ID'] ?>">
                        Добавьте
                        <span data-max-quantity="<?= $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE']?>" id="basket-item-upakovka-cnt-<?= $item['ID'] ?>">
                            <? echo $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] - 1 ?>
                        </span>
                        шт. до коробки
                    </span>

                    </div>

                </div>
                <div class="c-t__btn tooltip__handle">
                    <? if ($arParams['IS_USER_AUTHORIZED']): ?>
                        <span class="tooltip__handle-text"
                              data-kolvo-vupakovke="<?= $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE']; ?>"
                              data-itemId="<?= $item['ID'] ?>" style="display: none;">Данное количество товара в корзине не кратно упаковке.<br>В упаковке <?= $tShtukVUpakovke; ?></span>

                        <div class="btn btn--transparent" data-add2basket data-add_item="true"
                             data-itemId="<?= $item['ID'] ?>">Купить
                        </div>
                    <? else: ?>
                        <a href="/local/include/modals/auth.php" data-modal="ajax" class="btn btn--transparent">Купить</a>
                    <? endif ?>
                </div>
            </div>

            <? if ($arParams['IS_USER_AUTHORIZED']): ?>
                <a href="javascript:void(0);" class="c-t__favorite <? /* active */ ?>" data-add2favorites
                   data-itemId="<?= $item['ID'] ?>">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-favorite'/>
                    </svg>
                </a>
            <? else: ?>
                <a href="/local/include/modals/auth.php" data-modal="ajax" class="c-t__favorite <? /* active */ ?>">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-favorite'/>
                    </svg>
                </a>
            <? endif ?>
        </div>

        <div class="c-t__bottom">
            <div class="c-t__params">
                <div>Артикул:&nbsp;<?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?></div>
                <? if ($item['DISPLAY_PROPERTIES']): ?>
                    <? foreach ($item['DISPLAY_PROPERTIES'] as $arProp): ?>
                        <span><?= $arProp['NAME'] ?>&nbsp;<?= $arProp['VALUE'] ?></span>
                    <? endforeach ?>
                <? endif ?>
            </div>
            <div class="c-t__stat">
                <span>Наличие:</span>
                <? if ($arParams['IS_USER_AUTHORIZED']): ?>
                    <? if ($item['PRODUCT']['QUANTITY'] >= 1000): ?>
                        <span class="green">много</span>
                    <? elseif ($item['PRODUCT']['QUANTITY'] <= 0): ?>
                        <span class="red">нет в наличии
                                <span class="tooltip">
                                        <span class="tooltip__icon">
                                            <svg class="i-icon">
                                                <use xlink:href="#icon-tooltip-alert"></use>
                                            </svg>
                                        </span>
                                        <span class="tooltip__text">
                                            <?php if ($item['RESERV_BALANCE']['UF_DATAPRIKHODA']) { ?>
                                                <span>Ожидаемая&nbsp;дата&nbsp;поступления: <?= date("d-m-Y", strtotime($item['RESERV_BALANCE']['UF_DATAPRIKHODA'])) ?>. </span>
                                                <br>
                                            <?php }
                                             if ($item['PRODUCT']['QUANTITY'] <= 0) {?>
                                                 <div class="wait-list-block"></div>
                                             <? } ?>
                                        </span>
                                    </span>
                        </span>
                    <? else: ?>
                        <span class="yellow">
                            мало
                            <span class="tooltip">
                                <span class="tooltip__icon">
                                    <svg class="i-icon">
                                        <use xlink:href="#icon-tooltip-catalog"></use>
                                    </svg>
                                </span>
                                <span class="tooltip__text">
                                    Свободный остаток: <?= $item['PRODUCT']['QUANTITY']; ?> шт. <br>
                            <? if ($item['RESERV_BALANCE']['UF_VREZERVE']) { ?>
                                Резервный остаток: <?= $item['RESERV_BALANCE']['UF_VREZERVE']; ?> шт.
                            <? } ?>
                                </span>
                            </span>
                        </span>
                    <? endif ?>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>
<? if ($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'] == 1) : ?>
    <script>
        document.querySelector(`#basket-item-upakovka-wrap-${<?= $item['ID']?>}`).style.display = 'none';
    </script>
<? endif;