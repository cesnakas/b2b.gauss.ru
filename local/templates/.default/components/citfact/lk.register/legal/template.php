<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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
$localization = new Localization();
?>
<div class="b-form__main" data-form-submit="<?= $arParams['FORM_CODE'] ?>">
    <form action="<?= $core->getCurDir() ?>" enctype="multipart/form-data" method="post">
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
                <label class="input-block__label" for="UF_COMPANY_NAME_<?= $arParams['FORM_CODE'] ?>">Название
                    компании*:</label>
                <input type="text"
                       name="UF_COMPANY_NAME"
                       id="UF_COMPANY_NAME_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Название компании"
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['NAME'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>
            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_DIRECTOR_NAME_<?= $arParams['FORM_CODE'] ?>">Ф.И.О.
                    руководителя*:</label>
                <input type="text"
                       name="UF_DIRECTOR_NAME"
                       id="UF_DIRECTOR_NAME_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Ф.И.О. руководителя"
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['UF_DIRECTOR_NAME'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_LEGAL_FORM">
                    Правовая форма*:
                </label>
                <div class="b-sorting__select-wrap b-sorting__item">
                    <div class="select select--medium b-sorting__select b-sorting__select--full">
                        <div class="relative">
                            <select class="select__inner select__inner-head required" name="UF_LEGAL_FORM"
                                    id="UF_LEGAL_FORM"
                                <? if ($core->getLoc() != Localization::LOC_KZ): ?>
                                    data-portal-register-legal-form-select
                                <? endif ?>
                            >
                                <option value="">Выберите значение</option>
                                <? foreach ($arResult['ENUM']['UF_LEGAL_FORM'] as $item): ?>
                                    <option value="<?= $item['ID'] ?>"
                                            data-xml-id="<?= $item['XML_ID'] ?>"
                                        <? if ($arResult['REQUEST_DATA']['UF_LEGAL_FORM'] == $item['ID']) echo 'selected' ?>
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
            <div class="b-form__block input-block" data-form-item>
                <?
                $innName = $localization->getInnName();
                ?>
                <label class="input-block__label" for="UF_INN_<?= $arParams['FORM_CODE'] ?>"><?= $innName ?>*:</label>
                <input type="text"
                       name="UF_INN"
                       id="UF_INN_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="<?= $innName ?>"
                       class="input-text required"
                    <? if (
                        $core->getLoc() == Localization::LOC_KZ ||
                        $arResult['REQUEST_DATA']['UF_LEGAL_FORM'] == $arResult['IP_LEGAL_FORM_ID']
                    ): ?>
                        data-input-mask="number12"
                    <? else: ?>
                        data-input-mask="number10"
                    <? endif; ?>
                       data-portal-register-inn
                       value="<?= $arResult['REQUEST_DATA']['UF_INN'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>

            <? if ($core->getLoc() != Localization::LOC_KZ): ?>
                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="UF_KPP_<?= $arParams['FORM_CODE'] ?>">КПП<span
                                data-portal-register-kpp-star
                            <? if ($arResult['REQUEST_DATA']['UF_LEGAL_FORM'] == $arResult['IP_LEGAL_FORM_ID']) echo 'style="display:none"' ?>
                        >*</span>:</label>
                    <input type="text"
                           name="UF_KPP"
                           id="UF_KPP_<?= $arParams['FORM_CODE'] ?>"
                           placeholder="КПП"
                           class="input-text <? if ($arResult['REQUEST_DATA']['UF_LEGAL_FORM'] != $arResult['IP_LEGAL_FORM_ID']) echo 'required' ?>"
                           data-input-mask="number9"
                           data-portal-register-kpp
                           value="<?= $arResult['REQUEST_DATA']['UF_KPP'] ?>">
                    <div class="input-block__error-text error-required" style="display: none">
                        Поле обязательно для заполнения
                    </div>
                </div>
            <? endif ?>
            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="NAME_<?= $arParams['FORM_CODE'] ?>">Ф.И.О.
                    представителя*:</label>
                <input type="text"
                       name="NAME"
                       id="NAME_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Ф.И.О. представителя"
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['NAME'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
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
                <label class="input-block__label" for="PHONE_<?= $arParams['FORM_CODE'] ?>">Мобильный телефон*:</label>
                <input type="text"
                       name="PHONE"
                       id="PHONE_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Мобильный телефон"
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

            <div class="b-form__block input-block"
                 data-form-item
                 data-other-input-block="UF_LEGAL_FORM"
                <?
                $legalFormXmlId = $arResult['ENUM'][$arResult['REQUEST_DATA']['UF_LEGAL_FORM']]['XML_ID'];
                if (
                    !$legalFormXmlId ||
                    $legalFormXmlId != 'other'
                ) echo 'style="display:none;' ?>
            >
                <label class="input-block__label" for="UF_OTHER_LEGAL_FORM_<?= $arParams['FORM_CODE'] ?>"></label>
                <textarea id="UF_OTHER_LEGAL_FORM_<?= $arParams['FORM_CODE'] ?>" name="UF_OTHER_LEGAL_FORM" cols="40"
                          rows="5"
                          placeholder="Правовая форма"
                          class="textarea-text"><?= $arResult['REQUEST_DATA']['UF_OTHER_LEGAL_FORM'] ?></textarea>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_ADDRESS_LEGAL_<?= $arParams['FORM_CODE'] ?>">Юридический
                    адрес*:</label>
                <input type="text"
                       name="UF_ADDRESS_LEGAL"
                       id="UF_ADDRESS_LEGAL_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Юридический адрес"
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['UF_ADDRESS_LEGAL'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_ADDRESS_FACT_<?= $arParams['FORM_CODE'] ?>">Адрес
                    фактический*:</label>
                <input type="text"
                       name="UF_ADDRESS_FACT"
                       id="UF_ADDRESS_FACT_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Адрес фактический"
                       class="input-text required"
                       value="<?= $arResult['REQUEST_DATA']['UF_ADDRESS_FACT'] ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_COMPANY_PHONE_<?= $arParams['FORM_CODE'] ?>">Телефон
                    компании*:</label>
                <input type="text"
                       name="UF_COMPANY_PHONE"
                       id="UF_COMPANY_PHONE_<?= $arParams['FORM_CODE'] ?>"
                       placeholder="Телефон компании"
                       data-input-mask="phone"
                       class="input-text phone required"
                       value="<?= \Citfact\Tools\Tools::formatPhone($arResult['REQUEST_DATA']['UF_COMPANY_PHONE']) ?>">
                <div class="input-block__error-text error-required" style="display: none">
                    Поле обязательно для заполнения
                </div>
                <div class='input-block__error-text error-format' style="display: none">
                    Поле заполнено неверно
                </div>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_WORK">
                    Основное направление деятельности*:
                </label>
                <div class="b-sorting__select-wrap b-sorting__item">
                    <div class="select select--medium b-sorting__select b-sorting__select--full">
                        <div class="relative">
                            <select class="select__inner select__inner-head required" name="UF_WORK" id="UF_WORK">
                                <option value="">Выберите значение</option>
                                <? foreach ($arResult['ENUM']['UF_WORK'] as $item): ?>
                                    <option value="<?= $item['ID'] ?>"
                                            data-xml-id="<?= $item['XML_ID'] ?>"
                                        <? if ($arResult['REQUEST_DATA']['UF_WORK'] == $item['ID']) echo 'selected' ?>
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

            <div class="b-form__block input-block"
                 data-form-item
                 data-other-input-block="UF_WORK"
                <?
                $workXmlId = $arResult['ENUM'][$arResult['REQUEST_DATA']['UF_WORK']]['XML_ID'];
                if (
                    !$workXmlId ||
                    $workXmlId != 'other'
                ) echo 'style="display:none;' ?>
            >
                <label class="input-block__label" for="UF_OTHER_WORK_<?= $arParams['FORM_CODE'] ?>"></label>
                <textarea id="UF_OTHER_WORK_<?= $arParams['FORM_CODE'] ?>" name="UF_OTHER_WORK" cols="40" rows="5"
                          placeholder="Основное направление деятельности"
                          class="textarea-text"><?= $arResult['REQUEST_DATA']['UF_OTHER_WORK'] ?></textarea>
            </div>

            <div class="b-form__block input-block" data-form-item>
                <label class="input-block__label" for="UF_CITY_NAME_<?= $arParams['FORM_CODE'] ?>">Город
                    обслуживания*:</label>
                <div class="contacts-addresses__sort">
                    <div class="contacts-addresses__city contacts-addresses__city--form">
                        <div class="select select--medium">
                            <select class="select__inner required"
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
            <? if ($arResult['IS_MANAGER']): ?>
                <div class="b-form__block input-block" data-form-item>
                    <label class="input-block__label" for="UF_REG_CLIENT_TYPE_<?= $arParams['FORM_CODE'] ?>">
                        Тип клиента*:
                    </label>
                    <div class="b-sorting__select-wrap b-sorting__item">
                        <div class="select select--medium b-sorting__select b-sorting__select--full">
                            <div class="relative">
                                <select class="select__inner select__inner-head required" name="UF_REG_CLIENT_TYPE"
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

            <div class="b-form__block input-block" data-file-field data-file-field-required>
                <label class="input-block__label">
                    Реквизиты компании
                </label>
                <div class="input-block">
                    <div class="input-file">
                        <input name="UF_FILE" class="inputfile" type="file" size="30">
                    </div>
                    <div class="input-block__error-text error-required" style="display: none">
                        Поле обязательно для заполнения
                    </div>
                    <div class="input-block__error-text error-invalid-size" style="display: none">
                        Превышен допустимый размер файла 50 Мб
                    </div>
                    <div class="input-block__error-text error-valid-extension" style="display: none">
                        Недопустимый тип файла
                    </div>
                    <div class="styled-text">
                        <p class="margin-top-05 margin-bottom-20">Формат документов: XLS, DOC, PDF, JPG, PNG, BMP</p>
                    </div>
                </div>
            </div>
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
                            Я согласен с правилами обработки персональных данных на сайте
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
