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
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
?>
    <div class="static-content">
        <div class="ed">
            <div class="ed__main">
                <div class="b-tabs">
                    <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/education/tabs-head.php"; ?>
                    <div class="b-tabs__content">
                        <div class="b-tabs__item active">
                            <div class="ed-media">
                                <? foreach ($arResult['ITEMS'] as $arItem) { ?>
                                    <div class="ed-media__card">
                                        <a class="ed-media__content" target="_blank" rel="nofollow"
                                           href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>" data-modal="video">
                                            <img data-src="<?= $arItem['PREVIEW_PICTURE']['SRC']['ORIGIN'] ?>"
                                                 src="<?= $arItem['PREVIEW_PICTURE']['SRC']['PREVIEW'] ?>"
                                                 alt="<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>"
                                                 title="<?= $arItem['PREVIEW_PICTURE']['TITLE'] ?>"
                                                 class="lazy">
                                            <svg class="ed-play">
                                                <use xlink:href="#icon-play"/>
                                            </svg>
                                        </a>
                                        <div class="ed-media__title"><?= $arItem['NAME'] ?></div>
                                        
                                        
                                        <?php if (!empty($arItem['DISPLAY_PROPERTIES']['VIDEO_FOR_DOWNLOADING']['FILE_VALUE']['SRC'])) { ?>
                                            <a href="/local/include/php/downloader.php?path=<?=$arItem['DISPLAY_PROPERTIES']['VIDEO_FOR_DOWNLOADING']['FILE_VALUE']['SRC']; ?>" class="link-download">
                                                <svg class="i-icon">
                                                    <use xlink:href="#icon-file"/>
                                                </svg>
                                                <span>Скачать</span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ed__aside">
                <a href="/local/include/modals/education.php" class="btn btn--transparent" data-modal="ajax">Подать заявку на
                    обучение</a>
                <div class="ed-banner">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "",
                        [
                            "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                            "PATH" => '/local/include/areas/education/banner-img.php',    // Путь к файлу области
                        ],
                        false
                    ); ?>
                    <div class="ed-banner__inner">
                        <div class="ed-banner__title">
                            <?
                            $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                [
                                    "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                                    "PATH" => '/local/include/areas/education/banner-title.php',    // Путь к файлу области
                                ],
                                false
                            ); ?>
                        </div>
                        <div class="ed-banner__text">
                            <?
                            $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                [
                                    "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                                    "PATH" => '/local/include/areas/education/banner-text.php',    // Путь к файлу области
                                ],
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ed__bottom" id="btn_<?= $bxajaxid ?>">
            <? if ($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer < $arResult["NAV_RESULT"]->nEndPage): ?>
                <a href="javascript:void(0)" data-ajax-id="<?= $bxajaxid ?>"
                   data-show-more="<?= $arResult["NAV_RESULT"]->NavNum ?>"
                   data-next-page="<?= ($arResult["NAV_RESULT"]->NavPageNomer + 1) ?>"
                   data-max-page="<?= $arResult["NAV_RESULT"]->nEndPage ?>"
                   class="btn btn--loading btn--orange">
                    <svg class='i-icon'>
                        <use xlink:href='#icon-loading'/>
                    </svg>
                    <span>Загрузить ещё</span>
                    <span>Загружается</span>
                </a>
            <? endif ?>
        </div>
    </div>