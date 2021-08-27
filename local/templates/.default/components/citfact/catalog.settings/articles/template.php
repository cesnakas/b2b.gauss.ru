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
?>
<div class="select select--small b-sort__select">
    <? if (!empty($arResult['SORT'])): ?>
        <input type="hidden" name="order" value="asc">
        <select class="select__inner" name="sort" id="" data-sort-select>
            <? foreach ($arResult['SORT'] as $item): ?>
                <? if ($item['HIDE']) {
                    continue;
                } ?>
                <option value="<?= $item['KEY'] ?>" <? if ($item['ACTIVE'] == 'Y') echo 'selected' ?> data-url="<?= $item['URL'] ?>">
                    <?= $item['NAME'] ?>
                </option>
            <? endforeach; ?>
        </select>
        <div class="select__arrow"></div>
    <? endif ?>
</div>