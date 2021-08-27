<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Citfact\SiteCore\Core;

$core = Core::getInstance();

$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"mobile_header",
	array(
		"CATEGORY_0" => array(
			0 => "no",
		),
		"CATEGORY_0_TITLE" => "",
		"CATEGORY_0_iblock_1c_catalog" => array(
			0 => "10",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "1",
		),
		"CATEGORY_OTHERS_TITLE" => "",
		"CHECK_DATES" => "N",
		"NUM_CATEGORIES" => "1",
		"ORDER" => "date",
		"PAGE" => "#SITE_DIR#search/index.php",
		"SHOW_OTHERS" => "N",
		"TOP_COUNT" => "5",
		"USE_LANGUAGE_GUESS" => "Y",
		"COMPONENT_TEMPLATE" => "top_header",
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "title-search"
	),
	false
);?>
