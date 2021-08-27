<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/local/includeClasses/citfact/basket/add2basket.php");
//header('Content-type: application/json');

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog")
    || !CModule::IncludeModule("iblock") ) {
    echo "failure";
    return;
}

global $APPLICATION;

$arTxt = array();
$arTxt1 = array();
$arTxt0 = explode("\r\n",htmlspecialcharsbx($_POST["TXT"]));
foreach($arTxt0 as $id => $value) {
    $arTxt1[] = explode(";",$value);
}
foreach($arTxt1 as $id0 => $arItems) {
    foreach($arItems as $id => $value) {
        if (!empty($value)) {
            $arTxt[] = trim($value);
        }
    }
}

$arPost = Array();
foreach ($arTxt as $item){
    if (strpos($item, ',') !== false)
        $tmp = explode(",",$item);
    else
        $tmp = explode("\t",$item);
    //if (count($tmp) == 2) {
    if (count($tmp) > 0) {
        $arPost[] = $tmp;
    }
}

$arRes = Array();
foreach ($arPost as $item){
    if (!empty($item[0])) {

        if (empty($item[1]) || IntVal($item[1])<=0){
            $item[1] = 1;
        }

        $fl_in = false;
        $db_props = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>IBLOCK_ID_GAUSS_CATALOG,'PROPERTY_CML2_ARTICLE' => $item[0]), false, false, array('ID'));
        while($ar_props = $db_props->Fetch()){
            $arRes[] = Array('id' => $ar_props["ID"],'article' => $item[0], 'quantity' => IntVal($item[1]));
            $fl_in = true;
        }
        if (!$fl_in)
            $arResErr["item_err"][] = Array('article' => $item[0], 'quantity' => $item[1]);
    }
    else{
        $arResErr["item_err"][] = Array('article' => $item[0], 'quantity' => $item[1]);
    }
}

$arResult = Array();
foreach ($arRes as $item){
    $arResult[] = SaleBasketActions::addToBasket($item["quantity"],$item["id"],$item["article"]);
}
foreach ($arResult as $item){
    if ($item["STATUS"] == "ERROR")
        $arResErr["item_err"][] = Array('article' => $item["ARTICLE"], 'quantity' => $item["QUANTITY"]);
    elseif($item["STATUS"] == "OK")
        $arResErr["item_suc"][] = Array('article' => $item["ARTICLE"], 'quantity' => $item["QUANTITY"],'ID' => $item["ID"]);
}
if(empty($arResErr["item_err"]) || !isset($arResErr["item_err"]))
    $arResErr["success"] = "success";
?>
<div id="modal-fast-order-some" class="modal modal--form" style="max-width: 600px;">
    <div class="modal__wrap styled-text">
        <div class="modal__head">
            <button class="modal__close" data-modal-close></button>
            <div class="modal__head-title">
                Быстрый заказ
            </div>
        </div>

        <div class="modal__body">
            <div class="fastorder_result" >
                <?if(!empty($arResErr["item_err"][0])):?>
                    <span class="special-text">Артикулы (не найдены или выведены из ассортимента):</span>
                    <ul class="list-wrap">
                    <?foreach($arResErr["item_err"] as $item):?>
                        <li><?=$item["article"]?></li>
                    <?endforeach;?>
                    </ul>
                <?endif?>
                <?if(!empty($arResErr["item_suc"][0])):?>
                    <span class="special-text">Артикулы (добавлены в корзину):</span>
                    <ul class="list-wrap">
                    <?foreach($arResErr["item_suc"] as $item):?>
                        <li><?=$item["article"]?></li>
                    <?endforeach;?>
                    </ul>
                <?endif?>
            </div>
        </div>

    </div>
</div>