<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
$this->setFrameMode(true);
$email = $_REQUEST['EMAIL'];
if ($arResult['SUCCESS'] == 'Y') {
    $email = '';
}
?>

<form action="#ADD_SUBSCRIPTION_MARKETING" method="GET" class="b-form f-subscribe__form" data-f-subscribe>
    <div class="b-form__item" data-f-item>
        <span class="b-form__label" data-f-label>Ваш e-mail</span>
        <input type="hidden" name="ADD_SUBSCRIPTION_MARKETING" value="Y">
        <input type="text" name="EMAIL" value="<?= $email; ?>" placeholder="email@domain.ru" data-f-field>
        <span class="b-form__text">
            Введите корректный адрес эл.почты
        </span>
    </div>

    <button type="submit" class="btn btn--grey btn--big">
        <span>Подписаться</span>
        <svg class='i-icon'>
            <use xlink:href='#icon-arrow-r'/>
        </svg>
    </button>

</form>