<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Citfact\SiteCore\Core;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle("Вебинары");
$core = Core::getInstance();
?>
    <div class="static-content">
        <div class="ed">
            <div class="ed__main">
                <div class="b-tabs" data-tab-group>
                    <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/education/tabs-head.php"; ?>
                    <div class="b-tabs__content">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "train_an_employee",
                            [
                                "COMPONENT_TEMPLATE" => "tests",
                                "IBLOCK_TYPE" => "education",
                                "IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_EDUCATIONAL_TRAIN_AN_EMPLOYEE),
                                "NEWS_COUNT" => "1",
                                "SORT_BY1" => "NAME",
                                "SORT_ORDER1" => "ASC",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "",
                                "FIELD_CODE" => [
                                    0 => "",
                                    1 => "",
                                ],
                                "PROPERTY_CODE" => [
                                    0 => "FILE",
                                    1 => "LINK",
                                ],
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "Y",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "SET_TITLE" => "N",
                                "SET_BROWSER_TITLE" => "Y",
                                "SET_META_KEYWORDS" => "Y",
                                "SET_META_DESCRIPTION" => "Y",
                                "SET_LAST_MODIFIED" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "Y",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "STRICT_SECTION_CHECK" => "N",
                                "DISPLAY_DATE" => "Y",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "Y",
                                "DISPLAY_PREVIEW_TEXT" => "Y",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "Y",
                                "PAGER_TITLE" => "Новости",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SET_STATUS_404" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => "",
                                "USE_SHARE" => "N"
                            ],
                            false
                        );?>
                    </div>
                </div>
            </div>
            <div class="ed__aside">
                <a href="/local/include/modals/education.php" class="btn btn--transparent" data-modal="ajax" title="Подать заявку на обучение">Подать заявку на обучение</a>
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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>