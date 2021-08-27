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
$cur_page = $APPLICATION->GetCurPage(false);
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
?>
<div class="static-content">
    <div class="b-tabs">
        <div class="b-tabs-head">
            <a href="/press-center/news/" class="b-tabs-link">Новости</a>
            <a href="/press-center/articles/" class="b-tabs-link">Статьи</a>
            <a href="/press-center/photo_and_video/" class="b-tabs-link active">Фото и видео</a>
        </div>
        <div class="b-tabs__content">
            <div class="b-tabs__item active">
                <div class="blocks">
                    <div class="blocks__inner">
                        <? foreach ($arResult['ITEMS'] as $content): ?>
                            <? $isVideo = !empty($content['DISPLAY_PROPERTIES']['VIDEO']) ?>
                            <div class="blocks__block">
                                <a href="<?= $isVideo ? $content['DISPLAY_PROPERTIES']['VIDEO']['VALUE'] : $content['PREVIEW_PICTURE']['SRC'] ?>"
                                   class="blocks__img <?= $isVideo ? 'blocks__img--video' : '' ?>"
                                   data-modal="<?= $isVideo ? 'video' : 'image' ?>">
                                    <img src="<?= $content['PREVIEW_PICTURE']['src'] ?>"
                                         alt='<?= $content['PREVIEW_PICTURE']['ALT'] ?>'
                                         title="<?=$content['PREVIEW_PICTURE']['TITLE']?>"
                                         class="lazy">
                                    <? if ($isVideo) : ?>
                                        <svg class="ed-play">
                                            <use xlink:href="#icon-play"/>
                                        </svg>
                                    <? endif ?>
                                </a>
                                <a href="<?= $isVideo ? $content['DISPLAY_PROPERTIES']['VIDEO']['VALUE'] : $content['PREVIEW_PICTURE']['SRC'] ?>"
                                   class="title-3 blocks__title" data-modal="<?= $isVideo ? 'video' : 'image' ?>"><?= $content['NAME'] ?></a>
                            </div>
                        <? endforeach ?>

                    </div>
                    <div class="blocks__bottom" id="btn_<?= $bxajaxid ?>">
                        <? if ($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer < $arResult["NAV_RESULT"]->nEndPage): ?>
                            <a href="javascript:void(0)" data-ajax-id="<?= $bxajaxid ?>"
                               data-show-more="<?= $arResult["NAV_RESULT"]->NavNum ?>"
                               data-next-page="<?= ($arResult["NAV_RESULT"]->NavPageNomer + 1) ?>"
                               data-max-page="<?= $arResult["NAV_RESULT"]->nEndPage ?>"
                               class="btn btn--loading">
                                <svg class='i-icon'>
                                    <use xlink:href='#icon-loading'/>
                                </svg>
                                <span>Загрузить ещё</span>
                                <span>Загружается</span>
                            </a>
                        <? endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
