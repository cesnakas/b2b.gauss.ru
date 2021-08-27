<?php
use Bitrix\Main\Page\FrameBuffered;
use Citfact\Sitecore\UserDataManager;

$contragent = UserDataManager\UserDataManager::getContrAgentInfo();

$isRegularUser = UserDataManager\UserDataManager::isRegularUser();
?>

<div class="h__lk h-lk">

    <?php
    $frame = new FrameBuffered('auth');

    $frame->begin(); ?>

    <? if ($USER->IsAuthorized()) { ?>
        <div class="h-lk__inner">
            <div class="h-lk-user">
                <svg class='i-icon'>
                    <use xlink:href='#icon-auth-user'/>
                </svg>
                <div class="h-lk-user__inner">
                    <a href="/personal">Личный кабинет</a>
                    <div>
                        <? if (true === $isRegularUser) {
                            echo $contragent['UF_NAME'];
                        } else {
                            echo $USER->GetLastName() . " " . $USER->GetFirstName() . " " . $USER->GetSecondName();
                        } ?>
                    </div>
                </div>
            </div>
            <a href="?logout=yes" class="h-lk__logout">
                <svg class='i-icon'>
                    <use xlink:href='#icon-exit'/>
                </svg>
                <span>Выйти</span>
            </a>
        </div>
    <?php } else { ?>
        <a href="/personal/">
            <img src="/local/client/img/lk-user.png" alt="" class="h-user__img">
        </a>
        <a href="/local/include/modals/auth.php" data-modal="ajax">Войти</a>
        <a href="/auth/?register=yes" class="register_link">Регистрация</a>
    <?php }?>
    <? $frame->beginStub(); ?>
    <a href="/local/include/modals/auth.php" data-modal="ajax">Войти</a>
    <a href="/auth/?register=yes" class="register_link">Регистрация</a>
    <?php $frame->end(); ?>

</div>
