<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

$this->setFrameMode(false);
?>
<div class="lk__section">
    <? if ($component->isAjax == true) {
        $GLOBALS['APPLICATION']->RestartBuffer();
    } ?>
    <form method="post"
          name="lk_change_addresses"
          id="lk_change_addresses"
          action="<?= $arResult["FORM_TARGET"]; ?>"
          enctype="multipart/form-data"
          class="b-form"
          data-form-validation>
        <p>Выберите юр.лицо от которого будет оформлен заказ.
            Пользователя можно изменять в разделе "Персональные данные" и "Корзина".</p>
        <? $APPLICATION->IncludeComponent(
            "citfact:contragent.list",
            ".default",
            Array()
        ); ?>
        <?= $arResult["BX_SESSION_CHECK"] ?>
        <input type="hidden" name="LOGIN" value="<?= $arResult["arUser"]["LOGIN"] ?>"/>
        <input type="hidden" name="EMAIL" value="<? echo $arResult["arUser"]["EMAIL"] ?>"/>
        <input type="hidden" name="lang" value="<?= LANG ?>"/>
        <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>"/>
        <div class="b-tabs">
            <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/personal/profile/tabs-head.php"; ?>
            <div class="b-tabs__content">
                <div class="b-tabs__item active">
                    <?php if (count($arResult['UF_SHIPPING_ADDRESSES']) <= 0) { ?>
                        <div class="b-form__item hidden" data-f-item data-field>
                            <span class="b-form__label" data-f-label>Адрес доставки</span>
                            <input type="text" name="ADDRESSES[]" value="Добавьте адрес доставки" data-f-field
                                   data-required="Y" data-suggestion="address" disabled="disabled">
                            <span class="b-form__text" data-form-error></span>
                        </div>
                    <?php } ?>

                    <?foreach ($arResult['UF_SHIPPING_ADDRESSES'] as $address):?>
                        <div class="b-form__item" data-f-item data-field>
                            <span class="b-form__label" data-f-label>Адрес доставки</span>
                            <input type="text" name="ADDRESSES[]" value="<?=$address['UF_NAME']?>" data-f-field
                                   data-required="Y" data-suggestion="address" disabled="disabled">
                            <span class="b-form__text" data-form-error></span>
                        </div>
                    <?endforeach;?>
                    <? if (empty($arResult['SUCCESS'])){?>
                    <a href="javascript:void(0);" title="Добавить" class="btn btn--transparent" data-field-add>
                        <div class="plus"></div>
                        <span>Добавить</span>
                    </a>
                    <?}else{
                        echo "<span class=\"form__text\">Запрос на обновление адресов доставки отправлен!</span>";
                    }?>
                    <div class="b-form__bottom">
                        <button class="btn btn--transparent btn--big"
                                type="submit"
                                name="SEND"
                                value="Отправить заявку"
                                data-agree-submit="WEB_FORM_AJAX" disabled>Отправить заявку</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<? if ($component->isAjax !== true) { ?>
    <script src="<?= $componentPath . '/script.js' ?>"></script>
    <script>
        if (window.HlBlock.inited !== true) {
            // signedParameters - перечень ключей параметров компонента
            HlBlock.init(<?=json_encode([
                'signedParameters' => $component->getSignedParameters(),
                'htmlContainerSelector' => '#lk_change_addresses',
                'formSelector' => '#lk_change_addresses',
            ])?>);
        }
    </script>
<? } else { ?>
    <script>Am.validation.run();</script>
<? } ?>

<? if ($component->isAjax === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
} ?>