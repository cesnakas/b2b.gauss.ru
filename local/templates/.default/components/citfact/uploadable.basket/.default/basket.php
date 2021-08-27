<?php

use MasterWatt\Core\Sale\Basket\Upload\UploadableBasket;
use MasterWatt\Core\Sale\Basket\Upload\UploadableProduct;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var array $arParams
 * @var array $arResult массив результатов работы компонента
 * @var string $templateFile путь к шаблону относительно корня сайта
 * @var array $arLangMessages массив языковых сообщений шаблона
 * @var string $templateFolder путь к папке с шаблоном от DOCUMENT_ROOT
 * @var string $parentTemplateFolder путь относительно корня сайта к папке шаблона комплексного компонента
 * @var CBitrixComponent $component ссылка на текущий вызванный компонент
 * @var CBitrixComponentTemplate $this ссылка на текущий шаблон
 * @var string $templateName имя шаблона компонента
 * @var string $componentPath путь к папке с компонентом от DOCUMENT_ROOT
 * @var array $templateData массив для записи
 */

?>

<div id="upload-basket" data-component="uploadable-basket">

    <?
    /**
     * @var UploadableBasket|UploadableProduct[] $basket
     */
    $basket = $arResult['basket'];
    ?>
    <form action="">

        <div class="b-order-checkout">

            <div class="b-order-checkout__head">
                <div class="b-order-checkout__cell b-order-checkout__cell--id">
                    № п/п
                </div>
                <div class="b-order-checkout__cell b-order-checkout__cell--article">
                    Артикул
                </div>
                <div class="b-order-checkout__cell b-order-checkout__cell--count">
                    Количество
                </div>
                <div class="b-order-checkout__cell b-order-checkout__cell--isfound">
                    Найдено
                </div>
                <div class="b-order-checkout__cell b-order-checkout__cell--product">
                    Наименование
                </div>
                <div class="b-order-checkout__cell b-order-checkout__cell--price">
                    Цена
                </div>

                <div class="b-order-checkout__cell b-order-checkout__cell--total">
                    Сумма
                </div>
                <div class="b-order-checkout__cell b-order-checkout__cell--funcs">
                    Выбрать вручную
                </div>

            </div>


            <div class="b-order-checkout__body">

                <? foreach ($basket as $product): ?>
                    <div class="b-order-checkout__item <?= ($product->isFound()) ? '' : 'b-order-checkout__item_not_found' ?>" data-actable="remove">

                        <div class="b-order-checkout__item-id b-order-checkout__cell b-order-checkout__cell--id">
                            <div class="b-order-checkout__item-mobile-title">№ п/п:</div>
                            <?= $basket->indexOf($product) ?>
                        </div>

                        <div class="b-order-checkout__item-product b-order-checkout__cell b-order-checkout__cell--article">
                            <div class="b-order-checkout__item-mobile-title">Артикул:</div>
                            <input type="hidden" name="input[<?= $basket->indexOf($product) ?>]['article']" value="<?= $product->getArticle() ?>"/>
                            <?= $product->getArticle() ?>
                        </div>

                        <div class="b-order-checkout__cell b-order-checkout__cell--quality b-order-checkout__item-quality">
                            <div class="b-order-checkout__item-mobile-title">Количество:</div>
                            <input type="hidden" name="input[<?= $basket->indexOf($product) ?>]['quantity']" value="<?= $product->getQuantity() ?>"/>
                            <?= $product->getQuantity() ?>
                        </div>
                        <div class="b-order-checkout__cell b-order-checkout__cell--isfound b-order-checkout__cell--isfound-<?= $product->isFound() ? 'positive' : 'negative' ?> b-order-checkout__item-isfound">
                            <div class="b-order-checkout__item-mobile-title">Найдено:</div>
                            <?= $product->isFound() ? 'Да' : 'Нет' ?>
                        </div>
                        <div class="b-order-checkout__cell b-order-checkout__cell--product b-order-checkout__item-product">
                            <div class="b-order-checkout__item-mobile-title">Наименование:</div>
                            <input type="hidden" name="input[<?= $basket->indexOf($product) ?>]['name']" value="<?= $product->getName() ?>"/>
                            <?= $product->getName(); ?>
                        </div>
                        <div class="b-order-checkout__cell b-order-checkout__cell--price b-order-checkout__item-price">
                            <div class="b-order-checkout__item-mobile-title">Цена:</div>
                            <div class="b-price">
                                <div class="b-price__current">
                                    <?= CCurrencyLang::CurrencyFormat($product->getPrice(), 'RUB'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="b-order-checkout__cell b-order-checkout__cell--total b-order-checkout__item-price b-order-checkout__item-price--total">
                            <div class="b-order-checkout__item-mobile-title">Сумма:</div>
                            <div class="b-price">
                                <div class="b-price__current">
                                    <?= CCurrencyLang::CurrencyFormat($product->getSum(), 'RUB'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="b-order-checkout__cell b-order-checkout__cell--funcs">
                            <a href="javascript:void(0);" title="Удалить" class="b-order-checkout__item-btn" data-action="remove">
                                <div class="plus plus--cross"></div>
                                <span class="btn-content">Удалить</span>
                            </a>
                            <a href="javascript:void(0);" title="Каталог" class="b-order-checkout__item-btn">
                                <svg class='i-icon'>
                                    <use xlink:href='#icon-catalog'/>
                                </svg>
                                <span class="btn-content">Каталог</span>
                            </a>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
            <div class="b-order-checkout__btns">
                <a href="javascript:void(0);" title="Отменить" class="btn btn--gray b-order-checkout__btn" data-action="cancel">
                    Отменить
                </a>
                <a href="javascript:void(0);" title="Добавить и вернуться" class="btn btn--gray b-order-checkout__btn">
                    Добавить и вернуться
                </a>
                <a href="javascript:void(0);" title="Добавить и уточнить заказ" class="btn btn--gray b-order-checkout__btn">
                    Добавить и уточнить заказ
                </a>
            </div>
        </div>
    </form>

</div>
