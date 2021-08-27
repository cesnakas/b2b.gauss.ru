<?php
include($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Bitrix\Highloadblock;
use Citfact\SiteCore\Core;
use Bitrix\Highloadblock\HighloadBlockTable;


Loader::includeModule('highloadblock');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$keys = $request->getPost('id');
$checked = $request->getPost('checked');
$isChecked = false;
if ($checked == 'true') {
    $isChecked = true;
}

global $USER;
$currentUserId = $USER->GetID();
$core = Core::getInstance();

$hlId = $core->getHlBlockId($core::HL_BLOCK_CODE_LIST_WAIT);
$hlblock = HighloadBlockTable::getById($hlId)->fetch();
$entity = HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    'filter' => [
        'UF_USER_ID' => $currentUserId,
        'UF_PRODUCT_ID' => $keys
    ],
));

$isOk = true;
while ($items = $rsData->Fetch()) {
    $res = $entity_data_class::update($items['ID'], ['UF_EMAIL_PERMISSION' => $isChecked]);
    if (!$res->isSuccess()) {
        $isOk = false;
    }
}
if ($isOk == true) {
    echo 'ok';
} else {
    echo 'error';
}



