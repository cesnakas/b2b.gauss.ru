<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
$this->setFrameMode(true); ?>
<div class="static-content static-content--mb">
    <?
    $APPLICATION->IncludeComponent("bitrix:main.include", "",
        [
            "AREA_FILE_SHOW" => "file",    // Показывать включаемую область
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",    // Шаблон области по умолчанию
            "PATH" => '/local/include/areas/technical-documentation/text.php',    // Путь к файлу области
        ],
        false
    ); ?>
</div>
<?
$i = 0;
foreach ($arResult['ITEMS'] as $section) {
    if (!$section['NAME']) { // пропускаем товары без раздела
        continue;
    }
    if (empty($section['SUBSECTIONS']) && empty($section['PRODUCTS'])) {
        continue;
    }
    ?>
    <div class="s-toggle<?= $i == 0 ? ' active' : '' ?>" data-toggle-wrap>
        <div class="s-toggle__title s-toggle-arrow<?= $i == 0 ? ' active' : '' ?>" data-toggle-btn>
            <span><?= $section['NAME'] ? $section['NAME'] : 'Товары без раздела' ?></span>
        </div>
        <div class="s-toggle__list"<?= $i == 0 ? ' style="display: block;"' : '' ?> data-toggle-list>
            <? foreach ($section['SUBSECTIONS'] as $subSection) { ?>
                <? if (empty($subSection['SUBSECTIONS']) && empty($subSection['PRODUCTS'])) {
                    continue;
                } ?>
                <div class="s-toggle s-toggle--in" data-toggle-wrap> <? /* 2 лвл заголовок */ ?>

                    <div class="s-toggle__title s-toggle-arrow" data-toggle-btn>
                        <span><?= $subSection['NAME'] ?></span>
                    </div>

                    <div class="s-toggle__list t-doc-wrap" data-toggle-list> <? /* 2 лвл список */ ?>
                        <? foreach ($subSection['SUBSECTIONS'] as $subSubSection) { ?>
                            <? if (empty($subSubSection['SUBSECTIONS']) && empty($subSubSection['PRODUCTS'])) {
                                continue;
                            } ?>
                            <div class="s-toggle s-toggle--in t-doc" data-toggle-wrap>
                                <div class="s-toggle__title s-toggle-arrow" data-toggle-btn> <? /* 3 лвл заголовок */ ?>
                                    <span><?= $subSubSection['NAME'] ?></span>
                                </div>
                                <div class="s-toggle__list" data-toggle-list> <? /* 3 лвл список */ ?>
                                    <? foreach ($subSubSection['SUBSECTIONS'] as $subSubSection2) { ?>
                                        <? if (empty($subSubSection2['PRODUCTS'])) {
                                            continue;
                                        } ?>
                                        <div class="s-toggle s-toggle--in t-doc" data-toggle-wrap>
                                            <div class="s-toggle__title s-toggle-arrow" data-toggle-btn> <? /* 4 лвл заголовок */ ?>
                                                <span><?= $subSubSection2['NAME'] ?></span>
                                            </div>
                                            <div class="s-toggle__list" data-toggle-list> <? /* 4 лвл список */ ?>
                                                <? foreach ($subSubSection2['PRODUCTS'] as $prod) { ?>
                                                    <a href="/local/include/modals/tech_doc.php?XML_ID=<?= $prod['XML_ID']; ?>"
                                                       data-modal="ajax" class="t-doc__item link-download">
                                                        <svg class="i-icon">
                                                            <use xlink:href="#icon-t-doc"/>
                                                        </svg>
                                                        <span><?= $prod['NAME'] ?></span>
                                                    </a>
                                                <? } ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? foreach ($subSubSection['PRODUCTS'] as $prod) { ?>
                                        <a href="/local/include/modals/tech_doc.php?XML_ID=<?= $prod['XML_ID']; ?>"
                                           data-modal="ajax" class="t-doc__item link-download">
                                            <svg class="i-icon">
                                                <use xlink:href="#icon-t-doc"/>
                                            </svg>
                                            <span><?= $prod['NAME'] ?></span>
                                        </a>
                                    <? } ?>
                                </div>
                            </div>
                        <? } ?>
                        <? foreach ($subSection['PRODUCTS'] as $prod) { ?>
                            <a href="/local/include/modals/tech_doc.php?XML_ID=<?= $prod['XML_ID']; ?>"
                               data-modal="ajax" class="t-doc__item link-download">
                                <svg class="i-icon">
                                    <use xlink:href="#icon-t-doc"/>
                                </svg>
                                <span><?= $prod['NAME'] ?></span>
                            </a>
                        <? } ?>
                    </div>

                </div>
            <? } ?>
            <? foreach ($section['PRODUCTS'] as $prod) { ?>
                <div class="s-toggle s-toggle--in" data-toggle-wrap> <? // 2 лвл заголовок  ?>
                    <div class="s-toggle s-toggle--in t-doc" data-toggle-wrap>
                        <a href="/local/include/modals/tech_doc.php?XML_ID=<?= $prod['XML_ID']; ?>" class="t-doc__item link-download"
                           data-modal="ajax">
                            <svg class="i-icon">
                                <use xlink:href="#icon-t-doc"/>
                            </svg>
                            <span><?= $prod['NAME'] ?></span>
                        </a>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
    <? $i++;
} ?>
