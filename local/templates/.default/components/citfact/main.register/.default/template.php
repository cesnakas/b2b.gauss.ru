<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

use \Bitrix\Main\Localization\Loc;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

// создаем копию полей для формирования финальной формы для отправки со всеми полями (в т.ч. - скрытыми)
$arParams["SHOW_FIELDS_COPY"] = $arParams["SHOW_FIELDS"];
?>
<div class="aside auth" data-tab-group>

    <div class="aside__sidebar" data-tab-header>
        <div class="b-tabs-head">
            <a href="javascript:void(0);" class="b-tabs-link active" data-tab-btn="1">Личные данные</a>
            <a href="javascript:void(0);" class="b-tabs-link" data-tab-btn="2">Данные об организации</a>
        </div>
    </div>

    <div class="aside__main static-content" data-tab-content>

        <div class="b-tabs__item active" data-tab-body="1">
            <?
            if (count($arResult["ERRORS"]) > 0):
                foreach ($arResult["ERRORS"] as $key => $error)
                    if (intval($key) == 0 && $key !== 0)
                        $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

                ShowError(implode("<br />", $arResult["ERRORS"]));

            elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
                ?>
                <p><? echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
            <? endif ?>

            <form action="<?= POST_FORM_ACTION_URI ?>" name="regform_1" method="POST" enctype="multipart/form-data"
                  class="b-form regform"
                  data-form-validation="registration">
                <input type="hidden" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>"/>
                <? if ($arResult["BACKURL"] <> '') { ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <? } ?>

                <div class="b-form__inner b-form__inner--double">
                    <?
                    foreach ($arParams["SHOW_FIELDS"] as $key => $FIELD) {
                        if ($key < 6) {
                            switch ($FIELD) {
                                case "AUTO_TIME_ZONE":
                                case "PERSONAL_GENDER":
                                case "PERSONAL_COUNTRY":
                                case "WORK_COUNTRY":
                                case "PERSONAL_PHOTO":
                                case "WORK_LOGO":
                                case "PERSONAL_NOTES":
                                case "WORK_NOTES":
                                case "PERSONAL_BIRTHDAY":
                                case "UF_SUBSCRIBE":
                                    break;

                                default:
                                    $placeHolder = Loc::getMessage('REGISTER_FIELD_' . $FIELD);
                                    $attributes = '';
                                    if (in_array($FIELD, $arResult['REQUIRED_FIELDS'])) {
                                        $placeHolder .= '*';
                                        $attributes .= ' data-required = \'Y\' ';

                                        if ($FIELD == 'EMAIL') {
                                            $attributes .= ' data-form-field-email ';
                                        } else if ($FIELD == 'PERSONAL_PHONE') {
                                            $attributes .= ' data-form-field-phone ';
                                        }
                                    }
                                    if ($FIELD == 'PERSONAL_PHONE') {
                                        $attributes .= "data-mask='phone' ";
                                    }

                                    $arParamsComp = [
                                        'NOT_AOS' => 'Y',
                                        'NAME' => 'REGISTER[' . $FIELD . ']',
                                        'PLACEHOLDER' => $placeHolder,
                                        'ATTRIBUTES' => $attributes,
                                        'SUCCESS' => $arResult['SUCCESS'],
                                        'VALUE' => ($arResult['SUCCESS']) ? '' : $arResult["VALUES"][$FIELD],
                                        'ERROR' => Loc::getMessage('REGISTER_REQUIRED_FIELD'),
                                    ];

                                    $template = 'input';
                                    if ($FIELD == 'PASSWORD') {
                                        $template = 'password.register';
                                    }

                                    if ($FIELD == 'CONFIRM_PASSWORD') {
                                        $template = 'password';
                                    }

                                    if ($FIELD == 'UF_REGIONS') {
                                        $template = 'select';
                                        $arParamsComp['UF_REGIONS'] = $arResult['UF_REGIONS'];
                                    }

                                    if ($FIELD == 'UF_FORM_ORGANIZATION') {
                                        $template = 'select';
                                        $arParamsComp['UF_FORM_ORGANIZATION'] = $arResult['UF_FORM_ORGANIZATION'];
                                    }

                                    $APPLICATION->IncludeComponent('citfact:form.view', $template, $arParamsComp, $component);
                                    break;
                            }
                            unset($arParams["SHOW_FIELDS"][$key]);
                        }
                    } ?>

                    <?
                    foreach ($arParams["SHOW_FIELDS"] as $key => $FIELD) {
                        if ($key < 6 && $FIELD['HIDDEN'] !== 'Y') {
                            switch ($FIELD) {
                                case "AUTO_TIME_ZONE":
                                case "PERSONAL_GENDER":
                                case "PERSONAL_COUNTRY":
                                case "WORK_COUNTRY":
                                case "PERSONAL_PHOTO":
                                case "WORK_LOGO":
                                case "PERSONAL_NOTES":
                                case "WORK_NOTES":
                                case "PERSONAL_BIRTHDAY":
                                case "UF_SUBSCRIBE":
                                    break;

                                default:
                                    $placeHolder = Loc::getMessage('REGISTER_FIELD_' . $FIELD);
                                    $attributes = '';
                                    if (in_array($FIELD, $arResult['REQUIRED_FIELDS'])) {
                                        $placeHolder .= '*';
                                        $attributes .= ' data-required = \'Y\' ';

                                        if ($FIELD == 'EMAIL') {
                                            $attributes .= ' data-form-field-email ';
                                        } else if ($FIELD == 'PERSONAL_PHONE') {
                                            $attributes .= ' data-form-field-phone ';
                                        }
                                    }
                                    if ($FIELD == 'PERSONAL_PHONE') {
                                        $attributes .= "data-mask='phone' ";
                                    }

                                    $arParamsComp = [
                                        'NOT_AOS' => 'Y',
                                        'NAME' => 'REGISTER[' . $FIELD . ']',
                                        'PLACEHOLDER' => $placeHolder,
                                        'ATTRIBUTES' => $attributes,
                                        'SUCCESS' => $arResult['SUCCESS'],
                                        'VALUE' => ($arResult['SUCCESS']) ? '' : $arResult["VALUES"][$FIELD],
                                        'ERROR' => Loc::getMessage('REGISTER_REQUIRED_FIELD'),
                                    ];

                                    $template = 'input';
                                    if ($FIELD == 'PASSWORD') {
                                        $template = 'password.register';
                                    }

                                    if ($FIELD == 'CONFIRM_PASSWORD') {
                                        $template = 'password';
                                    }

                                    if ($FIELD == 'UF_REGIONS') {
                                        $template = 'select';
                                        $arParamsComp['UF_REGIONS'] = $arResult['UF_REGIONS'];
                                    }

                                    if ($FIELD == 'UF_FORM_ORGANIZATION') {
                                        $template = 'select';
                                        $arParamsComp['UF_FORM_ORGANIZATION'] = $arResult['UF_FORM_ORGANIZATION'];
                                    }

                                    $APPLICATION->IncludeComponent('citfact:form.view', $template, $arParamsComp, $component);
                                    break;
                            }
                            unset($arParams["SHOW_FIELDS"][$key]);
                        }
                    } ?>

                    <div class="b-form__bottom">
                        <div class="b-form__pp">
                            Пароль может содержать латинские строчные и заглавные буквы, а также цифры 0-9.
                        </div>
                    </div>
                    <div class="b-form__bottom">
                        <button type="button" id="continue" class="btn btn--transparent btn--big">Продолжить</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="b-tabs__item" data-tab-body="2">

            <?
            if (count($arResult["ERRORS"]) > 0):
                foreach ($arResult["ERRORS"] as $key => $error)
                    if (intval($key) == 0 && $key !== 0)
                        $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

                ShowError(implode("<br />", $arResult["ERRORS"]));

            elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
                ?>
                <p><? echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
            <? endif ?>

            <form action="<?= POST_FORM_ACTION_URI ?>" name="regform_2" method="POST" enctype="multipart/form-data"
                  class="b-form"
                  data-form-validation="registration">
                <input type="hidden" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>"/>
                <? if ($arResult["BACKURL"] <> '') { ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <? } ?>
                
                <div class="b-form__inner b-form__inner--double">
                    <?
                    foreach ($arParams["SHOW_FIELDS_COPY"] as $key => $FIELD) {
                        switch ($FIELD) {
                            case "AUTO_TIME_ZONE":
                            case "PERSONAL_GENDER":
                            case "PERSONAL_COUNTRY":
                            case "WORK_COUNTRY":
                            case "PERSONAL_PHOTO":
                            case "WORK_LOGO":
                            case "PERSONAL_NOTES":
                            case "WORK_NOTES":
                            case "PERSONAL_BIRTHDAY":
                            case "UF_SUBSCRIBE":
                                break;
            
                            default:
                                $placeHolder = Loc::getMessage('REGISTER_FIELD_' . $FIELD);
                                $attributes = '';
                                if (in_array($FIELD, $arResult['REQUIRED_FIELDS'])) {
                                    $placeHolder .= '*';
                                    $attributes .= ' data-required = \'Y\' ';
                    
                                    if ($FIELD == 'EMAIL') {
                                        $attributes .= ' data-form-field-email ';
                                    } else if ($FIELD == 'PERSONAL_PHONE') {
                                        $attributes .= ' data-form-field-phone ';
                                    }
                                }
                                if ($FIELD == 'PERSONAL_PHONE') {
                                    $attributes .= "data-mask='phone' ";
                                }

                                if ($FIELD == 'UF_TIN') {
                                    $attributes .= "data-suggestion='inn' ";
                                }

                                if ($FIELD == 'UF_COMPANY_NAME') {
                                    $attributes .= "data-suggestion='name' ";
                                    $arResult["VALUES"][$FIELD] = htmlspecialchars_decode($arResult["VALUES"][$FIELD]);
                                }

                                if ($FIELD == 'UF_OFFICE_ADDRESS') {
                                    $attributes .= "data-suggestion='address' ";
                                }


                                if ($FIELD == 'UF_COMPANY_PHONE') {
                                    $attributes .= "data-suggestion='phone' ";
                                }

                                $arParamsComp = [
                                    'NOT_AOS' => 'Y',
                                    'NAME' => 'REGISTER[' . $FIELD . ']',
                                    'PLACEHOLDER' => $placeHolder,
                                    'ATTRIBUTES' => $attributes,
                                    'SUCCESS' => $arResult['SUCCESS'],
                                    'VALUE' => ($arResult['SUCCESS']) ? '' : $arResult["VALUES"][$FIELD],
                                    'ERROR' => Loc::getMessage('REGISTER_REQUIRED_FIELD'),
                                ];

                                $template = 'input';
                                if ($FIELD == 'PASSWORD') {
                                    $template = 'password.register';
                                }
                
                                if ($FIELD == 'CONFIRM_PASSWORD') {
                                    $template = 'password';
                                }
                
                                if ($FIELD == 'UF_REGIONS') {
                                    $template = 'select';
                                    $arParamsComp['UF_REGIONS'] = $arResult['UF_REGIONS'];
                                }
                
                                if ($FIELD == 'UF_FORM_ORGANIZATION') {
                                    $template = 'select';
                                    $arParamsComp['UF_FORM_ORGANIZATION'] = $arResult['UF_FORM_ORGANIZATION'];
                                }

                                if ($key < 6){
                                    $arParamsComp['HIDDEN'] = 'Y';
                                }

                                $APPLICATION->IncludeComponent('citfact:form.view', $template, $arParamsComp, $component);
                                break;
                        }
                    } ?>

                    <div class="b-form__bottom">
                        <div class="b-form__pp">
                            Нажимая на кнопку, я подтверждаю свое согласие на <a href="/policy/" rel="noopener noreferrer" target="_blank">«Политику в отношении обработки персональных данных»</a>
                        </div>
                    </div>

                    <div class="b-form__bottom">
                        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
                        <input type="hidden" name="captcha_word" value="">
                        <a href="javascript:void(0);"
                           data-btn-submit
                           class="btn btn--transparent btn--big">Зарегистрироваться</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>