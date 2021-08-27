<?php

use Bitrix\Main\Application;
use Bitrix\Sale\Order;

if ($_REQUEST['SITE_ID']) {
    define('SITE_ID', $_REQUEST['SITE_ID']);
}

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//ajax
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    \Bitrix\Iblock\Component\Tools::process404(
        '404 Not Found'
        ,true
        ,"Y"
        ,"Y"
        , ""
    );
}

CJSCore::Init('currency');
$APPLICATION->ShowHeadStrings();
$APPLICATION->ShowHeadScripts();

$orderId = Application::getInstance()->getContext()->getRequest()->getQuery('order-id');

if (!$orderId) {
    echo null;
}

$basket = Order::load($orderId)->getBasket();

$basketItems = $basket->getBasketItems();

$productIds = [];

foreach ($basketItems as $basketItem) {
    $productIds[$basketItem->getProductId()] = $basketItem->getField('NAME');
}

$core = \Citfact\SiteCore\Core::getInstance();

$iblockElements = [];

$iblockElementsDb = CIBlockElement::GetList(['SORT' => 'ASC'],
    ['IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ID' => array_keys($productIds)],
    false,
    false,
    ['IBLOCK_ID', 'ID', 'NAME']);
while ($iblockElement = $iblockElementsDb->GetNext()) {
    $iblockElements[$iblockElement['ID']] = $iblockElement['ID'];
}

$ghostProducts = array_diff_key($productIds, $iblockElements);

if (empty($ghostProducts)) {
    LocalRedirect('/personal/orders/index.php?COPY_ORDER=Y&ID=' . $orderId, true, '301 Moved Permanently');
}

?>
<div class="b-modal b-modal--basket">
    <?

    ?>
    <div class="plus plus--cross b-modal__close" data-modal-close></div>
    <div class="title-1">
        <span>
        <?php
        if (empty($iblockElements)) {
            echo 'Нет возможности повторить заказ, т.к. все товары данного заказа отсутствуют в каталоге';
        } elseif (!empty($ghostProducts)) {
            echo 'Следующие товары не будут добавлены в корзину, т.к. отсутствуют в каталоге:';
        } ?>
        </span>
    </div>

        <div class="b-modal__content">

            <div class="basket basket--modal">

                <div class="basket__inner">
                    <?php if (!empty($iblockElements)) { ?>
                        <div class="basket-items-list-container">

                            <div class="basket-items-list-overlay" style="display: none;"></div>

                            <div class="basket-items-list">

                                <div class="basket__items">

                                    <?php foreach ($ghostProducts as $ghostProduct) { ?>
                                    <div class="basket-item">
                                        <div class="basket-items-list-item-notification">

                                            <div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="basket-item-height-aligner-{{ID}}">

                                                <div class="basket-items-list-item-removed-container">
                                                    <div>
                                                        <strong><?php echo $ghostProduct; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>

                            </div>
                        </div>
                        <div class="basket__bottom">

                            <div class="basket__filter b-form">
                                <a href="/personal/orders/index.php?COPY_ORDER=Y&ID=<?php echo $orderId; ?>" class="btn btn--grey" data-modal-close>Повторить заказ</a>
                            </div>

                        </div>
                    <? } elseif (empty($iblockElements)) { ?>
                        <div class="basket__bottom">

                            <div class="basket__filter b-form">
                                <a href="/catalog/" class="btn btn--grey" data-modal-close>Перейти в каталог</a>
                            </div>

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
