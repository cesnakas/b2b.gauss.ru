<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("TITLE", "Контакты");
$APPLICATION->SetTitle("Контакты");
?>

<div class="static-content">
    <div class="contacts">
        <div class="contacts__info">
            <div class="contacts__logo">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/local/include/areas/contacts/logo.php"
                    ),
                    false
                ); ?>
            </div>
            <div class="contacts__table">
                <div>
                    <div>Название:</div>
                    <div>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/contacts/company_name.php"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div>
                    <div>Адрес:</div>
                    <div>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/contacts/address.php"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div>
                    <div>Телефон:</div>
                    <div>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "phone",
                            array(
                                "COMPONENT_TEMPLATE" => "phone",
                                "CLASS" => "link",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/contacts/phone.php"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div>
                    <div>E-mail:</div>
                    <div>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "email",
                            array(
                                "COMPONENT_TEMPLATE" => "email",
                                "CLASS" => "link",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/contacts/email.php"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div>
                    <div>Сайт:</div>
                    <div>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "link",
                            array(
                                "COMPONENT_TEMPLATE" => "link",
                                "CLASS" => "link",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/local/include/areas/contacts/site.php"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
            </div>
            <a href="/local/include/modals/contact-to-company.php" class="btn btn--transparent" data-modal="ajax"
               title="Обратиться в компанию">Обратиться в компанию</a>
        </div>

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
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.76411378560552;s:10:\"yandex_lon\";d:37.82169840603873;s:12:\"yandex_scale\";i:9;s:10:\"PLACEMARKS\";a:2:{i:0;a:3:{s:3:\"LON\";d:37.416408058861;s:3:\"LAT\";d:55.712465852256;s:4:\"TEXT\";s:83:\"Офис###RN###г. Москва, ул. Дорогобужская, д.14, стр.6\";}i:1;a:3:{s:3:\"LON\";d:38.269740171739;s:3:\"LAT\";d:55.831410332971;s:4:\"TEXT\";s:135:\"Склад###RN###Московская обл., Ногинский район, п. Обухово, Кудиновское шоссе, 4\";}}}",
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
</div>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
