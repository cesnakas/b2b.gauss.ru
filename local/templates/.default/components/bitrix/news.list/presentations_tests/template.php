<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
global $APPLICATION;
?>
<div class="ed__links">
    <? foreach ($arResult['ITEMS'] as $arItem) {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        $src = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 370, 'height' => 300], BX_RESIZE_IMAGE_EXACT)['src'];
        ?>
      <a id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
         href="<?=$arItem["DISPLAY_PROPERTIES"]["PRESENTATION"]["FILE_VALUE"]["SRC"]?>"
         title="<?=$arItem["NAME"]?>" class="link-download" download>
          <img src="<?= $src?>" alt=""> <br>
        <svg class='i-icon'>
          <use xlink:href='#icon-file'/>
        </svg>
          <?=$arItem["NAME"]?></a>&nbsp; &nbsp;
    <?}?>
</div>
