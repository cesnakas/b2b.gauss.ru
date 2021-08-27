<?php
include($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader; 
use Bitrix\Highloadblock;
Loader::includeModule('highloadblock'); 

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$product = $request->getPost('product');
$count = $request->getPost('count');
$action = $request->getPost('action');

if (($product && $count) || ($action == 'delete')) {
    $hlblock = Highloadblock\HighloadBlockTable::getList([
        'filter' => ['=NAME' => 'ListWait']
    ])->fetch();
    if ($hlblock) {
        global $USER;
        $hlClassName = (Highloadblock\HighloadBlockTable::compileEntity($hlblock))->getDataClass();

        $rsData = $hlClassName::getList([
            'select' => ['UF_COUNT', 'ID'],
            'filter' => ['UF_USER_ID' => $USER->GetID(), 'UF_PRODUCT_ID' => $product]
        ]);
         
        if ($arData = $rsData->Fetch()) {
            switch ($action) {
                case 'edit':
                    $data = [
                        'UF_COUNT' => $count,
                        'UF_DATE_ADD' => date('d.m.Y')
                    ];
                    $hlClassName::update($arData['ID'], $data);
                    break;
                case 'delete':
                    $hlClassName::delete($arData['ID']);
                    break;
                default:
                    $data = [
                        'UF_COUNT' => $count + $arData['UF_COUNT'],
                        'UF_DATE_ADD' => date('d.m.Y')
                    ];
                    $hlClassName::update($arData['ID'], $data);
                    break;
            }
        } else {
            $data = [
                'UF_COUNT' => $count,
                'UF_USER_ID' => $USER->GetID(),
                'UF_PRODUCT_ID' => $product,
                'UF_DATE_ADD' => date('d.m.Y'),
                'UF_VIEWED' => true,
                'UF_EMAIL_PERMISSION' => true,
            ];
            $hlClassName::add($data);
        }
    } else {
        echo "HL блок 'Лист ожидания' не найден";
    }
} else {
    echo "Ошибка: не все данные найдены";
}
