<?
use Bitrix\Main\Page\FrameBuffered;
use Citfact\Sitecore\UserDataManager;

$contragent = UserDataManager\UserDataManager::getContrAgentInfo();
$isRegularUser = UserDataManager\UserDataManager::isRegularUser();
?>
<div class="h-user">
    <? if ($USER->IsAuthorized()) { ?>
        <svg class='i-icon'>
            <use xlink:href='#icon-lk'/>
        </svg>

        <div class="h-user__inner">
            <div class="h-user__company">
                <?php if (true === $isRegularUser) { ?>
                    <?= $contragent['UF_NAME'] ?>
                <?php } else { ?>
                    <?= $USER->GetLastName() . " " . $USER->GetFirstName() . " " . $USER->GetSecondName(); ?>
                <? } ?>
            </div>
        </div>

        <a href="?logout=yes" class="h-user__logout" title="Выйти">
            <svg class='i-icon'>
                <use xlink:href='#icon-lk-login'/>
            </svg>
        </a>
    <? } ?>
</div>