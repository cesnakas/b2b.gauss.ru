<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->addExternalJs($componentPath.'/script.js');
?>
<?$frame = $this->createFrame()->begin();?>
    <script>
        BX.message({
            arParams_<?=$arResult['PARAMS_HASH']?>: <?=CUtil::PhpToJSObject($arParams)?>
        });
    </script>

    <form action="#" name="citfact_form_ajax">
        <?=bitrix_sessid_post()?>
        <input type="text" name="yarobot" value="" class="hide" placeholder="Я робот">
        <input type="text" name="iblockId" value="<?=$arParams['IBLOCK_ID']?>" class="hide">
        <input type="text" name="paramsHash" value="<?=$arResult['PARAMS_HASH']?>" class="hide">

        <?foreach ($arResult['SHOW_PROPERTIES'] as $arProp):?>
            <?if ($arProp['PARAMS_TYPE'] == 'text'):?>
                <div>
                    <input type="text" name="<?=$arProp['CODE']?>" class="<?=$arProp['REQUIRED'] == 'Y'? 'required':''?> <?=$arProp['CODE'] == 'EMAIL'? 'email':''?>" placeholder="<?=$arProp['PLACEHOLDER']?>">
                    <div class="active b-form__error">
                        Некорректно заполнено поле
                    </div>
                </div>
            <?endif?>
        <?endforeach?>

        <div class="errors_cont"></div>
        <div class="result_cont"></div>
        <div class="success_cont"><?=$arParams['~SUCCESS_MESSAGE']?></div>

        <button type="submit">Отправить</button>
    </form>
<?$frame->end();?>