<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
use Citfact\SiteCore\CatalogHelper\BasketRepository;
$basket = new BasketRepository();

$request = Application::getInstance()->getContext()->getRequest();
$post_data = array(
    'clear_basket' => $request->getPost('clear_basket'),
    'sessid' => $request->getPost('sessid'),
    'saveTemplateOrder' => $request->getPost('saveTemplateOrder'),
    'dateNotify' => $request->getPost('dateNotify'),
);

if($request->isAjaxRequest()) {
    $APPLICATION->RestartBuffer();
    if ((bitrix_sessid() == $post_data["sessid"])) {
        if($post_data["clear_basket"] =='Y') {
            $basket->clearBasket();
            echo json_encode(array('status' => 'success'));
        }else if($post_data["saveTemplateOrder"] =='Y') {
            $date = array();
            if($post_data["dateNotify"]) {
                $date[] = $post_data["dateNotify"];
            }
            if($basket->saveOrderTemplate($date))
                echo json_encode(array('status' => 'success'));
            else
                echo json_encode(array('status' => 'error'));
        }
    }
    die;
}