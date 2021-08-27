<?
define("NEED_AUTH", true);

use Citfact\Sitecore\Order;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Информация о контрагенте");

$UserGroupID = \Citfact\DataCache\UserData\UserGroupID::getInstance();
if (CSite::InGroup(array($UserGroupID->getByCode('MANAGER'),$UserGroupID->getByCode('ASSISTANT'))) === true) {
    global $USER;
    $userId = $USER->GetID();
    $count = Order\OrderManager::getOrdersCount();

    $APPLICATION->IncludeComponent(
        "citfact:main.profile",
        ".default",
        [
            "PAGE" => 'main',
            "XML_ID" => $_REQUEST['XML_ID'], 
            "COUNT_ORDERS" => $count
        ],
        false
    );
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
