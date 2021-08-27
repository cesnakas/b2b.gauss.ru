<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

use Bitrix\Main\UserTable;
use Bitrix\Sale\Order;
use Dompdf\Dompdf;
use Dompdf\Options;
use Citfact\Tools\Tools;

Loc::loadMessages(__FILE__);
Loader::includeModule('sale');
Loader::includeModule('citfact.tools');

global $USER;
$arReturn = array('errors'=>array(), 'result'=>array(), 'debug'=>array());

$application = Application::getInstance();
$request = $application->getContext()->getRequest();

if ($request->isPost() && check_bitrix_sessid()) {

    $postData = $request->getPostList()->toArray();
    array_walk_recursive($postData, 'strip_tags');
    $arReturn['debug']['post'] = $postData;


    /** @var \Bitrix\Sale\Order $order */
    $order = Order::load($postData['orderId']);
    if (!$order){
        throw new \Exception('Order not found or load error');
    }

    $arOrder = [
        'ID' => $order->getId(),
        'DATETIME' => $order->getDateInsert()->format('d.m.Y'),
        'PRICE' => Tools::formatNumber($order->getField('PRICE')),
        'PRICE_WORDS' => Number2Word_Rus($order->getField('PRICE')),
        'TAX' => Tools::formatNumber($order->getField('TAX_VALUE')),
        'TAX_WORDS' => Number2Word_Rus($order->getField('TAX_VALUE'))
    ];

    $fieldValues = $order->getFieldValues();
    $tableEntity = UserTable::getEntity();
    $query = new \Bitrix\Main\Entity\Query($tableEntity);
    $query
        ->setFilter(array("ID" => $fieldValues['USER_ID']))
        ->setSelect(array('ID', 'NAME'));
    $result = $query->exec();
    $arUserData = array();
    if ($row = $result->fetch()) {
        $arUserData = $row;
    }
    $arOrder['USER_NAME'] = $arUserData['NAME'];

    $arProps = $order->getPropertyCollection()->getArray();
    $arPropsCodes = [
        'ID_1C',
        'PICKUP_ADDRESS',
        'VAT',
    ];
    foreach ($arProps['properties'] as $arProp){
        if (in_array($arProp['CODE'], $arPropsCodes)){
            $arOrder['PROPERTIES'][$arProp['CODE']] = $arProp['VALUE'][0];
        }
    }

    $arOrder['TAX'] = Tools::formatNumber($arOrder['PROPERTIES']['VAT']);
    $arOrder['TAX_WORDS'] = Number2Word_Rus($arOrder['PROPERTIES']['VAT']);

    $arBasketItems = [];
    $basket = $order->getBasket();
    /** @var \Bitrix\Sale\BasketItem $basketItem*/
    foreach ($basket as $basketItem) {
        $arTemp = [];
        $arTemp['NAME'] = $basketItem->getField('NAME');
        $quantity = $basketItem->getQuantity();
        $arTemp['QUANTITY'] = $quantity;
        $arTemp['MEASURE_NAME'] = $basketItem->getField('MEASURE_NAME');

        $basketPropertyCollection = $basketItem->getPropertyCollection();
        $arPropsValues = $basketPropertyCollection->getPropertyValues();
        $arTemp['ARTICLE'] = $arPropsValues['Артикул']['VALUE'];

        $price = $basketItem->getField('PRICE');
        $basePrice = $basketItem->getField('BASE_PRICE');
        $discountPrice = $basketItem->getField('DISCOUNT_PRICE');
        $baseSum = $basePrice * $quantity;
        $discount = $discountPrice * $quantity;
        $sum = $price * $quantity;

        $arTemp['PRICE'] = Tools::formatNumber($price);
        $arTemp['BASE_SUM'] = Tools::formatNumber($baseSum);
        $arTemp['DISCOUNT'] = Tools::formatNumber($discount);
        $arTemp['SUM'] = Tools::formatNumber($sum);
        $arBasketItems[] = $arTemp;
    }
    $arOrder['BASKET_ITEMS'] = $arBasketItems;

    $pdfTemplatePath = 'templateBillAgreement.php';
    $html = '';
    if (file_exists($pdfTemplatePath)) {
        ob_start();
        include $pdfTemplatePath;
        $html = ob_get_clean();
    }
    else{
        throw new \Exception('PDF template file not found');
    }


    // instantiate and use the dompdf class $options = new Options();
    $options = new Options();
    //$options->set('defaultFont', 'times');
    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    //$dompdf->stream('document',array('Attachment'=>0));
    $output = $dompdf->output();

    $arParams = array("replace_space"=>"-","replace_other"=>"-");
    $trans = Cutil::translit('filename', "ru", $arParams);
    $pathRel = '/upload/pdf_to_download/LSR_schet-dogovor_'.$arOrder['PROPERTIES']['ID_1C'].'.pdf';
    $pathFull = $_SERVER['DOCUMENT_ROOT'].$pathRel;
    if (file_put_contents($pathFull, $output) !== false){
        $arReturn['result']['path'] = $pathRel;
    }

}


$strReturn = json_encode($arReturn);
echo $strReturn;?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>