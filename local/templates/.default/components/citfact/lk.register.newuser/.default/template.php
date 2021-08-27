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
$this->setFrameMode(true);
$contragent = $arResult['contragent'];
?>
<h1>Добавить пользователя для <?= $contragent['UF_NAME'] ?></h1>

<div class="lk__section">

    <p>
        Вы можете зарегистрировать нового пользователя или добавить уже существующего.
    </p>

    <div class="b-tabs-head">
        <a class="b-tabs-link active"
           href="javascript:void(0)">
            Добавить нового
        </a>
        <a class="b-tabs-link"
           href="/personal/register/add_existing_user/?contragent_guid=<?= $contragent['UF_XML_ID'] ?>">
            Добавить существующего
        </a>
    </div>


    <? if ($arResult['NEW_USER_REGISTERED']): ?>
        <? if ($arResult['NEW_USER_REGISTERED'] == 'SUCCESS'): ?>
            <div class="green">Новый пользователь успешно зарегистрирован и добавлен в список пользователей юрлица</div>
        <? else: ?>
            <div class="red"><?= $arResult['ERROR'] ?></div>
        <? endif ?>
    <? endif ?>

    <form action="<?= $APPLICATION->GetCurUri("", false) ?>" method="post" class="b-form">

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>Ф.И.О. представителя *</span>
            <input type="text" name="user_fio" data-f-field>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>Телефон *</span>
            <input type="text" name="user_phone" data-mask="phone" data-f-field>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>E-mail *</span>
            <input type="text" name="user_email" data-f-field>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>Ф.И.О. руководителя</span>
            <input type="text" name="leader_fio" data-f-field>
        </div>

        <div class="b-form__bottom">
            <button type="submit" name="inst" class="btn btn--transparent">Добавить</button>
        </div>
    </form>

</div>