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
            <a href="/press-center/articles/" class="b-tabs-link active">Статьи</a>
            <a href="/press-center/photo_and_video/" class="b-tabs-link">Фото и видео</a>
        </div>
        <div class="b-tabs__content">
            <div class="b-tabs__item active">
                <div class="blocks blocks--article">
                    <div class="blocks__inner">
                        <? foreach ($arResult['ITEMS'] as $article): ?>
                            <div class="blocks__block">
                                <a href="<?= $article['DETAIL_PAGE_URL'] ?>" class="blocks__img">
                                    <img src="<?= $article['PREVIEW_PICTURE']['src'] ?>"
                                         alt='<?= $article['PREVIEW_PICTURE']['ALT'] ?>'
                                         title="<?=$article['PREVIEW_PICTURE']['TITLE']?>"
                                         class="lazy">
                                </a>
                                <div class="blocks__subtitle"><?= $article['ACTIVE_FROM'] ?></div>
                                <a href="<?= $article['DETAIL_PAGE_URL'] ?>"
                                   title="<?= $article['NAME'] ?>"
                                   class="title-3 blocks__title blocks__title--upper"><?= $article['NAME'] ?></a>


                                <a href="<?=$article['DETAIL_PAGE_URL']?>"
                                    rel="nofollow"
                                    title="<?=mb_strtoupper($article['NAME'])?>"
                                    class="blocks__tag">
                                    <span><b>Статья</b></span>
                                </a>
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