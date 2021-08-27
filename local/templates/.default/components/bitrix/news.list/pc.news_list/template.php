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
            <a href="/press-center/news/" class="b-tabs-link active">Новости</a>
            <a href="/press-center/articles/" class="b-tabs-link">Статьи</a>
            <a href="/press-center/photo_and_video/" class="b-tabs-link">Фото и видео</a>
        </div>
        <div class="b-tabs__content">
            <div class="b-tabs__item active">
                <div class="b-tabs">
                    <div class="b-tabs-head b-tabs-head--light">
                        <a href="/press-center/news/"
                           class="b-tabs-link <?= $cur_page == '/press-center/news/' ? 'active' : '' ?>">Все новости
                        </a>
                        <? foreach ($arResult['SECTIONS'] as $section): ?>
                            <a href="<?= $section['SECTION_PAGE_URL'] ?>"
                               class="b-tabs-link <?= $cur_page == $section['SECTION_PAGE_URL'] ? 'active' : '' ?>"><?= $section['NAME'] ?></a>
                        <? endforeach ?>
                    </div>
                    <div class="b-tabs__content">
                        <div class="b-tabs__item active">
                            <div class="news">
                                <div class="news__items">
                                    <? foreach ($arResult["ITEMS"] as $arItem) {

                                        $unixDate = MakeTimeStamp($arItem["DISPLAY_PROPERTIES"]['DATE']['DISPLAY_VALUE']);

                                        $formatDate = formatDate('f, Y', $unixDate);

                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                                        ?>
                                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                                           id="<?php echo $this->GetEditAreaId($arItem['ID']); ?>"
                                           class="news__item">
                                            <img src="<?=$arItem['PREVIEW_PICTURE']['src']?>"
                                                 data-src=""
                                                 data-src-small=""
                                                 alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>"
                                                 title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
                                                 class="lazy">
                                            <div class="news__inner">
                                                <div class="news__date"><?= $formatDate; ?></div>
                                                <div class="title-3" data-news-text><?= $arItem["NAME"] ?></div>
                                                <div class="btn btn--transparent btn--small">
                                                    <span>Новость</span>
                                                </div>
                                            </div>
                                        </a>
                                    <? } ?>
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
        </div>
    </div>
</div>


