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
global $APPLICATION; ?>
<? foreach ($arResult['ITEMS'] as $item) {
    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="b-tabs__item active" id="<?= $this->GetEditAreaId($item['ID']); ?>">
        
        <a class="ed-presentation" target="_blank" rel="nofollow"
           href="<?= $item['DISPLAY_PROPERTIES']['LINK']['VALUE'] ?>" title="" data-modal="video">
            <div class="ed-presentation__img">
                <img data-src="<?= $item['PREVIEW_PICTURE']['SRC']['ORIGIN'] ?>"
                     src="<?= $item['PREVIEW_PICTURE']['SRC']['PREVIEW'] ?>"
                     alt="<?=$item['PREVIEW_PICTURE']['ALT']?>"
                     title="<?=$item['PREVIEW_PICTURE']['TITLE']?>"
                     class="lazy lazy--replace">
            </div>
            <div class="ed-presentation__title"><?= $item['NAME'] ?></div>
            <svg class="ed-play">
                <use xlink:href="#icon-play"/>
            </svg>
        </a>

        <?php if (!empty($item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC'])) { ?>
            <a href="<?= $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']; ?>"
               class="btn btn--download">
                <svg class='i-icon'>
                    <use xlink:href='#icon-file'/>
                </svg>
                Скачать презентацию
            </a>
            <? if (in_array($item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['CONTENT_TYPE'], [
                'application/pdf', 'image/png', 'image/jpg', 'image/jpeg'
            ])) { ?>
                <a href="<?= $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC'] ?>" class="btn btn--download" target="_blank">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-file'/>
                    </svg>
                    Посмотреть презентацию
                </a>
            <? } ?>
        <?php } ?>
    </div>
<? } ?>