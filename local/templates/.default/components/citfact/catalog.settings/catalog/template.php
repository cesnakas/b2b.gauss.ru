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
$initMaxItemsCount = $arResult['COUNT'][0]['VALUE'];
if ($arResult['ITEMS_COUNT'] < $initMaxItemsCount)
    $itemsCount = $arResult['ITEMS_COUNT'];
else $itemsCount = $initMaxItemsCount;
?>
<div class="b-sort__bottom">
    <?if($arParams['IS_AUTH']=='Y'):?>
        <div class="b-sort__company">
            <? $APPLICATION->IncludeComponent(
                "citfact:contragent.list",
                ".default",
                Array()
            ); ?>
        </div>
    <? endif ?>

    <div class="b-sort__text">
        Только в наличии
    </div>
    <label for="filter" class="b-checkbox__label" style="margin-left: 5px">
        <input type="checkbox" class="b-checkbox__input" id="filter"  <?if($_GET['only_available'] == 'show'){echo 'checked';}?>>
        <span class="b-checkbox__box">
                     <span class="b-checkbox__line b-checkbox__line--short"></span>
                     <span class="b-checkbox__line b-checkbox__line--long"></span>
        </span>
    </label>
    <? $data_item = $_GET['data_item']; ?>
    <? $curPage = ($APPLICATION->GetCurPage(false)) ?>
    <? CJSCore::Init(array("jquery"));?>
    <script>

        $( document ).ready(function() {
            $('#filter').on('change', function () {
                var filter;
                if ($('#filter').is(':checked')) {
                    filter = 'show';
                } else {
                    filter = 'dont_show';
                }
                location = "<?=$curPage?>" +"?only_available="+filter;
            });
        })
    </script>

    <div class="b-sort__text">Сортировать:</div>

    <div class="b-sort__sort">
        <select class="select--white" data-select-catalog-sort>
            <? foreach ($arResult['SORT'] as $key => $arSort): ?>
                <option value="<?= $arSort['KEY'] ?>"
                    <?= ($arSort['ACTIVE'] == 'Y' ? 'selected' : '') ?>
                        data-url="<?= $arSort['URL'] ?>"
                ><?= $arSort['NAME'] ?></option>
            <? endforeach; ?>
        </select>
    </div>

    <div class="b-sort__filter btn btn--transparent btn--big" data-filter-btn>
        Фильтр
    </div>

    <? foreach ($arResult['VIEW'] as $key => $arView):?>
        <a href="<?= $arView['URL'] ?>" class="b-sort__display <?= $arView['ACTIVE'] == 'Y' ? 'active' : '' ?>" title="<?= $arView['TITLE']?>">
            <svg class='i-icon'><use xlink:href='#icon-display-<?= $arView['CLASS'] ?>'/></svg>
        </a>
    <? endforeach; ?>
</div>