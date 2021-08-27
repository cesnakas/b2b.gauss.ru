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
        <a class="b-tabs-link"
           href="/personal/register/add_new_user/?contragent_guid=<?= $contragent['UF_XML_ID'] ?>">
            Добавить нового
        </a>
        <a class="b-tabs-link active"
           href="javascript:void(0)">
            Добавить существующего
        </a>
    </div>

    <? if ($arResult['EXISTING_USER_REGISTERED']): ?>
        <? if ($arResult['EXISTING_USER_REGISTERED'] == 'SUCCESS'): ?>
            <div class="green">Пользователь успешно добавлен в список пользователей юридического лица</div>
        <? else: ?>
            <div class="red"><?= $arResult['ERROR'] ?></div>
        <? endif ?>
    <? endif ?>

    <form action="<? echo $APPLICATION->GetCurUri("", false) ?>" method="post" class="b-form">

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>Поиск пользователя</span>
            <input type="text" name="user" autocomplete="off" data-search-input-user="<?= $templateFolder; ?>/ajax.php" data-f-field>
            <input type="hidden" name="contragent" data-search-contragent value="<?= $contragent['UF_XML_ID'] ?>">
            <input type="hidden" name="user_id" data-search-user-id value="">
        </div>

        <div class="b-form__bottom">
            <button type="submit" name="register_existing_user" value="Y" class="btn btn--transparent">Добавить</button>
        </div>
    </form>

</div>