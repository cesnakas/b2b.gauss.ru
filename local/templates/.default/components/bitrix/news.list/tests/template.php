<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$core = Core::getInstance();
global $APPLICATION;
?>
<div class="static-content">
    <div class="ed">
        <div class="ed__main">
            <div class="b-tabs">
                <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/education/tabs-head.php"; ?>
                <div class="b-tabs__content">
                    <div class="b-tabs__item active">
                        <div class="blocks blocks--tests">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:news.list",
                                "presentations_tests",
                                [
                                    "COMPONENT_TEMPLATE" => "presentations_tests",
                                    "IBLOCK_TYPE" => "education",
                                    "IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_PRESENTATIONS_TESTS),
                                    "NEWS_COUNT" => "10",
                                    "SORT_BY1" => "NAME",
                                    "FIELD_CODE" => "",
                                    "PROPERTY_CODE" => [
                                        0 => "PRESENTATION",
                                    ],
                                    "CHECK_DATES" => "Y",
                                    "AJAX_MODE" => "N",
                                    "AJAX_OPTION_JUMP" => "N",
                                    "AJAX_OPTION_STYLE" => "Y",
                                    "AJAX_OPTION_HISTORY" => "N",
                                    "AJAX_OPTION_ADDITIONAL" => "",
                                    "CACHE_TYPE" => "A",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_FILTER" => "N",
                                    "CACHE_GROUPS" => "Y",
                                    "SET_TITLE" => "N",
                                    "SET_BROWSER_TITLE" => "Y",
                                    "SET_META_KEYWORDS" => "Y",
                                    "SET_META_DESCRIPTION" => "Y",
                                    "SET_LAST_MODIFIED" => "N",
                                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                    "ADD_SECTIONS_CHAIN" => "Y",
                                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                    "INCLUDE_SUBSECTIONS" => "Y",
                                    "STRICT_SECTION_CHECK" => "N",
                                    "DISPLAY_NAME" => "Y",
                                    "PAGER_TEMPLATE" => ".default",
                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                    "PAGER_BASE_LINK_ENABLE" => "N",
                                    "SET_STATUS_404" => "N",
                                    "SHOW_404" => "N",
                                    "USE_SHARE" => "N"
                                ],
                                false
                            );?>


                            <div class="blocks__inner">
                                <? foreach ($arResult['ITEMS'] as $arItem) {
                                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                    ?>
                                    <div class="blocks__card" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                                        <div class="blocks__img">
                                            <img data-src="<?= $arItem['PREVIEW_PICTURE']['SRC']['ORIGIN'] ?>"
                                                 src="<?= $arItem['PREVIEW_PICTURE']['SRC']['PREVIEW'] ?>"
                                                 alt='<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>'
                                                 title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>"
                                                 class="lazy">
                                        </div>
                                        <div class="blocks__title"><?= $arItem['NAME'] ?></div>
                                        <div class="blocks__text">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>

                                        <a href="<?= $arItem['DISPLAY_PROPERTIES']['FILE_TEST']['FILE_VALUE']['SRC']; ?>"
                                           class="link-download">
                                            <svg class='i-icon'>
                                                <use xlink:href='#icon-file'/>
                                            </svg>
                                            Скачать
                                        </a>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ed__aside">
            <a href="/local/include/modals/education.php" class="btn btn--transparent" data-modal="ajax">Подать заявку на
                обучение</a>
            <div class="ed-tests b-tabs__item active" data-tab-body-extend>
                <div class="title-4">Ответы на тесты</div>
                <? foreach ($arResult['ITEMS'] as $arItem) {
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <a href="<?= $arItem['DISPLAY_PROPERTIES']['FILE_ANSWER']['FILE_VALUE']['SRC'] ?>"
                       class="ed-tests__item" download>
                        <span><?= $arItem['NAME'] ?></span>
                        <svg class='i-icon'>
                            <use xlink:href='#icon-file'/>
                        </svg>
                    </a>
                <? } ?>
            </div>
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
</div>