<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\SiteCore\Dokumentatsiya\DokumentatsiyaManager;
use Citfact\SiteCore\RekomendovannyeTovary\RekomendovannyeTovaryManager;
use Citfact\SiteCore\Rezervy\RezervyManager;
use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock as HL;
use Citfact\SiteCore\Core;
use \Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Tools\HLBlock;
use Citfact\Sitecore\Video;
use Citfact\SiteCore\Tools\InternetResourcesHelper;


/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


//Получаем данные пользовательское свойство Отзывы

$rsResult = CIBlockSection::GetList(array("SORT"=>"ASC"), array(
    "IBLOCK_ID"=> $arResult['ORIGINAL_PARAMETERS']['IBLOCK_ID'],
    'CODE' => $arResult['ORIGINAL_PARAMETERS']['SECTION_CODE']
), false, array("UF_*"));
if($res = $rsResult->Fetch())
{
}

//Получаем данные из HL блока и записываем их в arResult по секциям

$core = Core::getInstance();
$hlblockOb = new HLBlock();

//Получаем массив привязанных к товару интернет-ресурсов

$internetResourcesHelper = new InternetResourcesHelper();
$internetResources = $internetResourcesHelper->getResourcesOfNomenclature($arResult['XML_ID']);

$videoOb = new Video();
foreach ($internetResources as &$resource){
    if(!empty($resource['UF_PICTURE'])) {
        $path =  $internetResourcesHelper->getPath($resource['ID']);
        $resource['PICTURE'] = $path;
    }

    if($resource['UF_KINDID'] == '01' || $resource['UF_KINDID'] == '05'){
        $resource['IFRAME_LINK'] = $videoOb->convertUrlToIframe($resource['UF_LINK']);
        $arResult['REVIEW_IN_CAROUSEL'][] = $resource;

    } else {
        $arResult['REVIEW_IN_REVIEWS_BLOCK'][] = $resource;
    }
}

if(!empty($arResult['REVIEW_IN_CAROUSEL'])){
    unset($arResult['PROPERTIES']['YOUTUBE_VIDEO']);
}

if(empty($arResult['REVIEW_IN_REVIEWS_BLOCK'])){
    $hlId= $core->getHlBlockId($core::HL_BLOCK_CODE_REVIEWS);
    $hlblockRev = HL\HighloadBlockTable::getById($hlId)->fetch();
    if( Loader::IncludeModule('highloadblock')){
        $entity = HL\HighloadBlockTable::compileEntity($hlblockRev);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID"=>$res['UF_SECTION_REVIEW'])  // Задаем параметры фильтра выборки
        )) ;
        while($arData = $rsData->Fetch()){
            $arResult['UF_SECTION_REVIEW'][] = $arData;
        }
    }
//Получаем данные с HL Reviews и записываем их в $arResult для каждого элемента
    $arHighloadProperty = $arResult["PROPERTIES"]['REVIEWS'];
    $sTableName = $arHighloadProperty['USER_TYPE_SETTINGS']['TABLE_NAME'];
    if ( Loader::IncludeModule('highloadblock') && !empty($sTableName) && !empty($arHighloadProperty["VALUE"]) )
    {
        $hlblock = HL\HighloadBlockTable::getRow([
            'filter' => [
                '=TABLE_NAME' => $sTableName
            ],
        ]);
        if ( $hlblock )
        {
            $entity      = HL\HighloadBlockTable::compileEntity( $hlblock );
            $entityClass = $entity->getDataClass();

            $arRecords = $entityClass::getList([
                'filter' => [
                    'UF_XML_ID' => $arHighloadProperty["VALUE"]
                ],
            ]);
            $arReviews =[];
            while ($res = $arRecords->Fetch()) {
                $arReviews[] = $res;
            }

            $arResult['REVIEWS'] = $arReviews;
        }
    }
}

$rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => $arResult['ID']), false, false, false);

if ($arStore = $rsStore->Fetch())
     $arResult['CATALOG_QUANTITY'] = $arStore['AMOUNT'];


if (!empty($arResult['DETAIL_PICTURE']['SRC'])) {
    $arResult['DETAIL_PICTURE'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures($arResult['DETAIL_PICTURE'], 635, 635,  0,0, 127, 127);
}

if (!empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
    if (count($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])==1){
        $arResult['NEW_MORE_PHOTO']['0'] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures(
            $arResult['PROPERTIES']['MORE_PHOTO']['VALUE']['0'], 1635, 1635,  0,0, 127, 127);
    }else{
        foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $key=>$value){
            $arResult['NEW_MORE_PHOTO']["$key"] = \Citfact\SiteCore\Pictures\ResizeManager::getResizePictures(
                $arResult['PROPERTIES']['MORE_PHOTO']['VALUE']["$key"], 1635, 1635,  0,0, 127, 127);
        }
    }
}

foreach ($arResult['DISPLAY_PROPERTIES'] as $arItem) :
    if(!in_array($arItem['CODE'],$arParams['EXCLUDE_PARAMS'])):
        $arResult['ITEM_PROPERTIES'][] = $arItem;
     endif;
endforeach;

//Получаем и формируем массив с дополнительными ценами

$extraPricesXmlInfo = Price::getExtraPricesXmlInfo();
$pricesIDs = array_keys($extraPricesXmlInfo);
$extraPrices = [];
\Bitrix\Main\Loader::includeModule("catalog");

$allProductPrices = \Bitrix\Catalog\PriceTable::getList([
    "select" => ["*"],
    "filter" => [
        "=PRODUCT_ID" => $arResult['ID'],
        'CATALOG_GROUP_ID' => $pricesIDs
    ],
    "order" => ["CATALOG_GROUP_ID" => "ASC"]
]);

while($extra = $allProductPrices->Fetch()){
    $extraPrices[$extra['CATALOG_GROUP_ID']] = $extra;
    foreach ($extraPricesXmlInfo as $groupId => $xmlId ) {
        foreach ($extraPrices as $key => $value) {
            if ($groupId == $key) {
                $extraPrices[$key]['XML_ID'] = $xmlId;
                $extraPrices[$key]['LABEL']= Price::getLabelExtraPrice($extraPrices[$key]['XML_ID']);
            }
        }
    }
}
$arResult['EXTRA_PRICES'] = $extraPrices;


//Получаем изображения 360
$dir = '/upload/ftp_images_360/'. $arResult['PROPERTIES']['CML2_ARTICLE']['~VALUE'].'_360';
$links360 = scandir($_SERVER['DOCUMENT_ROOT']. $dir);
foreach ($links360 as $key=> $link){
    if($link == '..' || $link =='.'){
        unset($links360[$key]);
    }
}
natsort($links360);
foreach ($links360 as $link){
    $arResult['IMAGES_360'][] = $dir.'/'. $link;
}

$arResult['RESERV_BALANCE'] = RezervyManager::getByNomenclature($arResult['XML_ID']);
$arResult['DOCUMENTATIONS'] = DokumentatsiyaManager::getListByNomenclature($arResult['XML_ID']);
$arRecommend = RekomendovannyeTovaryManager::getListByContragent($arParams['CONTRAGENT']);

$arResult['RECOMENDATION'] = [];
foreach ($arRecommend as $recommend) {
    $arResult['RECOMENDATION'][] = $recommend['UF_NOMENKLATURA'];
}

$arResult['modelValue'] = $arResult['DISPLAY_PROPERTIES']['MODEL']['VALUE'];
$this->__component->SetResultCacheKeys(array('modelValue', 'RECOMENDATION','ID'));