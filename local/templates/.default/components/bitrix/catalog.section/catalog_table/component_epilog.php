<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Sitecore\CatalogHelper\ListWaitHelper;

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

// есть ли GET параметры? Если есть, установить canonical на url без параметров
$urlHaveParams = false;
if (strpos($_SERVER["REQUEST_URI"], "?") !== false) {
    $urlHaveParams = true;
}

if ($urlHaveParams) {
    $APPLICATION->AddViewContent('canonical', '<link rel="canonical" href="http://' . SITE_SERVER_NAME . $APPLICATION->GetCurDir() . '">');
}

if (!empty($arResult['ELEMENT_PAGE_AMOUNT'])) {
    $APPLICATION->AddViewContent('amount', 'Товаров на странице:&nbsp;&nbsp;<span>' . $arResult['ELEMENT_PAGE_AMOUNT'] . '</span>');
}

$frame = new \Bitrix\Main\Page\FrameBuffered("wait-list-block");
$frame->begin();

$listWaitHelper = new ListWaitHelper();
$itemIds = array_column($arResult['ITEMS'], 'ID');
$productInListWait = $listWaitHelper->checkProductinListWait($itemIds);
?>
<div style="display: none" class="button-container">
    <? foreach ($arResult['ITEMS'] as $item) { ?>
        <div class="item-hidden" data-id="<?= $item['ID'] ?>">
            <? if (!in_array($item['ID'], $productInListWait)) { ?>
                <button type="button"
                        class="btn btn-link btn--transparent btn--big btn--loading tooltip__handle btn-wait"
                        data-itemId="<?= $item["ID"] ?>"><span>В лист ожидания</span><span>В листе ожидания</span>
                </button>
            <? } else { ?>
                <div class="btn btn-link btn--transparent btn--big btn--loading tooltip__handle btn-wait no-hover"
                     data-itemId="<?= $item['ID'] ?>"> В листе ожидания
                </div>
            <? } ?>
        </div>
    <? } ?>
</div>

<script>
    function initBtnWaitList() {
        $('.button-container .item-hidden').each(function (i, v) {
            let id = $(v).attr('data-id');
            let container = $('[data-item-container=' + id + '] .wait-list-block');
            container.html($(v).html());
        })
    }

    <? if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { ?>
        initBtnWaitList();

    <?} else {?>
        if (window.frameCacheVars !== undefined)
        {
            BX.addCustomEvent("onFrameDataReceived" , function(json) {
                initBtnWaitList();
            });
        } else {
            document.addEventListener('App.Ready', function (e) {
                initBtnWaitList();
            });
        }
    <?}?>
</script>

<?php
$frame->beginStub();

$frame->end();