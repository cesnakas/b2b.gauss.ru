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
$this->setFrameMode(false);
global $APPLICATION;
$core = Core::getInstance();
?>

<div class="b-form__main" data-form-submit="<?= $arParams['FORM_CODE'] ?>">
    <form action="<?= $core->getCurDir() ?>">
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
        <? if (!$arResult['SUCCESS']): ?>
            <? echo bitrix_sessid_post(); ?>
            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="NAME_<?= $arParams['FORM_CODE'] ?>">ФИО*:</label>
                <input type="text"
                       name="NAME"
                       id="NAME_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Ваше полное имя"
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['NAME'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="PHONE_<?= $arParams['FORM_CODE'] ?>">Телефон*:</label>
                <input type="text"
                       name="PHONE"
                       id="PHONE_<?= $arParams['FORM_CODE'] ?>"
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
                <label class="input-block__label" for="EMAIL_<?= $arParams['FORM_CODE'] ?>">Email*:</label>
                <input type="text" name="EMAIL"
                       id="EMAIL_<?= $arParams['FORM_CODE'] ?>"
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
            <? if ($core->getLoc() == Localization::LOC_KZ): ?>
                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="UF_IIN_<?= $arParams['FORM_CODE'] ?>">
                        ИИН*:</label>
                    <input type="text"
                           name="UF_IIN"
                           id="UF_IIN_<?= $arParams['FORM_CODE'] ?>"
                           placeholder="ИИН"
                           class="input-text required"
                           data-input-mask="number12"
                           value="<?= $arResult['REQUEST_DATA']['UF_IIN'] ?>">
                    <div class="input-block__error-text error-required" style="display: none">
                        Поле обязательно для заполнения
                    </div>
                </div>
            <? endif; ?>

            <? if ($arResult['IS_MANAGER']): ?>
                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="UF_REG_CLIENT_TYPE_<?= $arParams['FORM_CODE'] ?>">
                        Тип клиента
                    </label>
                    <div class="b-sorting__select-wrap b-sorting__item">
                        <div class="select select--medium b-sorting__select b-sorting__select--full">
                            <div class="relative">
                                <select class="select__inner select__inner-head" name="UF_REG_CLIENT_TYPE"
                                        id="UF_REG_CLIENT_TYPE_<?= $arParams['FORM_CODE'] ?>">
                                    <option value="">Выберите значение</option>
                                    <? foreach ($arResult['ENUM']['UF_REG_CLIENT_TYPE'] as $item): ?>
                                        <option value="<?= $item['ID'] ?>"
                                                data-xml-id="<?= $item['XML_ID'] ?>"
                                            <? if ($arResult['REQUEST_DATA']['UF_REG_CLIENT_TYPE'] == $item['ID']) echo 'selected' ?>
                                        >
                                            <?= $item['VALUE'] ?>
                                        </option>
                                    <? endforeach ?>
                                </select>
                                <div class="select__arrow"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endif ?>
            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="PASSWORD_<?= $arParams['FORM_CODE'] ?>">Пароль*:</label>
                <input type="password"
                       name="PASSWORD"
                       id="PASSWORD_<?= $arParams['FORM_CODE'] ?>"
                       placeholder=""
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['PASSWORD'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="CONFIRM_PASSWORD_<?= $arParams['FORM_CODE'] ?>">Подтверждение
                    пароля*:</label>
                <input type="password"
                       name="CONFIRM_PASSWORD"
                       id="CONFIRM_PASSWORD_<?= $arParams['FORM_CODE'] ?>"
                       placeholder=""
                       value="<?= $arResult['REQUEST_DATA']['CONFIRM_PASSWORD'] ?>"
                       class="input-text required">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>
            <div class="styled-text">

                <p class="margin-bottom-20">Мы настоятельно рекомендуем Вам придумать достаточно надежный пароль. Пароль
                    может содержать латинские строчные и заглавные буквы, а также цифры 0-9.<br>
                    Не передавайте доступы к Вашему личному кабинету третьим лицам.</p>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <div class="b-checkbox">
                    <input type="checkbox"
                           class="b-checkbox__input"
                           name="UF_NOTIFICATION"
                           id="UF_NOTIFICATION_<?= $arParams['FORM_CODE'] ?>"
                           value="1"
                        <? if ($arResult['REQUEST_DATA']['UF_NOTIFICATION'] == 1) echo 'checked' ?>
                    >
                    <label for="UF_NOTIFICATION_<?= $arParams['FORM_CODE'] ?>" class="b-checkbox__label">
                  <span class="b-checkbox__box">
                      <span class="b-checkbox__line b-checkbox__line--short"></span>
                      <span class="b-checkbox__line b-checkbox__line--long"></span>
                  </span>
                        <span class="b-checkbox__text">
                      Подписаться на e-mail и sms рассылку от компании
                  </span>
                    </label>
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <div class="b-checkbox">
                    <input type="checkbox"
                           class="b-checkbox__input"
                           name="UF_AGREEMENT"
                           id="UF_AGREEMENT_<?= $arParams['FORM_CODE'] ?>"
                           value="1"
                           data-agreement-checkbox
                        <? if ($arResult['REQUEST_DATA']['UF_AGREEMENT'] == 1) echo 'checked' ?>
                    >
                    <label for="UF_AGREEMENT_<?= $arParams['FORM_CODE'] ?>" class="b-checkbox__label">
                  <span class="b-checkbox__box">
                      <span class="b-checkbox__line b-checkbox__line--short"></span>
                      <span class="b-checkbox__line b-checkbox__line--long"></span>
                  </span>
                        <span class="b-checkbox__text">
                      Я согласен с <a style="text-decoration: underline" href="/policy/" rel="noopener noreferrer" target="_blank">правилами обработки персональных</a> данных на сайте
                  </span>
                    </label>
                </div>
            </div>

            <div class="b-form__block input-block">
                <input type="submit" class="btn" value="Зарегистрироваться">
            </div>
        <? endif ?>
    </form>
    <div class="result_cont"></div>
</div>
