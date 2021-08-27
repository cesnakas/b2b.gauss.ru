<? use Citfact\Sitecore\Core;

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
$this->setFrameMode(false);
global $APPLICATION;
$core = Core::getInstance();
?>

<div id='modal-portal-regiter' class='modal modal--form mfp-hide' data-form-reset
     data-form-submit="PORTAL_REGISTER_MODAL">
    <div class='modal__wrap'>
        <div class='modal__head'>
            <button class='modal__close' data-modal-close></button>
            <div class='modal__head-title'>
                Регистрация клиента
            </div>
        </div>
        <form class="b-form"
              action="<?= $core->getCurDir() ?>">
            <div class="modal__body b-form__main">
                <div class="margin-bottom-15">
                    <? if ($arResult['SUCCESS']): ?>
                        <div class="result_desc success-text">
                            <?= $arResult['SUCCESS'] ?>
                        </div>
                    <? endif ?>
                    <? if ($arResult['ERROR']): ?>
                        <div class="errors_cont error-text">
                            <?= $arResult['ERROR'] ?>
                        </div>
                    <? endif ?>
                </div>
                <? $rand = md5(uniqid(rand(), 1)); ?>
                <input type="hidden" name="<?= $arParams['FORM_CODE'] ?>" value="Y">
                <input type="hidden" name="UF_NOTIFICATION" value="1">
                <input type="hidden" name="PASSWORD" value="<?= $rand ?>">
                <input type="hidden" name="CONFIRM_PASSWORD" value="<?= $rand ?>">
                <? echo bitrix_sessid_post(); ?>
                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="NAME">ФИО*:</label>
                    <input type="text"
                           name="NAME"
                           id="NAME"
                           placeholder="Полное имя клиента"
                           class="input-text required"
                           value="<?= $arResult['REQUEST_DATA']['NAME'] ?>">
                    <div class="input-block__error-text error-required" style="display: none">
                        Поле обязательно для заполнения
                    </div>
                </div>
                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="PHONE">Телефон*:</label>
                    <input type="text"
                           name="PHONE"
                           id="PHONE"
                           placeholder=""
                           data-input-mask="phone"
                           class="input-text phone required"
                           value="<?= $arResult['REQUEST_DATA']['PHONE'] ?>">
                    <div class="input-block__error-text error-required" style="display: none">
                        Поле обязательно для заполнения
                    </div>
                    <div class='input-block__error-text error-format' style="display: none">
                        Поле заполнено неверно
                    </div>
                </div>

                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="EMAIL">Email*:</label>
                    <input type="text" name="EMAIL"
                           id="EMAIL"
                           placeholder="yourmail@example.com"
                           class="input-text email required"
                           value="<?= $arResult['REQUEST_DATA']['EMAIL'] ?>">
                    <div class="input-block__error-text error-required" style="display: none">
                        Поле обязательно для заполнения
                    </div>
                    <div class='input-block__error-text error-format' style="display: none">
                        Поле заполнено неверно
                    </div>
                </div>

                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="UF_CITY_NAME_<?= $arParams['FORM_CODE'] ?>">Город:</label>
                    <div class="contacts-addresses__sort">
                        <div class="contacts-addresses__city contacts-addresses__city--form">
                            <div class="select select--medium">
                                <select class="select__inner"
                                        data-select2-init
                                        name="UF_CITY_NAME"
                                        id="UF_CITY_NAME_<?= $arParams['FORM_CODE'] ?>">
                                    <? foreach ($arResult['CITY_LIST'] as $valueID => $city): ?>
                                        <option value="<?= $city['NAME'] ?>"
                                            <?= ($valueID == $_SESSION['CURRENT_REGION']['ID'] ? 'selected' : ''); ?>
                                        >
                                            <?= $city['NAME'] ?>
                                        </option>
                                    <? endforeach ?>
                                </select>
                                <div class="select__arrow"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <? if ($arResult['IS_AUL']): ?>
                    <div class="b-form__block input-block" data-form-item>
                        <label class="input-block__label" for="ACCESS_LEVEL">
                            Уровень доступа
                        </label>
                        <div class="b-sorting__select-wrap b-sorting__item">
                            <div class="select select--medium b-sorting__select b-sorting__select--full">
                                <div class="relative">
                                    <select class="select__inner select__inner-head" name="ACCESS_LEVEL"
                                            id="ACCESS_LEVEL">
                                        <option value="0"
                                            <? if ($arResult['REQUEST_DATA']['ACCESS_LEVEL'] != '1') echo 'selected' ?>
                                        >
                                            Стандартный
                                        </option>
                                        <option value="1"
                                            <? if ($arResult['REQUEST_DATA']['ACCESS_LEVEL'] == '1') echo 'selected' ?>
                                        >
                                            Расширенный
                                        </option>
                                    </select>
                                    <div class="select__arrow"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif ?>

                <div class="b-form__block input-block">
                    <input type="submit" class="btn" value="Зарегистрировать">
                </div>
            </div>
        </form>
    </div>
</div>



