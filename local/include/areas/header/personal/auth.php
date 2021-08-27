<?
use Bitrix\Main\Page\FrameBuffered;
use Citfact\Sitecore\UserDataManager;

$contragent = UserDataManager\UserDataManager::getContrAgentInfo();
$isRegularUser = UserDataManager\UserDataManager::isRegularUser();
?>
<div class="h-user">
    <a href="/personal/">
        <img src="/local/client/img/lk-user.png" alt="" class="h-user__img">
    </a>

    <div class="h-user__inner">
        <?php if (true === $isRegularUser) { ?>
            <div class="h-user__company"><?= $contragent['UF_NAME'] ?></div>
        <?php } ?>
        <div class="h-user__name">
            <?= $USER->GetLastName() . " " . $USER->GetFirstName() . " " . $USER->GetSecondName(); ?>
        </div>
    </div>

    <a href="?logout=yes" class="h-user__logout" title="Выйти">
        <svg class='i-icon'>
            <use xlink:href='#icon-lk-login'/>
        </svg>
    </a>
</div>