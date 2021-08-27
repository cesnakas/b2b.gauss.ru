<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
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

$curPage = str_replace('/catalog/', '/products/', $APPLICATION->GetCurPage());

?>

<div class="c-nav">
    <? foreach ($arResult['SECTIONS'] as $arSection):
        $countSubsections = count($arSection['SECTIONS']);
        if (strpos($curPage, $arSection["SECTION_PAGE_URL"]) !== false)
            $isInCurSection = true;
        else
            $isInCurSection = false;
        ?>
        <div data-toggle-wrap>
            <a href="<?= $arSection["SECTION_PAGE_URL"]; ?>" <?= $isInCurSection && $arParams['CURRENT_SECTION_ID'] == $arSection['ID'] ? 'class="active"' : '' ?>>
                <span><?= $arSection["NAME"]; ?></span>
                <? if ($countSubsections > 0): ?>
                    <span data-toggle-btn <?= $isInCurSection ? 'class="active"' : '' ?>></span>
                <? endif; ?>
            </a>
            <? if ($countSubsections > 0): ?>
                <div data-toggle-list <?= $isInCurSection ? 'class="active"' : '' ?>>
                    <? foreach ($arSection['SECTIONS'] as $arSubsection):
                        $countSubsections3Lvl = count($arSubsection['SECTIONS']);
                        if (strpos($curPage, $arSubsection["SECTION_PAGE_URL"]) !== false)
                            $isInCurSubsection = true;
                        else
                            $isInCurSubsection = false;
                        ?>
                        <? if ($countSubsections3Lvl > 0): ?>
                        <div data-toggle-wrap>
                    <? endif ?>
                        <a href="<?= $arSubsection["SECTION_PAGE_URL"]; ?>" <?= $isInCurSubsection && $arParams['CURRENT_SECTION_ID'] == $arSubsection['ID'] ? 'class="active"' : '' ?>>
                            <?php if ($isInCurSubsection && empty($arSubsection['SECTIONS'])) { ?>
                                <b><?= $arSubsection["NAME"]; ?></b>
                            <?php } else { ?>
                                <span><?= $arSubsection["NAME"]; ?></span>
                            <?php } ?>
                            <? if ($countSubsections3Lvl > 0): ?>
                                <span data-toggle-btn <?= $isInCurSubsection ? 'class="active"' : '' ?>></span>
                            <? endif ?>
                        </a>
                        <? if ($countSubsections3Lvl > 0): ?>
                        <div data-toggle-list <?= $isInCurSubsection ? 'class="active"' : '' ?>>
                            <? foreach ($arSubsection['SECTIONS'] as $arSubsection3Lvl):

                                if (strpos($curPage, $arSubsection3Lvl["SECTION_PAGE_URL"]) !== false) {
                                    $isInSubsection3Lvl = true;
                                } else {
                                    $isInSubsection3Lvl = false;
                                }
                                ?>
                                <a href="<?= $arSubsection3Lvl["SECTION_PAGE_URL"]; ?>" <?= $isInCurSection && $arParams['CURRENT_SECTION_ID'] == $arSubsection3Lvl['ID'] ? 'class="active"' : '' ?>>
                                    <?php if ($isInSubsection3Lvl === true) { ?>
                                        <b><?= $arSubsection3Lvl["NAME"]; ?></b>
                                    <?php } else { ?>
                                        <?= $arSubsection3Lvl["NAME"]; ?>
                                    <?php } ?>
                                </a>
                            <? endforeach; ?>
                        </div>
                        </div>
                    <? endif ?>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
        </div>
    <? endforeach; ?>
</div>