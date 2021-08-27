<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\UserDataManager;

$arContragent = UserDataManager\UserDataManager::getContrAgentInfo();

$this->setFrameMode(false);
?>
    <div class="lk__section">
        <? if ($component->isAjax == true) {
            $GLOBALS['APPLICATION']->RestartBuffer();
        } ?>
        <div class="b-form">
            <p>Выберите юр.лицо от которого будет оформлен заказ.
                Пользователя можно изменять в разделе "Персональные данные" и "Корзина".</p>
            <? $APPLICATION->IncludeComponent(
                "citfact:contragent.list",
                ".default",
                Array(),
                $component
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

                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Название компании *</span>
                            <input type="text" value='<?=$arContragent['UF_NAME']?>' data-f-field disabled>
                        <span class="b-form__text">

                        </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>ИНН *</span>
                            <input type="text" value="<?=$arContragent['UF_INN']?>" data-f-field disabled>
                        <span class="b-form__text">

                        </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Правовая форма</span>
                            <input type="text" value="<?=$arContragent['UF_YURFIZLITSO']?>" data-f-field disabled>
                        <span class="b-form__text">

                        </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Фактический адрес</span>
                            <input type="text" disabled
                                   value="<?=$arContragent['UF_ADRESFAKT']?>"
                                   data-f-field>
                        <span class="b-form__text">

                        </span>
                        </div>
                        <div class="b-form__item" data-f-item>
                            <span class="b-form__label" data-f-label>Телефон</span>
                            <input type="text" disabled value="<?=implode(', ', $arContragent['UF_TELEFON'])?>" data-f-field>
                            <span class="b-form__text">

                            </span>
                        </div>

                        <?php if (!empty($arContragent['UF_REGION'])) { ?>
                            <div class="b-form__item" data-f-item>
                                <span class="b-form__label" data-f-label>Регион</span>
                                <input type="text" disabled value="<?= $arContragent['UF_REGION']; ?>" data-f-field>
                                <span class="b-form__text"></span>
                            </div>
                        <?php } ?>

                        <? $APPLICATION->IncludeComponent(
                            "citfact.lib:webform.ajax",
                            "page",
                            array(
                                'WEB_FORM_ID' => 4,
                                "WEB_FORM_CODE" => "COMPANY_PROFILE",
                                "COMPONENT_TEMPLATE" => "page",
                                "PARAM" => "",
                                'SET_PLACEHOLDER' => 'N',
                                "HIDDEN_FIELD" => "COMPLEX_NAME",
                                "HIDDEN_FIELD_VALUE" => $arResult['NAME'],
                            ),
                            false
                        ); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<? if ($component->isAjax === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
}