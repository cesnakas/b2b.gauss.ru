<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;
use Citfact\Sitecore\Order\OrderManager;
$core = Core::getInstance();


$arUsersIds = [];
foreach ($arResult['ORDERS'] as $key => $order) {
    $arUsersIds[$order['ORDER']['USER_ID']] = $order['ORDER']['USER_ID'];
}

$arGroupsUsers = [];
$res = \Citfact\SiteCore\User\UserGroupTable::getList([
    'filter' => ['USER_ID' => $arUsersIds],
    'select' => ['USER_ID', 'GROUP_ID'],
]);
while ($arGroupRes = $res->fetch()) {
    $arGroupsUsers[$arGroupRes['USER_ID']][] = $arGroupRes['GROUP_ID'];
}

$arResult['USERS_MANAGER'] = [];
$arGroupManagers = $core->GetGroupByCode($core::USER_GROUP_MANAGER . '|' . $core::USER_GROUP_ASSISTANT);
foreach ($arGroupsUsers as $uid => $arGroup) {
    foreach ($arGroup as $groupId) {
        if (in_array($groupId, $arGroupManagers)) {
            $arResult['USERS_MANAGER'][] = $uid;
            break;
        }
    }
}

// сбор id заказов, убираем изначальные доставки
foreach ($arResult['ORDERS'] as $key => $order) {
    $ids[] = $order['ORDER']['ID'];
    unset($arResult['ORDERS'][$key]['SHIPMENT']);
}

// получение ID_1C заказов
$ordersIterator = \Bitrix\Sale\Internals\OrderTable::getList(array('select' => array('ID', 'ID_1C'), 'filter' => ['ID' => $ids]));
while ($order = $ordersIterator->fetch()) {
    if ($order['ID_1C']) {
        $ids_1c[$order['ID']] = $order['ID_1C'];
        $ids_1c_reverse[$order['ID_1C']] = $order['ID'];
    }
}

// получение отгрузок заказа
$arResult['SHIPMENT'] = [];
$arAddressesIds = [];
$arGruzopoluchatelIds = [];
$arOtgruzkiIds = [];
$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter' => array('ID' => $core->getHlBlockId($core::HLBLOCK_CODE_OTGRUZKI))));
if ($hldata = $rsData->fetch()) {
    $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'] . 'Table';
    $res = $hlDataClass::getList(array(
            'filter' => array(
                'UF_ZAKAZID' => $ids_1c
            ),
            'select' => array("*"),
            'order' => array(
                'ID' => 'asc'
            ),
        )
    );
    // формируем массив отгрузок
    while ($row = $res->fetch()) {
        $shipments[$row['UF_ID']][] = $row;
        $arAddressesIds[$row['UF_ADRESDOSTAVKI']] = $row['UF_ADRESDOSTAVKI'];
        $arGruzopoluchatelIds[$row['UF_GRUZOPOLUCHATEL']] = $row['UF_GRUZOPOLUCHATEL'];
        $arOtgruzkiIds[] = $row['UF_ID'];
    }
}

// записываем доставки в соответсвующие заказы
foreach ($shipments as $key => $shipment) {
    foreach ($shipment as $item) {
        $arResult['ORDERS'][$ids_1c_reverse[$item['UF_ZAKAZID']]]['SHIPMENT'][$key][] = $item;
    }
}

/** адреса доставок */
$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$core->getHlBlockId($core::HLBLOCK_CODE_SHIPPING_ADDRESSES))));
if ($hldata = $rsData->fetch()){
    $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $res = $hlDataClass::getList(array(
            'filter' => array(
                'UF_XML_ID' => $arAddressesIds
            ),
            'select' => array("*"),
            'order' => array(
                'ID' => 'asc'
            ),
        )
    );
    while ($row = $res->fetch()) {
        $arResult['ADDRESSES'][$row['UF_XML_ID']] = $row;
    }
}

/** Грузополучатель */
$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$core->getHlBlockId($core::HLBLOCK_CODE_KONTRAGENTY))));
if ($hldata = $rsData->fetch()){
    $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $res = $hlDataClass::getList(array(
            'filter' => array(
                'UF_XML_ID' => $ids
            ),
            'select' => array("*"),
        )
    );
    while ($row = $res->fetch()) {
        $arResult['GRUZOPOLUCHATEL'][$row['UF_XML_ID']] = $row;
    }
}

/**
 * файлы заказа
 */
$arResult['ORDER_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $ids]);

/**
 * файлы отгрузки
 */
if (!empty($arOtgruzkiIds)) {
    $arResult['OTGRUZKA_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $arOtgruzkiIds]);
}

/**
 * показываем модалку при клике на "Повторить заказ", если товары отсутствуют в каталоге
 */
$basketItemsProductIds = [];
$iblockElements = [];
$orderManager = new OrderManager();

foreach ($arResult['ORDERS'] as $order) {
    if (!empty($order['BASKET_ITEMS'])) {
        foreach ($order['BASKET_ITEMS'] as $basketItem) {
            $basketItemsProductIds[$basketItem['PRODUCT_ID']] = $basketItem['PRODUCT_ID'];
        }
    }
}

$iblockElementsDb = CIBlockElement::GetList(['SORT' => 'ASC'],
    ['IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG), 'ID' => array_keys($basketItemsProductIds)],
    ['IBLOCK_ID', 'ID', 'NAME']);
while ($iblockElement = $iblockElementsDb->Fetch()) {
    $iblockElements[$iblockElement['ID']] = $iblockElement['ID'];
}
$ghostProducts = array_diff_key($basketItemsProductIds, $iblockElements);
if (!empty($ghostProducts)) {
    foreach ($arResult['ORDERS'] as &$order) {
        if (!empty($order['BASKET_ITEMS'])) {
            foreach ($order['BASKET_ITEMS'] as $basketItem) {
                if (in_array($basketItem['PRODUCT_ID'], $ghostProducts)) {
                    $order['ORDER']['URL_TO_COPY'] = "javascript:Am.modals.showDialog('/local/include/modals/ghost_products.php?order-id=" . $order['ORDER']['ID'] . "');";
                    unset($order);
                    break 1;
                }
            }
        }
    }
    unset($order);
}

foreach ($arResult['ORDERS'] as &$order){
    $number1C = $orderManager->get1CNumber($order['ORDER']['ID']);
    if(!empty($number1C)){
        $order['ORDER']['1C_NUMBER'] = str_replace('1C-', '',$number1C);
    }
}