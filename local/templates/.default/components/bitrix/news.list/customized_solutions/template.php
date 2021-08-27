<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;

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

$core = Core::getInstance();

$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
?>
<div class="b-tabs__content">
    <div class="b-tabs__item active">
        <div class="blocks">
            <?if($arParams['HIDE_FORM'] != 'Y'){?>
                <div class="blocks__top">
                    <a href="/local/include/modals/customized-solutions.php" data-modal="ajax" class="btn btn--transparent">Отправить
                        заявку</a>
                </div>
            <?}?>
            <div class="blocks__inner blocks__inner--solutions" id="block_<?= $bxajaxid ?>">
                <!-- items -->
                <? foreach ($arResult['ITEMS'] as $key => $arItem):
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="blocks__card" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                        <a href="<?= $arItem['PREVIEW_PICTURE']['SRC']['SCREEN']; ?>" data-modal="image" class="blocks__img">
                            <img data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']['ORIGIN']?>"
                                 src="<?= $key < 5 ? $arItem['PREVIEW_PICTURE']['SRC']['PREVIEW'] : $core::IMAGE_PLACEHOLDER_TRANSPARENT ?>"
                                 alt='<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>'
                                 title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
                                 class="lazy">
                        </a>
                        <div class="blocks__title"><?= $arItem['NAME'] ?></div>
                        <? if ($arItem['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']) { ?>
                            <a href="/local/include/php/downloader.php?path=<?= $arItem['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']; ?>"
                               class="link-download" download="<?= $arItem['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['ORIGINAL_NAME'] ?>">
                                <svg class='i-icon'>
                                    <use xlink:href='#icon-file'/>
                                </svg>
                                Скачать
                            </a>
                        <? } ?>

                    </div>
                <? endforeach; ?>
                <!-- /items -->
            </div>
            <? if ($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer < $arResult["NAV_RESULT"]->nEndPage): ?>
            <!-- btn pagination -->
            <div class="blocks__bottom" id="btn_<?= $bxajaxid ?>">
                    <a class="btn btn--loading"
                       data-ajax-id="<?= $bxajaxid ?>"
                       href="javascript:void(0)"
                       data-show-more="<?= $arResult["NAV_RESULT"]->NavNum ?>"
                       data-next-page="<?= ($arResult["NAV_RESULT"]->NavPageNomer + 1) ?>"
                       data-max-page="<?= $arResult["NAV_RESULT"]->nEndPage ?>">
                        <svg class='i-icon'>
                            <use xlink:href='#icon-loading'/>
                        </svg>
                        <span>Загрузить ещё</span>
                        <span>Загружается</span>
                    </a>
            </div>
                <!-- /btn pagination -->
            <? endif ?>
        </div>
    </div>
</div>
