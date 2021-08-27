<div class="b-authorize__container">
    <? $cUser = new \CUser(); ?>
    <? $frame = new \Bitrix\Main\Page\FrameBuffered($arParams['ID']); ?>
    <? $frame->begin(); ?>
    <? if ($cUser->IsAuthorized()):
        $name = $cUser->GetFirstName() ?: 'Личный кабинет';
        ?>
        <a href="/account/" class="b-authorize" title="<?php echo $name; ?>">
            <svg class='i-icon b-authorize__icon'>
                <use xlink:href='#icon-folder'/>
            </svg>
            <span class="b-authorize__text"><?= $name; ?></span>
        </a>
        <span class="b-authorize__slash">|</span>
        <a href="<?= $APPLICATION->GetCurPageParam("logout=yes", array(
            "login",
            "logout",
            "register",
            "forgot_password",
            "change_password")); ?>" class="header-personal__profile" title="Выйти">
            Выйти
        </a>
    <? else: ?>
        <a href="/local/include/modals/auth.php" class="b-authorize" data-modal="ajax" title="Вход в ЛК">
            <svg class='i-icon b-authorize__icon'>
                <use xlink:href='#icon-folder'/>
            </svg>
            <span class="b-authorize__text">Вход в ЛК</span>
        </a>
    <? endif; ?>
    <? $frame->beginStub(); ?>
    <div class="b-authorize">
        <svg class='i-icon b-authorize__icon'>
            <use xlink:href='#icon-folder'/>
        </svg>
        <span class="b-authorize__text">Вход в ЛК</span>
    </div>
    <? $frame->end(); ?>
</div>
