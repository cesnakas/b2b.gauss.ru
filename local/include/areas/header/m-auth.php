<?php

use Bitrix\Main\Page\FrameBuffered;

$frame = new FrameBuffered('auth_m');

$frame->begin();


if ($USER->IsAuthorized()) { ?>
    <a href="/personal/" class="h__lk-m" title="Личный кабинет">
        <svg class='i-icon'>
            <use xlink:href='#icon-lk'/>
        </svg>
    </a>
<?php } else { ?>
    <a href="/local/include/modals/auth.php" data-modal="ajax" class="h__lk-m" title="Личный кабинет">
        <svg class='i-icon'>
            <use xlink:href='#icon-lk'/>
        </svg>
    </a>
<?php }

$frame->beginStub(); ?>
    <a href="/local/include/modals/auth.php" data-modal="ajax" class="h__lk-m" title="Личный кабинет">
        <svg class='i-icon'>
            <use xlink:href='#icon-lk'/>
        </svg>
    </a>
<?php $frame->end(); ?>
