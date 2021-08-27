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

?>
<h2>Пользователи <?= $arParams['COMPANY_NAME'] ?></h2>
<? if ($arResult['AUTHORIZE_AS_USER']): ?>
    <? if ($arResult['AUTHORIZE_AS_USER'] == 'SUCCESS'): ?>
        <h3>Такого быть не должно! </h3>
    <? else: ?>
        <h3><?= $arResult['ERROR'] ?></h3>
    <? endif ?>
<? endif ?>

<? if ($arResult['ITEMS']): ?>

    <div class="basket basket--company">
        <div class="basket-item basket-item--top">
            <div class="basket-item__description">Ф.И.О. пользователя</div>
            <div class="basket-item__info">Телефон</div>
            <div class="basket-item__info">E-mail</div>
            <div class="basket-item__btn"></div>
        </div>
        <div class="basket__items">


            <? foreach ($arResult['ITEMS'] as $item): ?>
                <div class="basket-item">
                    <div class="basket-item__description">
                        <span class="basket-item__title"><?= $item['LAST_NAME'] . " " . $item['NAME'] ?></span>
                    </div>
                    <div class="basket-item__info">
                        <div class="basket-item__t">Телефон</div>
                        <span><?= $item['PERSONAL_PHONE'] ?></span>
                    </div>
                    <div class="basket-item__info">
                        <div class="basket-item__t">E-mail</div>
                        <span><?= $item['EMAIL'] ?></span>
                    </div>
                    <div class="basket-item__btn">
                        <form action="<?= $APPLICATION->GetCurPage(false) ?>" method="post">
                            <button class="btn btn--transparent btn--small" type="submit">Авторизоваться</button>
                            <input type="hidden" name="AUTHORIZE_AS_USER" value="Y">
                            <input type="hidden" name="USER_ID" value="<?= $item['ID'] ?>">
                        </form>
                        <div>
                            <a href="/local/include/modals/delete_user.php?user_id=<?php echo $item['ID']; ?>"
                               data-modal="ajax"
                               class="btn btn--transparent btn--small">
                                Удалить
                            </a>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
<? else: ?>
    Пользователи не найдены.
<? endif ?>

<h2>Список удаленных пользователей <?= $arParams['COMPANY_NAME'] ?> </h2>

<div class="basket basket--company">
    <div class="basket-item basket-item--top">
        <div class="basket-item__description">Ф.И.О. пользователя</div>
        <div class="basket-item__info">Телефон</div>
        <div class="basket-item__info">E-mail</div>
        <div class="basket-item__btn"></div>
    </div>
    <div class="basket__items">


        <? foreach ($arResult['ITEMS_FOR_DELETE'] as $item): ?>
            <div class="basket-item">
                <div class="basket-item__description">
                    <span class="basket-item__title"><?= $item['LAST_NAME'] . " " . $item['NAME'] ?></span>
                </div>
                <div class="basket-item__info">
                    <div class="basket-item__t">Телефон</div>
                    <span><?= $item['PERSONAL_PHONE'] ?></span>
                </div>
                <div class="basket-item__info">
                    <div class="basket-item__t">E-mail</div>
                    <span><?= $item['EMAIL'] ?></span>
                </div>
                <div class="basket-item__btn">
                    <form action="<?= $APPLICATION->GetCurPage(false) ?>" method="post">
                        <button class="btn btn--transparent btn--small" type="submit">Восстановить</button>
                        <input type="hidden" name="ACCEPT_USER" value="Y">
                        <input type="hidden" name="USER_ID" value="<?= $item['ID'] ?>">
                    </form>
                    <form action="<?= $APPLICATION->GetCurPage(false) ?>" method="post">
                        <button class="btn btn--grey btn--small" type="submit">Удалить с сайта</button>
                        <input type="hidden" name="DELETE_USER" value="Y">
                        <input type="hidden" name="USER_ID" value="<?= $item['ID'] ?>">
                    </form>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>