<?php

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

    <div class="b-tabs" data-tab-group>
        <div class="b-tabs-head" data-tab-header>
            <a href="javascript:void(0);" title="Вставить скопированный текст" class="b-tabs-link active" data-tab-btn="1">Вставить скопированный текст</a>
            <a href="javascript:void(0);" title="Загрузить заказ из файла" class="b-tabs-link" data-tab-btn="2">Загрузить заказ из файла</a>
        </div>
        <div class="b-tabs__content" data-tab-content>
            <div class="b-tabs__item active" data-tab-body="1">
                <form class="b-form" action="">

                    <div class="b-form__inner-column b-form__inner-column--2 b-order-steps-wrap">
                        <div class="b-order-steps__column">
                            <div class="b-form-input__wrap b-order-steps__input">
                                <div class="b-form__success hidden">Заказ добавлен.</div>
                                <textarea name="input" class="b-form__textarea"
                                          data-component="uploadable-basket-input"></textarea>
                                <div class="b-form__error hidden" data-message="error">Некорректно заполнено поле</div>
                            </div>
                        </div>

                        <div class="b-order-steps__column">
                            <div class="b-order-steps">
                                <div class="b-order-steps__item-wrap">
                                    <p class="b-order-steps__head">Скопируйте и вставьте текст из таблицы</p>
                                    <ul>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 1:</span>
                                            Выделить
                                            область ячеек так, чтобы первым столбцом были артикулы, а последним –
                                            количество
                                            товара.
                                        </li>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 2:</span>
                                            Щелкнуть
                                            правой кнопкой мыши по выделенной области и выбрать из открывшегося меню
                                            пункт
                                            «Копировать».
                                        </li>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 3:</span>
                                            Щелкнуть
                                            правой кнопкой мыши внутри поля «Вставьте скопированный текст» на портале и
                                            выбрать пункт «Вставить» из появившегося меню.
                                        </li>
                                        <li class="b-order-steps__item"><span class="text--highlight">Шаг 4:</span>
                                            Нажать
                                            кнопку «Далее».
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="b-order-steps">
                    <div class="b-order-steps__btns">
                        <a href="javascript:void(0);" title="Отменить" class="btn btn--gray b-order-steps__btn" data-action="cancel">
                            Отменить
                        </a>
                        <a href="javascript:void(0);" title="Далее" class="btn btn--red b-order-steps__btn" data-action="preview">
                            Далее
                        </a>
                    </div>
                </div>
            </div>
            <div class="b-tabs__item" data-tab-body="2">
                <div class="b-order-steps__column b-order-steps__column--2">
                    <div class="b-form__success hidden" id="filename"></div>
                    <div class="b-form__error hidden">Некорректно заполнено поле</div>
                    <div class="b-order-steps__btns b-order-steps__btns--loadfile">

                        <label class="btn btn--blue b-order-steps__btn">
                            <svg class='i-icon'>
                                <use xlink:href='#icon-clip'/>
                            </svg>
                            <input type="file" class="file" id="file-upload"/>

                        </label>

                        <a href="javascript:void(0);" title="Далее" class="btn btn--red b-order-steps__btn">
                            Далее
                        </a>
                    </div>
                    <div class="b-order-steps__hint">
                    <span>Вы можете загрузить заказ из файла.<br>
                        Формат <span class="text--dark">xlsx</span>, размер не более <span
                                class="text--dark">10Мб</span>.</span>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
