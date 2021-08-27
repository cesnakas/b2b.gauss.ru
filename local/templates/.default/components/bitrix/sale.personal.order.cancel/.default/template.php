<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="lk__section">

    <div class="bx_my_order_cancel">
        <?if(strlen($arResult["ERROR_MESSAGE"])<=0):?>
            <form method="post" action="<?=POST_FORM_ACTION_URI?>" class="b-form">

                <input type="hidden" name="CANCEL" value="Y">
                <?=bitrix_sessid_post()?>
                <input type="hidden" name="ID" value="<?=$arResult["ID"]?>">

                <?=GetMessage("SALE_CANCEL_ORDER1") ?>

                <a href="<?=$arResult["URL_TO_DETAIL"]?>" class="link"><?=GetMessage("SALE_CANCEL_ORDER2")?> #<?=$arResult["ACCOUNT_NUMBER"]?></a>?
                <p><?= GetMessage("SALE_CANCEL_ORDER3") ?></p>

                <div class="b-form__item b-form__item--textarea" data-f-item>
                    <span class="b-form__label" data-f-label><?= GetMessage("SALE_CANCEL_ORDER4") ?> *</span>
                    <textarea name="REASON_CANCELED" data-f-field></textarea>
                </div>


                <div class="b-form__bottom">
                    <button type="submit" name="action" value="<?=GetMessage("SALE_CANCEL_ORDER_BTN") ?>" class="btn btn--grey"><span><?=GetMessage("SALE_CANCEL_ORDER_BTN") ?></span></button>
                </div>

            </form>
        <?else:?>
            <p class="red"><?=ShowError($arResult["ERROR_MESSAGE"]);?></p>
        <?endif;?>

    </div>

    <a href="<?=$arResult["URL_TO_LIST"]?>" class="link-more link-more--back">
        <svg class='i-icon'>
            <use xlink:href='#icon-arrow-r'/>
        </svg>
        <span><?=GetMessage("SALE_RECORDS_LIST")?></span>
    </a>
</div>