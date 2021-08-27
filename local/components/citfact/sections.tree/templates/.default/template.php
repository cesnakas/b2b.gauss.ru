<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
//$GLOBALS['APPLICATION']->AddHeadScript($templateFolder . '/script.js');
//$GLOBALS['APPLICATION']->SetAdditionalCSS($templateFolder . '/style.css');
?>
<?$this->setFrameMode(true);?>
Дерево разделов
<?foreach($arResult['SECTIONS_TREE'] as $arSection):?>
    <?=$arSection['NAME']?>
<?endforeach?>