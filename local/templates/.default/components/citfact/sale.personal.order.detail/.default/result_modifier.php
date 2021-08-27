<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Core;
use Bitrix\Sale\Order;
use Citfact\Sitecore\Order\OrderRepository;
use Citfact\Sitecore\UserDataManager;
use Citfact\Sitecore\Order\OrderManager;

$core = Core::getInstance();
$component = $this->getComponent();

// Получение всех свойств заказа
$order = Order::load($arResult['ID']);
$propertyCollection = $order->getPropertyCollection();
$arPropertiesCollection = $propertyCollection->getArray();
$idDelivery = 0;
foreach ($arPropertiesCollection['properties'] as $key => &$item) {
    $item['VALUE'] = (is_array($item['VALUE'])) ? implode(', ', $item['VALUE']) : $item['VALUE'];
    if($item['VALUE'] === 'N'){
        $item['VALUE'] = 'Нет';
    } elseif($item['VALUE'] === 'Y'){
        $item['VALUE'] = 'Да';
    }

    if ($item['CODE'] == 'DELIVERY_ID') {
        $idDelivery = $item['VALUE'];
    }
    if ($item['CODE'] == 'CONTRAGENT') {
        $contragentXml = $item['VALUE'];

        // получение юр.лица
        $userContragent = UserDataManager\UserDataManager::getContrAgentInfo($contragentXml);
        $arResult['USER']['CONTRAGENT'] = $userContragent;

        // получение менеджера
        $userManager = \Citfact\SiteCore\User\UserManagers::getManagerByContragent($contragentXml);
        $arResult['USER']['MANAGER'] = $userManager;
    }
    if ($item['CODE'] == 'OFFLINE') {
        $arResult['OFFLINE'] = $item['VALUE'];
    }
}
unset($item);
unset($key);

foreach ($arPropertiesCollection['properties'] as $key => &$item) {
    if (!OrderRepository::isShowPropertyByDelivery($idDelivery, $item['CODE']) || empty($item['VALUE'])) {
        unset($arPropertiesCollection['properties'][$key]);
    }
}

$core = Core::getInstance();

// заменяем св-ва
$arResult['ORDER_PROPS'] = $arPropertiesCollection['properties'];

// получение xml_id и статусов товара

CModule::IncludeModule('highloadblock');
$HLinfo = [];
$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$core->getHlBlockId($core::HLBLOCK_CODE_STATUSY_TOVAROV))));
if ($hldata = $rsData->fetch()){
    $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $res = $hlDataClass::getList(array(
            'filter' => array(
                'UF_ZAKAZID' => $arResult['ID_1C']
            ),
            'select' => array("*"),
            'order' => array(
                'ID' => 'asc'
            ),
        )
    );
    while ($row = $res->fetch()) {

        $HLinfo[$row['UF_NOMENKLATURAID']][] = $row;

        // подсчет кол-ва товара со статусом
        $countItems[$row['UF_NOMENKLATURAID']] += $row['UF_KOLICHESTVO'];
    }
}
// формируем массив статусов товаров
$arResult['BASKET_STATUS'] = $HLinfo;

// получение отгрузок заказа
$arResult['SHIPMENT'] = [];
$arAddressesIds = [];
$arGruzopoluchatelIds = [];
$arOtgruzkiIds = [];
$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$core->getHlBlockId($core::HLBLOCK_CODE_OTGRUZKI))));
if ($hldata = $rsData->fetch()){
    $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    $hlDataClass = $hldata['NAME'].'Table';
    $res = $hlDataClass::getList(array(
            'filter' => array(
                'UF_ZAKAZID' => $arResult['ID_1C']
            ),
            'select' => array("*"),
            'order' => array(
                'ID' => 'asc'
            ),
        )
    );
    // формируем массив отгрузок
    $shipmentSumm = 0;
    $counter = 0;
    while ($row = $res->fetch()) {
        $arResult['SHIPMENT'][$row['UF_ID']][] = $row;
        $arAddressesIds[$row['UF_ADRESDOSTAVKI']] = $row['UF_ADRESDOSTAVKI'];
        $arGruzopoluchatelIds[$row['UF_GRUZOPOLUCHATEL']] = $row['UF_GRUZOPOLUCHATEL'];
        $arOtgruzkiIds[] = $row['UF_ID'];
        $shipmentSumm += $row['UF_SUMMA'];
        $counter++;
    }
    $arResult['SUMM_WITH_SHIPMENT'] = $shipmentSumm;
    $arResult['FORMATED_SUMM_SHIPMENT'] = SaleFormatCurrency($arResult['SUMM_WITH_SHIPMENT'], $arResult["CURRENCY"]);
    $arResult['QUANTITY_GOODS_WITH_SHIPMENT'] = $counter;
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
                'UF_XML_ID' => $arGruzopoluchatelIds
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
$arResult['ORDER_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $arResult['ID']]);
if(empty($arResult['ORDER_FILES'])){
    $arResult['ORDER_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $arResult['ID_1C']]);
}

/**
 * файлы отгрузки
 */
if (!empty($arOtgruzkiIds)) {
    $arResult['OTGRUZKA_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $arOtgruzkiIds]);
}


/**
 * для товаров доставим доп поля
 */
$arProductsIds = [];
foreach ($arResult['BASKET'] as $key => $item) {
    $arProductsIds[] = $item['PRODUCT_ID'];
}

$resItems = \CIBlockElement::GetList([], [
    'ID' => $arProductsIds, 'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG)
], false, false, ['IBLOCK_ID', 'XML_ID', 'ID', 'PROPERTY_CML2_ARTICLE', 'DETAIL_PAGE_URL']);
while ($arItem = $resItems->GetNext()) {
    $arResult['CATALOG_ITEMS'][$arItem['XML_ID']] = $arItem;
}


// переименовываем ключи массива товаров для более удобного обращения в шаблоне, записываем статус по товару
// записываем оставшиеся (REMAINDER) товары (без статуса)
foreach ($arResult['BASKET'] as $key => &$item){

    $basket[$item['PRODUCT_XML_ID']] = $item;
    $basket[$item['PRODUCT_XML_ID']]['STATUS'] = $arResult['BASKET_STATUS'][$item['PRODUCT_XML_ID']]['UF_STATUS'];

    if ($item['QUANTITY'] > $countItems[$item['PRODUCT_XML_ID']]){

        $count = $item['QUANTITY'] - $countItems[$item['PRODUCT_XML_ID']];
        $arResult['BASKET_STATUS'][$item['PRODUCT_XML_ID']]['REMAINDER']['UF_NOMENKLATURAID'] = $item['PRODUCT_XML_ID'];
        $arResult['BASKET_STATUS'][$item['PRODUCT_XML_ID']]['REMAINDER']['UF_KOLICHESTVO'] = $count;
        $arResult['BASKET_STATUS'][$item['PRODUCT_XML_ID']]['REMAINDER']['UF_STATUS'] = 'В обработке';
        $arResult['BASKET_STATUS'][$item['PRODUCT_XML_ID']]['REMAINDER']['PRODUCT_ID'] = $item['PRODUCT_ID'];
    }
    unset($arResult['BASKET'][$key]);
}
$arResult['BASKET'] = $basket;


// формируем стоимость по каждому статусу товара и класс по статусу
foreach ($arResult['BASKET_STATUS'] as &$basketStatus) {

    foreach ($basketStatus as &$basketItem) {

        $basketItem['FORMATED_SUM'] = SaleFormatCurrency($arResult['BASKET'][$basketItem['UF_NOMENKLATURAID']]['PRICE'] * $basketItem['UF_KOLICHESTVO'], $arResult["CURRENCY"]);

        $basketItem['UF_STATUS_CLASS'] = ($basketItem['UF_STATUS'] === 'Отгружен') ? 'gray' :
            (($basketItem['UF_STATUS'] === 'Готов к отгрузке') ? 'green' :
                (($basketItem['UF_STATUS'] === 'Срок поставки') ? 'yellow' :
                    (($basketItem['UF_STATUS'] === 'В обработке') ? 'red' : 'red')));
    }
}

$orderManager = new OrderManager();
$number1C = $orderManager->get1CNumber($arResult['ID']);
if(!empty($number1C)){
    $arResult['1C_NUMBER'] = $number1C;
}

$this->__component->SetResultCacheKeys(array('1C_NUMBER', 'DATE_INSERT','ID'));