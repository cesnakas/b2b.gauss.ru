<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var array $arParams */
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
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
?>
<? if ($arResult['SECTIONS']): ?>

    <div class="price-lists">
        <? foreach ($arResult['SECTIONS'] as $sectionId => $section): ?>
            <div class="price-lists__section">
                <div class="price-lists__head"><?= $section['NAME'] ?></div>
                <div class="price-lists__groups">
                    <? foreach ($section['SECTIONS'] as $secondLvlSectionId => $secondLvlSection): ?>
                        <div class="price-lists-group">
                            <div class="price-lists-group__head" data-collapse-btn>
                                <?= $secondLvlSection['NAME'] ?>
                            </div>
                            <div class="price-lists-group__items" data-collapse-body>
                                <table class="styled-table">
                                    <? if (!$secondLvlSection['SECTIONS']): ?>
                                        <tr>
                                            <td><?= GetMessage('COMMON_PRICE_LIST') ?></td>
                                            <td class="styled-table__buttons">
                                                <a href="<?= $dir ?>?PRICE_LIST_SECTION_ID=<?= $secondLvlSectionId ?>"
                                                   class="styled-table__button"
                                                   target="_blank"
                                                   rel="nofollow">
                                                    <svg class="b-tabs__icon i-icon">
                                                        <use xlink:href="#icon-prosmotr"></use>
                                                    </svg>
                                                </a>
                                                <a href="<?= $dir ?>?PRICE_LIST_SECTION_ID=<?= $secondLvlSectionId ?>&DOWNLOAD=Y"
                                                   class="styled-table__button"
                                                   download=""
                                                   rel="nofollow">
                                                    <svg class="b-tabs__icon i-icon">
                                                        <use xlink:href="#icon-skachat"></use>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    <? else: ?>
                                        <? foreach ($secondLvlSection['SECTIONS'] as $thirdLvlSectionId => $thirdLvlSection): ?>
                                            <tr>
                                                <td><?= $thirdLvlSection['NAME'] ?></td>
                                                <td class="styled-table__buttons">
                                                    <a href="<?= $dir ?>?PRICE_LIST_SECTION_ID=<?= $thirdLvlSectionId ?>"
                                                       class="styled-table__button"
                                                       target="_blank"
                                                       rel="nofollow">
                                                        <svg class="b-tabs__icon i-icon">
                                                            <use xlink:href="#icon-prosmotr"></use>
                                                        </svg>
                                                    </a>
                                                    <a href="<?= $dir ?>?PRICE_LIST_SECTION_ID=<?= $thirdLvlSectionId ?>&DOWNLOAD=Y"
                                                       class="styled-table__button"
                                                       download=""
                                                       rel="nofollow">
                                                        <svg class="b-tabs__icon i-icon">
                                                            <use xlink:href="#icon-skachat"></use>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        <? endforeach; ?>
                                    <? endif ?>
                                </table>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        <? endforeach; ?>
    </div>
<? endif ?>
