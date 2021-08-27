<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetPageProperty("class", 'auth-page');
$APPLICATION->SetPageProperty("TITLE", "Запрос пароля");
?>
<div class="static-content">

    <p>
        <?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
    </p>
    <?ShowMessage($arParams["~AUTH_RESULT"]);?>
    <form name="bform" method="post" target="_top" class="b-form b-form--small" action="<?=$arResult["AUTH_URL"]?>">
        <?
        if (strlen($arResult["BACKURL"]) > 0)
        {
            ?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
            <?
        }
        ?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="SEND_PWD">

        <div class="title-2">
            <?=GetMessage("AUTH_GET_CHECK_STRING")?>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label><?=GetMessage("AUTH_EMAIL")?>*</span>

            <input type="text" name="USER_EMAIL" maxlength="255" data-f-field>
        </div>

        <div class="b-form__bottom">
            <a href="/personal/profile/" title="<?=GetMessage("AUTH_AUTH")?>" class="link">
                <?=GetMessage("AUTH_AUTH")?>
            </a>

            <button type="submit" class="btn btn--transparent btn--big" name="send_account_info"><?=GetMessage("AUTH_SEND")?></button>
        </div>
        
        
        <?/*<table class="data-table bx-forgotpass-table">
        
        <?if($arResult["USE_CAPTCHA"]):?>
            <tr>
                <td></td>
                <td>
                    <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                </td>
            </tr>
            <tr>
                <td><?echo GetMessage("system_auth_captcha")?></td>
                <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
            </tr>
        <?endif?>
        
        </tbody>
    </table>*/?>

    </form>
</div>