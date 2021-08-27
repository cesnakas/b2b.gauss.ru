<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
$APPLICATION->SetPageProperty("class", 'auth-page');
$APPLICATION->SetPageProperty("TITLE", "Изменение пароля");
?>

<div class="static-content">
    <div class="title-2"><span>Введите контрольную строку, новый пароль и подтверждение пароля</span></div>

    <form method="post" class="b-form b-form--small" action="<?=$arResult["AUTH_FORM"]?>" name="bform"
          data-form-validation="changepasswd">
        <? if ($arParams["~AUTH_RESULT"]['TYPE'] == 'OK'): ?>
            <p class="result_cont">
                <?= $arParams["~AUTH_RESULT"]['MESSAGE']; ?>
            </p>
        <? endif ?>

        <? if ($arParams["~AUTH_RESULT"]['TYPE'] == 'ERROR'): ?>
            <p class="red">
                <?= $arParams["~AUTH_RESULT"]['MESSAGE']; ?>
            </p>
        <? endif ?>

        <?if (strlen($arResult["BACKURL"]) > 0): ?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <? endif ?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="CHANGE_PWD">


        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label>E-mail</span>

            <input type="text" name="USER_LOGIN" id="USER_LOGIN"
                   class="b-input__input" data-required="email"
                   value="<?= $_REQUEST['USER_LOGIN'] ?>"
                   maxlength="255" data-f-field>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label><?=GetMessage("AUTH_CHECKWORD")?></span>

            <input type="text" name="USER_CHECKWORD" id="USER_CHECKWORD"
                   class="b-input__input" data-required
                   value="<?= $_REQUEST['USER_CHECKWORD'] ?>"
                   maxlength="50" data-f-field>
        </div>

        <div class="b-form__item" data-f-item>
            <span class="b-form__label" data-f-label><?=GetMessage("AUTH_NEW_PASSWORD")?></span>

            <input type="password" name="USER_PASSWORD" id="USER_PASSWORD"
                   class="b-input__input" data-required
                   value="<?= $_REQUEST['USER_PASSWORD'] ?>"
                   maxlength="50" data-f-field>
        </div>

        <?if($arResult["SECURE_AUTH"]):?>
            <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
                        <div class="bx-auth-secure-icon"></div>
                    </span>
            <noscript>
                    <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
                        <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                    </span>
            </noscript>
            <script>
                document.getElementById('bx_auth_secure').style.display = 'inline-block';
            </script>
        <?endif?>


        <div class="b-form__item" data-f-item>
            <span for="USER_CONFIRM_PASSWORD"
                  class="b-form__label"
                  data-f-label>
                <?=GetMessage("AUTH_NEW_PASSWORD_REQ")?>
            </span>

            <input type="password" name="USER_CONFIRM_PASSWORD" id="USER_CONFIRM_PASSWORD"
                   class="b-input__input" data-required
                   value="<?= $_REQUEST['USER_CONFIRM_PASSWORD'] ?>"
                   data-f-field
                   maxlength="50">
        </div>


        <div class="b-form__bottom">
            <a href="/personal/profile/" class="link" title="Авторизоваться">Авторизоваться</a>

            <button type="submit" name="change_pwd" class="btn btn--transparent btn--big">Восстановить</button>
        </div>
    </form>
</div>
