<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Citfact\SiteCore\Core;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle("Наши возможности");
$core = Core::getInstance();
?>
    <div class="static-content">
        <div class="ed">
            <div class="ed__main">
                <div class="b-tabs">
                    <? include $_SERVER['DOCUMENT_ROOT'] . "/local/include/areas/education/tabs-head.php"; ?>
                    <div class="b-tabs__content">
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.include", "",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => '/local/include/areas/education/academy_content.php', 
                            ],
                            false
                        ); ?>
                        <div>
                            <?
                            $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                [
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => '/local/include/areas/education/academy_content_text.php',
                                ],
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ed__aside">
                <a href="/local/include/modals/education.php" class="btn btn--transparent" title="Подать заявку на обучение" data-modal="ajax">
                    Подать заявку на обучение
                </a>
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

<?php
$day = (int) date('j');
?>


    <script>
        window.educationMonthOffset = JSON.parse(<?php echo $day <= 10 ? 1 : 2; ?>);
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>