<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult['RECEIVABLES'] as &$order) {
    if($order['UF_NOMER'] !== 'Нет данных' && !empty($order['UF_NOMER'])) {
        $order['ORDER_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $order['UF_NOMER']]);
        if(empty($order['ORDER_FILES'])){
            $order['ORDER_FILES'] = \Citfact\Sitecore\Order\OrderFiles::getListFilter(['UF_ID' => $order['UF_ZAKAZ']]);
        }
    }
}
unset($order);
