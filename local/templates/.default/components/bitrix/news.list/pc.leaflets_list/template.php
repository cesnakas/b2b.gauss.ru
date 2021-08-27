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
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
$cur_page = $APPLICATION->GetCurPage(false);
?>
<div class="static-content">
    <div class="b-tabs">
        <div class="b-tabs-head">
            <a href="/press-center/news/" class="b-tabs-link">Новости</a>
            <a href="/press-center/articles/" class="b-tabs-link">Статьи</a>
            <a href="/press-center/photo_and_video/" class="b-tabs-link">Фото и видео</a>
        </div>
        <div class="b-tabs__content">
            <div class="b-tabs__item active">
                <div class="blocks">
                    <div class="blocks__inner">
                        <? foreach ($arResult['ITEMS'] as $leaflet): ?>

                                <div class="blocks__card">
                                    <div class="blocks__img">
                                        <img src="<?= $leaflet['PREVIEW_PICTURE']['src'] ?>"
                                             alt='<?= $leaflet['PREVIEW_PICTURE']['ALT'] ?>'
                                             title="<?=$leaflet['PREVIEW_PICTURE']['TITLE']?>"
                                             class="lazy">
                                    </div>
                                    <div class="blocks__title"><?= $leaflet['NAME'] ?></div>
                            <? if (!empty($leaflet['DISPLAY_PROPERTIES']['BOOKLET'])): ?>
                                        <a href="<?= $leaflet['DISPLAY_PROPERTIES']['BOOKLET']['FILE_VALUE']['SRC'] ?>"
                                           download="<?= $leaflet['NAME'] ?>"
                                           class="link-download">
                                            <svg class='i-icon'>
                                                <use xlink:href='#icon-file'/>
                                            </svg>
                                            Скачать
                                        </a>
                            <? endif ?>
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
</div>
