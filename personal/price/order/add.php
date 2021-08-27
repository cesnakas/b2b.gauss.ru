<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")) {
	if (isset($_POST['MAP_TO_COUNT']) &&  !empty($_POST['MAP_TO_COUNT']) ) {
	    $data = json_decode($_POST['MAP_TO_COUNT'], true);
	    $products = [];
		foreach ($data as $id => $count) {
			$PRODUCT_ID = intval($id);
			$QUANTITY = intval($count);
            $products[] = [
                'PRODUCT_ID' => $PRODUCT_ID,
                'QUANTITY' => $QUANTITY,
            ];
        }

        $res = \Citfact\Sitecore\Order\Basket::addProducts($products, ['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom']);

        if (!$res->isSuccess()) {
            throw new \Exception(implode(' ,', $res->getErrorMessages()));
        }
		?>
        <script>
            BX.onCustomEvent('OnBasketChange');
            Am.modals.showDialog('/local/include/ajax/getBasketSuccess.php');
        </script>
		<?
	} else {
		echo "Заполните количество хотя бы у одного из товаров";
	}
} else {
	echo "Не подключены модули";
}
?>