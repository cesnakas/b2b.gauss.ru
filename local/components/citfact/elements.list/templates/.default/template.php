<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


?>
<?$this->setFrameMode(true);?>
<?foreach($arResult['ITEMS'] as $arItem):?>
    <?=$arItem['NAME']?><br>
<?endforeach?>