<?
use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Самовывоз");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
    <div class="static-content">
        <div class="title-1"><span>Доставка</span></div>
        <div class="b-tabs">
            <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/delivery/tabs-head.php"; ?>
            <div class="b-tabs__content">
                <div class="b-tabs__item active">
                    <div class="contacts contacts--pickup">
                        <? $APPLICATION->IncludeComponent(
                            "citfact:elements.list",
                            "pickup",
                            array(
                                "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_DELIVERY_PICKUP),
                                "FILTER" => array(),
                                "FIELDS" => array(),
                                "PROPERTY_CODES" => array('ADDRESS', 'PHONE', 'EMAIL'),
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600000",
                            ),
                            false,
                            array('HIDE_ICON' => 'Y')
                        ); ?>
                        <div id="cMap" class="contacts__map">
                            <? $APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view", 
	".default", 
	array(
		"API_KEY" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(
			0 => "SMALLZOOM",
		),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.56604542404796;s:10:\"yandex_lon\";d:37.45396484948698;s:12:\"yandex_scale\";i:8;s:10:\"PLACEMARKS\";a:2:{i:0;a:3:{s:3:\"LON\";d:37.4166285;s:3:\"LAT\";d:55.712269069044;s:4:\"TEXT\";s:44:\"Дорогобужская улица, 14с6\";}i:1;a:3:{s:3:\"LON\";d:38.26969971276822;s:3:\"LAT\";d:55.831385070825206;s:4:\"TEXT\";s:29:\"посёлок Обухово\";}}}",
		"MAP_HEIGHT" => "400",
		"MAP_ID" => "",
		"MAP_WIDTH" => "AUTO",
		"OPTIONS" => array(
			0 => "ENABLE_SCROLL_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
); ?>
                        </div>
                    </div>
                    
                    <? $APPLICATION->IncludeComponent(
                        "citfact:elements.list",
                        "route",
                        array(
                            "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_DELIVERY_ROUTE),
                            "FILTER" => array(),
                            "FIELDS" => array('PREVIEW_TEXT'),
                            "PROPERTY_CODES" => array('ADDRESS', 'PHONE', 'EMAIL'),
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600000",
                        ),
                        false,
                        array('HIDE_ICON' => 'Y')
                    ); ?>
                </div>
            </div>
        </div>

        <? $APPLICATION->IncludeComponent(
            "citfact:elements.list",
            "payment",
            array(
                "IBLOCK_ID" => $core->getIblockId(Core::IBLOCK_CODE_DELIVERY_PAYMENT),
                "FILTER" => array(),
                "FIELDS" => array('PREVIEW_TEXT'),
                "PROPERTY_CODES" => array('ICON'),
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600000",
            ),
            false,
            array('HIDE_ICON' => 'Y')
        ); ?>

    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>