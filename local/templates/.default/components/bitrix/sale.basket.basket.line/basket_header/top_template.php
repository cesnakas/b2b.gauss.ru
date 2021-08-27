<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */

global $USER;
?>

<? if ($arParams['MOBILE']) { ?>

    <? if ($arResult['COMPOSITE_STUB'] == 'Y') { ?>
        <a href="/local/include/modals/auth.php" data-modal="ajax" class="h__cart-m">
            <svg class='i-icon'>
                <use xlink:href='#icon-cart'/>
            </svg>
            <span>0</span>
        </a>
    <?} else { ?>
        <? if ($USER->IsAuthorized()) { ?>
            <a href="<?= $arParams['PATH_TO_BASKET'] ?>" class="h__cart-m">
                <svg class='i-icon'>
                    <use xlink:href='#icon-cart'/>
                </svg>
                <span><?= $arResult['NUM_PRODUCTS'] ?></span>
            </a>
        <? } else { ?>
            <a href="/local/include/modals/auth.php" data-modal="ajax" class="h__cart-m">
                <svg class='i-icon'>
                    <use xlink:href='#icon-cart'/>
                </svg>
                <span><?= $arResult['NUM_PRODUCTS'] ?></span>
            </a>
        <? } ?>
    <? } ?>

<? } else {

    if ($arResult['COMPOSITE_STUB'] == 'Y') { ?>
        <a href="<?= $arParams['PATH_TO_BASKET'] ?>" class="h__cart">
            <svg class='i-icon'>
                <use xlink:href='#icon-cart'/>
            </svg>
            <div class="h__cart-inner">
                <span>0 ₽ </span><span>(0)</span>
            </div>
        </a>

        <a href="/order/" class="btn btn--grey">Оформить заказ</a>
    <? } else {
        if ($USER->IsAuthorized()) { ?>
            <a href="<?= $arParams['PATH_TO_BASKET'] ?>" class="h__cart">
                <svg class='i-icon'>
                    <use xlink:href='#icon-cart'/>
                </svg>
                <div class="h__cart-inner">
                    <span><?= $arResult['TOTAL_PRICE'] ?> </span><span>(<?= $arResult['NUM_PRODUCTS'] ?>)</span>
                </div>
            </a>

            <a href="/order/" class="btn btn--grey">Оформить заказ</a>

        <? } else { ?>
            <a href="/local/include/modals/auth.php" data-modal="ajax" class="h__cart">
                <svg class='i-icon'>
                    <use xlink:href='#icon-cart'/>
                </svg>
                <div class="h__cart-inner">
                    <span>0 ₽ </span><span>(0)</span>
                </div>
            </a>

            <a href="/local/include/modals/auth.php" data-modal="ajax" class="btn btn--grey">Оформить заказ</a>
        <? }
    }
}