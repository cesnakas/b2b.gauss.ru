<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$core = \Citfact\SiteCore\Core::getInstance();
?>
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="post_form" id="post_form">
    <?echo bitrix_sessid_post();?>
    <? if ($arResult['PRODUCTS']) {?>
<div class="list-wait">
    <div class="list-wait__head">
        <div class="c-t__checkbox">
            <div class="b-checkbox">
                <label for="ag-checkbox" class="b-checkbox__label">
                    <input type="checkbox" class="b-checkbox__input checkbox-ajax-all" id="ag-checkbox" data-catalog-select-all
                    <?=(count($arResult['WAIT_LIST']) == count($arResult["CHECKED"]))? "checked":''?>>
                    <span class="b-checkbox__box">
                         <span class="b-checkbox__line b-checkbox__line--short"></span>
                         <span class="b-checkbox__line b-checkbox__line--long"></span>
                    </span>
                    <span class="b-checkbox__text">Выбрать все</span>
                </label>
            </div>
        </div>
        <div class="list-wait__content">
            <div class="list-wait__name">
                Наименование
            </div>
            <div class="list-wait__status">
                Поступит на склад
            </div>
            <div class="list-wait__count">
                На складе
            </div>
            <div class="list-wait__price">
                Цена
            </div>
            <div class="list-wait__value">
                Количество
            </div>
        </div>
    </div>
    <div class="list-wait__items">
        <input type="hidden" class="b-checkbox__input checkbox-ajax"  name="id[]" value="empty">
        <? foreach ($arResult['PRODUCTS'] as $id => $product) {?>
            <div class="list-wait__item">
                <div class="c-t__checkbox">
                    <div class="b-checkbox">
                        <label for="table-<?= $product['ID'] ?>" class="b-checkbox__label">
                            <input type="checkbox" class="b-checkbox__input checkbox-ajax" id="table-<?= $product['ID'] ?>" name="id[]" value="<?= $product['ID'] ?>"
                                   data-item-id="<?= $product['ID'] ?>"
                                   <?= (in_array($product['ID'], $arResult['CHECKED'])) ? 'checked' : ''; ?>
                                   data-catalog-select-item>
                            <span class="b-checkbox__box">
                                <span class="b-checkbox__line b-checkbox__line--short"></span>
                                <span class="b-checkbox__line b-checkbox__line--long"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="list-wait__image">
                <?if ($product['PREVIEW_PICTURE']) {?>
                    <img src="<?=$product['PREVIEW_PICTURE']?>"
                         class="lazy lazy--replace"
                         alt="<?= $product['PREVIEW_PICTURE_NAME']; ?>">
                <?} else {?>
                    <img src="<?= $core::NO_PHOTO_SRC; ?>"
                         class="lazy lazy--replace"
                         alt="no_photo">
                <? } ?>
                </div>
                <div class="list-wait__content">
                    <div class="list-wait__inner">
                        <div class="list-wait__name-w">
                            <a class="list-wait__name" href="<?=$product['URL']?>">
                                <?=$product['NAME']?>
                            </a>
                            <div class="list-wait__article">
                                <?=$product['ARTICLE']?>
                            </div>
                        </div>
                        <div class="list-wait__status">
                            <div class="list-wait__status-m">Поступит на склад</div>
                            <?if ($arResult['ARRIVAL_DATES'][$product['XML_ID']]) {?>
                                <?=$arResult['ARRIVAL_DATES'][$product['XML_ID']]?>
                            <?} else {?>
                                &mdash;
                            <?}?>
                        </div>
                        <div class="list-wait__count">
                            <div class="list-wait__status-m">На складе</div>
                            <?if ($product['QUANTITY']) {?>
                                <span class="list-wait__count-number"><?=$product['QUANTITY_FORMAT']?></span>
                            <?} else {?>
                                <span>&mdash;</span>
                            <?}?>
                        </div>
                        <div class="list-wait__price">
                            <? if ($product['PRICE']) {?>
                            <div>
                                <?=$product['PRICE']?>
                            </div>
                            <span>
                                Цена за 1 штуку
                            </span>
                            <?}?>
                        </div>

                        <div class="list-wait__right">
                            <div class="list-wait__btns">
                                <div class="list-wait__value">
                                    <div class="b-count" data-input-count="<?=$arResult['WAIT_LIST'][$id]?>" id="b-count-<?=$id?>">
                                        <button type="button" data-input-count-btn="minus"
                                                class="b-count__btn b-count__btn--minus" data-elemId="<?=$id?>"></button>

                                        <input class="b-count__input count_ajax" type="text" value="1" data-input-count-input=""
                                               data-input-count-not-val data-itemId="<?=$id?>" data-measure
                                               autocomplete="on">

                                        <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"
                                                data-elemId="<?=$id?>"></button>
                                    </div>

                                    <div class="list-wait__count-info" id="basket-item-upakovka-wrap-<?=$id?>"
                                        <?if($product['PACKAGED'] == 0 || $product['PACKAGED'] == null){
                                            $product['PACKAGED'] = 1;
                                        }?>
                                        <? if ($arResult['WAIT_LIST'][$id] % $product['PACKAGED'] == 0) { ?>style="display: none !important" <?}?>>
                                        <div class="list-wait__count-text">Кол-во не кратно упаковке.</div>
                                        <span class="btn-add-box" data-id="<?=$id?>" id="button-quantity-<?=$id?>">
                                            Добавьте&nbsp;
                                            <span data-max-quantity="<?=$product['PACKAGED']?>" id="basket-item-upakovka-cnt-<?=$id?>">
                                                <?=$product['PACKAGED'] - 1?>
                                            </span>
                                            &nbsp;шт. до коробки
                                        </span>
                                    </div>
                                </div>
                                <a 
                                    data-add2basket="" 
                                    data-itemid="<?=$id?>" 
                                    data-detail="" 
                                    data-stock="<?=$product['QUANTITY']?>" 
                                    data-inbasket="<?=$product['COUNT_IN_BASKET']?>"
                                    title="Купить" 
                                    class="btn btn--transparent js-basket-add
                                    <?= $product['QUANTITY'] < 1 ? "disabled" : "" ?>">
                                    <?= $product['COUNT_IN_BASKET'] == $arResult['WAIT_LIST'][$id] ? "В корзине" : "В корзину" ?>
                                </a>
                            </div>
                            <div class="list-wait__actions">
                                <div data-itemId="<?=$id?>" class="plus plus--cross"></div>
                            </div>
                        </div>
                    </div>
                    <div class="list-wait__bottom">
                        <div class="list-wait__count">
                            <div>На складе</div>
                            <?if ($product['QUANTITY']) {?>
                                <span><?=$product['QUANTITY_FORMAT']?></span>
                            <?} else {?>
                                <span>&mdash;</span>
                            <?}?>
                        </div>
                        <div class="list-wait__status">
                            <div>Поступит на склад</div>
                            <? if ($arResult['ARRIVAL_DATES'][$product['XML_ID']]) {?>
                                <span><?=$arResult['ARRIVAL_DATES'][$product['XML_ID']]?></span>
                            <?} else {?>
                                <span>&mdash;</span>
                            <?}?>
                        </div>
                        <div class="list-wait__notification">
                            <span class="notificaction-green  <?if(!in_array($product['ID'], $arResult['CHECKED'])):?> hidden<?endif;?>" data-notification="<?=$product['ID']?>" >
                                Вы подписаны на уведомления о поступлении товара </span>
                            <span class="notificaction-red <?if(in_array($product['ID'], $arResult['CHECKED'])):?> hidden<?endif;?>" data-notification="<?=$product['ID']?>">Вы отписались от уведомлений по поступлению товара</span>
                        </div>
                    </div>
                    <div class="list-wait__extra-block">
                        <div class="list-wait__notification">
                            <span class="notificaction-green <?if(!in_array($product['ID'], $arResult['CHECKED'])):?> hidden<?endif;?>" data-notification="<?=$product['ID']?>">Вы подписаны на уведомления о поступлении товара </span>
                            <span class="notificaction-red <?if(in_array($product['ID'], $arResult['CHECKED'])):?> hidden<?endif;?>" data-notification="<?=$product['ID']?>">Вы отписались от уведомлений по поступлению товара</span>
                        </div>
                    </div>
                </div>
            </div>
        <?}?>
    </div>
</div>
<?} else {?>
    <div class="c__empty">
        <h3>Лист ожидания пуст</h3>
    </div>
<?}?>

</form>