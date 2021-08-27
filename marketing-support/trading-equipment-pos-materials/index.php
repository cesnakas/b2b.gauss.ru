<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Citfact\SiteCore\Core;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle("Торговое оборудование и POS материалы");
$core = Core::getInstance();
?>
    <div class="static-content static-flex-wrap">
        <div class="b-tabs">
            <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/marketing-support/tabs-head.php"; ?>
            <? $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "trading_equipment_pos_materials",
                [
                    "COMPONENT_TEMPLATE" => "trading_equipment_pos_materials",
                    "IBLOCK_TYPE" => "marketing_support",
                    "IBLOCK_ID" => $core->getIblockId($core::IBLOCK_CODE_MARKETING_SUPPORT_TRADING_EQUIPMENT_POS_MATERIALS),
                    "NEWS_COUNT" => "10",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "",
                    "FIELD_CODE" => [
                        0 => "",
                        1 => "",
                    ],
                    "PROPERTY_CODE" => [
                        0 => "",
                        1 => "",
                    ],
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "Y",
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
            ); ?>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>