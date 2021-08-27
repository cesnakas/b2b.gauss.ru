<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;

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

$core = Core::getInstance();

$this->setFrameMode(true);
$item = $arParams['ITEM'];
$key = $arParams['KEY'];

$tShtukVUpakovke = \Citfact\SiteCore\Tools\DataAlteration::declension($item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE'], [
    'штука', 'штук', 'штук'
]);
?>
<!-- item-container -->
<div class="swiper-slide" id="<?= $arParams['AREA_ID'] ?>">
    <div class="p">

        <a href="javascript:void(0);" class="p__favorite"
           data-add2favorites data-itemId="<?= $item['ID'] ?>">
            <svg class='i-icon'>
                <use xlink:href='#icon-favorite'/>
            </svg>
        </a>

        <?if($item['PROPERTIES']['NOVINKA']['VALUE']==='Да'):?>
            <div class="tag tag--new">
                NEW
            </div>
        <?endif?>

        <a href="javascript:void(0);" title="<?=$item['NAME']?>" class="p__img">
            <? if($arParams['COUNT'] < $arParams['ON_FIRST']) { ?>
                <img src="<?=$item['DETAIL_PICTURE']['SRC']['PREVIEW']?>" <?/* 43x43px */?>
                     data-src="<?=$item['DETAIL_PICTURE']['SRC']['ORIGIN']?>"
                     alt="<?=$item['NAME']?>"
                     title="<?=$item['NAME']?>"
                     class="lazy lazy--replace">
            <? } else { ?>
                <img src="<?=$core::IMAGE_PLACEHOLDER_TRANSPARENT?>"
                     data-src="<?=$item['DETAIL_PICTURE']['SRC']['ORIGIN']?>"
                     alt="<?=$item['NAME']?>"
                     title="<?=$item['NAME']?>"
                     class="lazy">
            <? } ?>
        </a>

        <div class="p__middle">
            <a href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$item['NAME']?>" class="p__title">
                <?=$item['NAME']?>
            </a>

            <div class="p__params">
                <? if ($USER->IsAuthorized()) { ?>
                    <div class="p__param">
                        <div>Наличие</div>
                        <div>
                            <?if($item['PRODUCT']['QUANTITY']>=1000):?>
                                <span class="green">Много</span>
                            <?elseif ($item['PRODUCT']['QUANTITY'] <= 0):?>
                                <span class="red">Нет в наличии</span>
                            <?else: ?>
                                <div>
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
                    </div>
                <? } ?>
                <?foreach ($item['DISPLAY_PROPERTIES'] as $prop):?>
                    <div class="p__param">
                        <div><?=$prop['NAME']?></div>
                        <div><?=$prop['VALUE']?></div>
                    </div>
                <?endforeach;?>
            </div>


        </div>
        <div class="p__bottom">
            <div class="price">
                <div class="price__current<?= $item['PRICE_MAIN'] != ' price__current--sale' ? '' : '' ?>">
                    <?= $item['PRICE'] ?>&nbsp;₽
                </div>

                <? if ($item['PRICE_MAIN'] != '') {
                    ?>
                    <div class="price__old"><?= $item['PRICE_MAIN'] ?></div>
                    <?
                } ?>
            </div>

            <?if($arParams['IS_USER_AUTHORIZED']):?>
                <div class="btn btn--transparent" data-add2basket data-itemId="<?= $item['ID'] ?>">Купить</div>
            <?else:?>
                <a href="/local/include/modals/auth.php" data-modal="ajax" title="Купить" class="btn btn--transparent">Купить</a>
            <?endif?>

            <div class="b-count tooltip__handle" data-input-count-add2basket="">
                 <span class="tooltip__handle-text" data-kolvo-vupakovke="<?= $item['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE']['VALUE']; ?>"
                       data-itemId="<?= $item['ID'] ?>" style="display: none;">Данное количество товара в корзине не кратно упаковке.<br>В упаковке <?= $tShtukVUpakovke; ?></span>

                <button type="button" data-input-count-btn-add2basket="minus" class="b-count__btn b-count__btn--minus"></button>

                <input class="b-count__input" type="text" value="1" data-input-count-input="" disabled data-itemId="<?= $item['ID'] ?>" data-measure>

                <button type="button" data-input-count-btn-add2basket="plus" class="b-count__btn b-count__btn--plus"></button>
            </div>
        </div>
    </div>
</div>
<!-- !item-container -->


