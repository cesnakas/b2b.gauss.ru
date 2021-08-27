<?

use Citfact\SiteCore\Definition\Mobile;
use Citfact\SiteCore\Core;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Доставка транспортной компанией");

global $USER;
$userId = $GLOBALS["USER"]->GetID();
?>
    <div class="static-content">
        <div class="title-1"><span>Доставка</span></div>
        <div class="b-tabs">
            <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/delivery/tabs-head.php"; ?>
            <div class="b-tabs__content">
                <div class="b-tabs__item active">
                    <p>
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.include", "",
                            [
                                "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
                                "PATH" => '/local/include/areas/delivery/company-delivery/text.php',    // Путь к файлу области
                            ],
                            false
                        ); ?>
                    </p>
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