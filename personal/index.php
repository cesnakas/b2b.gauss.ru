<?
define("NEED_AUTH", true);

use Citfact\Sitecore\Order;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

$UserGroupID = \Citfact\DataCache\UserData\UserGroupID::getInstance();
if (CSite::InGroup(array($UserGroupID->getByCode('MANAGER'),$UserGroupID->getByCode('ASSISTANT'))) === false) {
    global $USER;
    $userId = $USER->GetID();
    $count = Order\OrderManager::getOrdersCount();

    $APPLICATION->IncludeComponent(
        "citfact:main.profile",
        ".default",
        [
            "PAGE" => 'main',
            "USER_ID" => $userId,
            "COUNT_ORDERS" => $count
        ],
        false
    );
} else {
    $APPLICATION->IncludeComponent(
        "citfact:manager_v2",
        ".default",
        [
            "SORT" => $_REQUEST["sort"],
            "DIR" => $_REQUEST["dir"],
            "PERIOD" => $_REQUEST["period"],
            "FOLDER" => '/personal/detail/',
        ],
        false
    );
}


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
