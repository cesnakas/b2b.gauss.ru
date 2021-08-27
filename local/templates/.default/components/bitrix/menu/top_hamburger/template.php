<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Citfact\SiteCore\Core;

$core = Core::getInstance();

if (empty($arResult["ALL_ITEMS"]))
	return;

CJSCore::Init();
?>
<div class="h-menu-d">
    <div class="h-menu-d__inner">
        <div class="container">
            <div class="h-menu-d__links">
                <? foreach ($arResult['ALL_ITEMS'] as $item) : ?>
                    <a href="<?=$item['LINK']?>" class="animated fadeInUp"><?=$item['TEXT']?></a>
                <? endforeach; ?>
            </div>

            <? $APPLICATION->IncludeComponent(
                "citfact:elements.list",
                "soc.links",
                [
                    "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_FOOTER_SOC_LINKS),
                    "FILTER" => array(),
                    "PROPERTY_CODES" => array('LINK', 'ICON'),
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600000",
                    "IS_FOOTER" => false,
                ], ['HIDE_ICON' => 'Y']
            ); ?>
        </div>

        <div class="h-menu-d__img animated fadeInRightSmall">
            <img src="<?php echo $core::IMAGE_PLACEHOLDER_TRANSPARENT; ?>"
                 data-delayed="/local/client/img/h-menu.jpg"
                 title=""
                 alt="">
        </div>
    </div>
</div>