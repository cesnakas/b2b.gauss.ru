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
$this->setFrameMode(true);
?>
<div class="manager-modal" data-block-modal>
    <div></div>
    <div>Вы авторизованы как: <?= $arResult['USER_NAME']; ?></div>
    <div>
        <a href="<?= $APPLICATION->GetCurDir() ?>?AUTHORIZE_BY_MANAGER=Y" class="h-user__logout" title="Вернуться к своему профилю">
            <span class="manager-modal__text">Вернуться к своему профилю</span>
            <svg class='i-icon'>
                <use xlink:href='#icon-lk-login'/>
            </svg>
        </a>
    </div>
    <div class="manager-modal__close" data-block-close>
        <div class="plus plus--cross"></div>
    </div>
</div>