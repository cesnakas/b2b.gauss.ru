<?

/* ********************
Данный агент выполняет рассылку при появлении на складе товара из листа ожидания
********************** */

use Citfact\SiteCore\Core;
use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock as HL;
use Citfact\Sitecore\CatalogHelper;

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/cron', '', __DIR__);

if ($_SERVER['HOSTNAME'] == 'testfact.ru') {
    $host = 'gaussb2b.testfact.ru';
} else {
    $host = 'b2b.gauss.ru';
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$core = Core::getInstance();
$iblockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

const EVENT_TYPE = 'GOODS_ARRIVAL_NOTIFICATION';

//Получаем hl блок со списками ожидания и записываем его в массив

$iblockId = $core->getIblockId($core::IBLOCK_CODE_CATALOG);

$waitUsers =[];
$core = Core::getInstance();
$hlId= $core->getHlBlockId($core::HL_BLOCK_CODE_LIST_WAIT);
$hlblock = HL\HighloadBlockTable::getById($hlId)->fetch();
if( Loader::IncludeModule('highloadblock')){
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC")
    )) ;
    while($arData = $rsData->Fetch()){
        $tmpEl = [
            'UF_IS_SENT'=> $arData['UF_IS_SENT'],
            'COUNT' => $arData['UF_COUNT'],
            'VIEWED' => $arData['UF_VIEWED'],
            'DATE_ADD' => $arData['UF_DATE_ADD'],
            'UF_EMAIL_PERMISSION' =>$arData['UF_EMAIL_PERMISSION']
        ];
        $waitUsersGoods[$arData['UF_USER_ID']]['ITEMS'][$arData['UF_PRODUCT_ID']] = $tmpEl;
        $goodsID[] = $arData['UF_PRODUCT_ID'];
        $userID[] = $arData['UF_USER_ID'];
        $userToGoodIDs[$arData['UF_USER_ID']][] = $arData['UF_PRODUCT_ID'];
    }
}
//получаем данные по пользователю и записываем их в массив
$userString = implode('|', $userID);
$params['FIELDS'] = ['ID', 'LOGIN', 'EMAIL', 'NAME', 'LAST_NAME', 'SECOND_NAME'];
$rsUsers = CUser::GetList(
    $by="",
    $order="asc",
    ['ID' => $userString],
    $params
); // выбираем пользователей

while($users = $rsUsers->GetNext()){
    $tmpData = [
        'ID'=> $users['ID'],
        'LOGIN' => $users['LOGIN'],
        'USERNAME' => ($users['NAME'] == '')? 'Уважаемый пользователь' : $users['NAME']. " " . $users['SECOND_NAME'],
        'EMAIL' =>  $users['EMAIL'],
        'ITEMS' => $waitUsersGoods[$users['ID']]['ITEMS']
    ];
    $waitUsersGoods[$users['ID']] = $tmpData;
}

//получаем нужные для вывода в письме данные по товарам и записываем их в массив
$res = CIBlockElement::GetList(
    false,
    ['IBLOCK_ID'=>$iblockId,'ID'=> $goodsID],
    false,
    false,
    ['PREVIEW_PICTURE', 'ID', 'CODE', 'NAME', 'CATALOG_QUANTITY', 'DETAIL_PAGE_URL']
);
$priceObject = new CatalogHelper\Price();
$priceFormat = new CatalogHelper\ElementRepository();
while($ob = $res->GetNext()) {
    foreach ($userToGoodIDs as $k => $arrIds ) {
        if (in_array($ob['ID'], $arrIds)) {
            $price= $priceObject->getWithoutDiscountPrices($ob['ID'])['PRICE']; //получаем цену
            if(!empty($ob['PREVIEW_PICTURE'])) {
                $hasPhoto = true;
 
                $arFileTmp = CFile::ResizeImageGet(
                    $ob['PREVIEW_PICTURE'],
                    array("width" => 300, "height" => 300),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
                $picture = CFile::GetFileArray($ob['PREVIEW_PICTURE']);
            }
            $tmpInfo = [
                'CATALOG_QUANTITY' => $ob['CATALOG_QUANTITY'],
                'GOOD' => $ob['NAME'],
                'PRODUCT_ID' => $ob['ID'],
                'CODE'=>  $ob['CODE'],
                'LINK'=> $ob['DETAIL_PAGE_URL'],
                'IMG' => ($hasPhoto) ? $arFileTmp['src'] : '/local/client/img/no-photo.jpg',
                'IMG_NAME' => ($hasPhoto) ? $picture['ORIGINAL_NAME'] : 'no_photo',
                'PRICE' => (!empty($price)) ? $priceFormat->formatPrice($price) . ' ₽' : '',
            ];
            $waitUsersGoods[$k]['ITEMS'][$ob['ID']]= array_merge($tmpInfo, $waitUsersGoods[$k]['ITEMS'][$ob['ID']]);
        } 
    }
}

//Делаем выборку подходящих элементов, у которых
$goodsAvailaible= $waitUsersGoods;
foreach ($goodsAvailaible as $userId=> &$user) {
    foreach ($user['ITEMS'] as $itemID => &$item){
        if($item['CATALOG_QUANTITY'] < $item['COUNT'] || $item['UF_IS_SENT'] == true) {
            unset($user['ITEMS'][$itemID]);
        }
        if($item['UF_EMAIL_PERMISSION'] == false){
            unset($user['ITEMS'][$itemID]);
        }

    }
    if(empty($user['ITEMS'])){
        unset($goodsAvailaible[$userId]);
    }
}

//Выбираем товары, у которых письмо о наличии уже отправлено, но их снова нет в наличии
$goodsNotAvailableAgain = $waitUsersGoods;
foreach ($goodsNotAvailableAgain as $userId=> &$user) {
    foreach ($user['ITEMS'] as $itemID => &$item){
        if($item['UF_EMAIL_PERMISSION'] == false  ||  $item['UF_IS_SENT'] == false || $item['CATALOG_QUANTITY'] > $item['COUNT']){
            unset($user['ITEMS'][$itemID]);
        }
    }
    if(empty($user['ITEMS'])){
        unset($goodsNotAvailableAgain[$userId]);
    }
}

//формирует массив данных для каждого письма
$arFields = [];
$i = 0;
foreach ($goodsAvailaible as $availableGoodsValue) {
    foreach ($availableGoodsValue['ITEMS'] as $itemValue) {
        $arFields[$i] = array();
        foreach ($itemValue as $key => $value) {
            $arFields[$i][$key] = $value;
        }
        foreach ($availableGoodsValue as $innerArrayKey => $innerArrayValue) {
            if (!is_array($innerArrayValue)) {
            // если элемент не массив, то мы попали в поле ID, LOGIN, EMAIL, и т.д.
            // запишем его в тот же i-й массив $arFields[$i] по ключам;
                $arFields[$i][$innerArrayKey] = $innerArrayValue;
                $arFields[$i]['HOST'] = $host;
            }
        }
        $i++;
    }
}

//Формируем правильную структуру массива для обновления элементов
$arUpdate = [];
$updateID = [];
$j=0;
foreach ($goodsNotAvailableAgain as $notAvaliable) {
    foreach ($notAvaliable['ITEMS'] as $itemValue) {
        $arUpdate[$j] = array();
        foreach ($itemValue as $key => $value) {
            $arUpdate[$j][$key] = $value;
        }
        foreach ($notAvaliable as $innerArrayKey => $innerArrayValue) {
            if (!is_array($innerArrayValue)) {
                $arUpdate[$j][$innerArrayKey] = $innerArrayValue;
            }
        }
        $j++;
    }
}

if(!empty($arUpdate)) {
    $arUserId = [];
    $arProdId = [];
    foreach ($arUpdate as $updateEl) {
        $arUserId[] = $updateEl['ID'];
        $arProdId[] = $updateEl['PRODUCT_ID'];
    }
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList(array(
        'select' => ['ID'],
        'filter' => [
            'UF_USER_ID' => $arUserId,
            'UF_PRODUCT_ID' => $arProdId,
        ],
    ));
    $updateData = array(
        "UF_IS_SENT" => false
    );
    while ($el = $rsData->fetch()) {
        $result = $entity_data_class::update($el['ID'],$updateData);
        if(!$result->isSuccess())
            $errors = $result->getErrorMessages();
    }
}

if(!empty($arFields)){
    $arrUserId= [];
    $arrProductId = [];
    foreach($arFields as $fields ){
        $arrUserId[] = $fields['ID'];
        $arrProductId[] = $fields['PRODUCT_ID'];
    }
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList(array(
        'select' => ['ID'],
        'filter' => [
            'UF_USER_ID' => $arrUserId,
            'UF_PRODUCT_ID' => $arrProductId,
        ],
    ));

    foreach($arFields as $fields) {
        CEvent::Send(
            EVENT_TYPE,
            SITE_ID,
            $fields
        );

        $data = array(
            "UF_IS_SENT" => true
        );
        while ($el = $rsData->fetch()) {
            $ID = $el['ID'];

            $result = $entity_data_class::update($ID,$data);
            if(!$result->isSuccess())
                $errors = $result->getErrorMessages();
        }
    }
}