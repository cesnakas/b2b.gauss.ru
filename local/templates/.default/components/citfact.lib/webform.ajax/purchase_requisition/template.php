<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

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

$frame = $this->createFrame()->begin();
?>
    <div class="b-modal b-modal--big">
        <div class="b-modal__close" data-modal-close="">
            <div class="plus plus--cross"></div>
        </div>

        <?php if ('Y' === $arParams['SHOW_FORM_TITLE']) { ?>
            <div class="title-1">
                <span><?php echo $arResult['arForm']['NAME']; ?></span>
            </div>
        <?php } ?>

        <?php
        if (!empty($arResult['SUCCESS'])) { ?>
            <p>Ваша заявка отправлена. В ближайшее время наш менеджер свяжется с Вами.</p>
        <?php } else { ?>
            <form class="b-form"
                  action="<?= $APPLICATION->GetCurDir(); ?>"
                  method="post"
                  enctype="multipart/form-data"
                  name="<?= $arResult['WEB_FORM_NAME']; ?>"
                  data-form-validation>
                <?= bitrix_sessid_post(); ?>
                <input type="hidden" name="WEB_FORM_CODE" value="<?php echo $arParams['WEB_FORM_CODE']; ?>">

                <div class="b-form__inner b-form__inner--double">
                    <?php foreach ($arResult['QUESTIONS'] as $name => $field) { ?>
                        <? if ($field['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') { ?>
                            <?= $field['HTML_CODE'] ?>
                        <? } else { ?>
                            <div
                                class="b-form__item <?= $field['STRUCTURE']['0']['FIELD_TYPE'] === 'dropdown' ? 'b-form__item--select' :
                                    ($field['STRUCTURE']['0']['FIELD_TYPE'] === 'textarea' ? 'b-form__item--textarea hidden' : ''); ?>"
                                data-f-item>
                        <span class="b-form__label" data-f-label>
                            <?= $field['CAPTION']; ?>&nbsp;
                            <?= ($field['REQUIRED'] === 'Y' ? '*' : ''); ?>
                        </span>
                                <?= $field['HTML_CODE']; ?>
                                <?php
                                if ($field['REQUIRED'] == 'Y') { ?>
                                    <span class="b-form__text alert alert--error hidden" data-form-error>
                                <?= Loc::getMessage('REQUIRED_FIELD'); ?>
                            </span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <? if ($arResult['PRODS']) { ?>
                    <div class="title-2"><span>Товары в заказе</span></div>

                    <div class="basket-item basket-item--top">
                        <div class="basket-item__description">Наименование</div>
                        <div class="basket-item__count">Количество</div>
                        <div class="basket-item__actions"></div>
                    </div>

                    <? foreach ($arResult['PRODS'] as $prod) { ?>
                        <div class="basket-item" data-prod-item>
                            <div class="basket-item__description">
                                <a href="#"><span class="basket-item__title" id="prod_name_<?= $prod['ID']?>"><?= $prod['NAME'] ?></span></a>
                            </div>

                            <div class="basket-item__count">
                                <div class="basket-item__t">Количество</div>
                                <div class="b-count" data-input-count>
                                    <button type="button" data-input-count-btn="minus"
                                            class="b-count__btn b-count__btn--minus"></button>
                                    <input class="b-count__input"
                                           type="text"
                                           name="products[quantity][]" min="1" pattern="[0-9]+"
                                           value="1"
                                           data-input-count-input data-product-quantity="1">
                                    <button type="button" data-input-count-btn="plus"
                                            class="b-count__btn b-count__btn--plus"></button>
                                </div>
                            </div>

                            <div class="basket-item__actions">
                                <div class="plus plus--cross" data-delete_item></div>
                            </div>
                        </div>
                    <? }
                }else{?>
                    <div class="title-2"><span>Товары не выбраны</span></div>
                <?} ?>

                <div class="b-modal__bottom">
                    <div class="b-form__pp">
                        Нажимая на кнопку, я подтверждаю свое согласие на <a href="/policy/" rel="noopener noreferrer" target="_blank">«Политику в отношении
                            обработки персональных данных»</a>
                    </div>
                    <button type="submit"
                            data-agree-submit="WEB_FORM_AJAX"
                            class="btn btn--transparent btn--big">
                        <?= $arResult['arForm']['BUTTON']; ?>
                    </button>
                </div>
            </form>
        <?php } ?>
    </div>

<? $fTime = filemtime($_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/script.js'); ?>
    <script src="<?= $templateFolder . '/script.js' ?>?<?= $fTime; ?>"></script>
    <script>
        BX.message({
            COMPONENT_PATH_MODAL_MARKETING: '<?= $arParams['AJAX_URL'] ?>',
            WEB_FORM_NAME_MODAL_MARKETING: '<?= $arResult['WEB_FORM_NAME']; ?>'
        });
    </script>
<?php $frame->end(); ?>