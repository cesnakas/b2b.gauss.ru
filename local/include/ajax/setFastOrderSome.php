<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Citfact\SiteCore\Core;

$core = Core::getInstance();

require_once ($_SERVER["DOCUMENT_ROOT"]."/local/includeClasses/citfact/basket/add2basket.php");
//header('Content-type: application/json');

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog")
    || !CModule::IncludeModule("iblock") ) {
    echo "failure";
    return;
}

global $APPLICATION;

$arRes = Array();
foreach ($_POST["products"]["productCode"] as $index => $item){
    $item = str_replace('_','', trim(htmlspecialcharsbx($item)));
    if (!empty($item) && !empty($_POST["products"]["quantity"][$index]) && IntVal($_POST["products"]["quantity"][$index])>0) {
        $fl_in = false;
        $db_props = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$core->getIblockId($core::IBLOCK_CODE_CATALOG),'PROPERTY_CML2_ARTICLE' => $item), false, false, array('ID'));
        while($ar_props = $db_props->Fetch()){
            $arRes[] = Array('id' => $ar_props["ID"],'article' => $item, 'quantity' => IntVal($_POST["products"]["quantity"][$index]));
            $fl_in = true;
            $id = $ar_props["ID"];
        }
        if (!$fl_in && strlen($item)>0)
            $arResErr["item_err"][] = Array('article' => $item, 'quantity' => $_POST["products"]["quantity"][$index]);

    }
    elseif(strlen($item)>0){
        $arResErr["item_err"][] = Array('article' => $item, 'quantity' => $_POST["products"]["quantity"][$index]);
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

<div id="modal-fast-order-some" class="b-modal">
    <div class="b-modal__close" data-modal-close="">
        <div class="plus plus--cross"></div>
    </div>

    <div class="title-1">
        <span>Быстрый заказ</span>
    </div>

    <div class="b-modal-f">
        <div class="b-form">
            <div class="styled-list">
                <?if(!empty($arResErr["item_err"][0])):?>
                    <span class="special-text">Артикулы (не найдены или выведены из ассортимента):</span>
                    <ul>
                        <?foreach($arResErr["item_err"] as $item):?>
                            <li><?=$item["article"]?></li>
                        <?endforeach;?>
                    </ul>
                <?endif?>
                <?if(!empty($arResErr["item_suc"][0])):?>
                    <span class="special-text">Артикулы (добавлены в корзину):</span>
                    <ul>
                        <?foreach($arResErr["item_suc"] as $item):?>
                            <li><?=$item["article"]?></li>
                        <?endforeach;?>
                    </ul>
                <?endif?>
            </div>
            <?if(!empty($arResErr["item_suc"][0])):?>
                <div class="b-modal-f__bottom">
                    <a href="/cart/" title="Перейти в корзину" class="btn btn--transparent">Перейти в корзину</a>
                </div>
            <?endif?>
        </div>
    </div>

</div>