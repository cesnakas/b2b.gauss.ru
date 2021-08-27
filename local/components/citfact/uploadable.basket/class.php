<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Citfact\Sitecore\Order\Basket;
use Citfact\SiteCore\Sale\Basket\Upload\UploadableBasket;
use Citfact\SiteCore\Sale\Basket\Upload\InputInterpeter;
use Citfact\SiteCore\Sale\Basket\Upload\FileUploadException;
use Citfact\SiteCore\Sale\Basket\Upload\InterpretationException;
use Citfact\Sitecore\Sale\Basket\Upload\UploadableBasketStorage;
use Citfact\SiteCore\Sale\Basket\Upload\UploadableProduct;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 */
Loc::loadMessages(__FILE__);

class UploadableBasketComponent extends CBitrixComponent
{
    /** @var  UploadableBasketStorage */
    private $basketStorage;


    public function executeComponent()
    {
        global $USER;

        $this->basketStorage = new UploadableBasketStorage();

        $request = Context::getCurrent()
            ->getRequest();
        $result = [
            'message' => '',
            'error' => null,
            'type' => null,
            'status' => true,
            'data' => $this->basketStorage->loadData()
        ];


        if ($request->getPost('method') && $request->isAjaxRequest()) {

            try {

                if ($USER->IsAuthorized()) {

                    $r = $request->getPost('method');
                    $data = $request->getPost('data');
                    $this->basketStorage->loadBasket();
                    $basket = UploadableBasket::fromArray($this->basketStorage->loadBasket());

                    switch ($r) {
                        case 'importText':
                            $input = $request->getPost('data');

                            $basket = UploadableBasket::create($input, 0, 1);
                            $this->basketStorage->saveBasket($basket->toArray());
                            $this->basketStorage->saveMode(UploadableBasketStorage::MODE_EXPORT);
                            break;

                        case 'importFile':
                            try {
                                $file = $request->getFile('fileImport');
                                $inputFileName = $file['tmp_name'];

                                if ($file['size'] > 1024 * 1024 * 10) {
                                    throw new FileUploadException(Loc::getMessage('INCORRECT_FILE_SIZE'));
                                }

                                $reader = new Xlsx();
                                $spreadsheet = $reader->load($inputFileName);

                                $data = $spreadsheet->getActiveSheet()
                                    ->toArray([]);

                                $this->clearData($data);

                                if (!empty($data)) {
                                    $interpreter = new InputInterpeter();
                                    $headers = $interpreter->getHeaders($data);

                                    $keyArticle = $headers[0];
                                    $keyQuantity = $headers[1];

                                    foreach ($data as $str) {
                                        if (
                                            (!isset($str[$keyQuantity]) || empty($str[$keyQuantity])) &&
                                            (is_string($str[0]) || intval($str[0]) > 0) && (intval($str[1]) > 0)
                                        ){
                                            $keyArticle = 0;
                                            $keyQuantity = 1;
                                        }
                                        break;
                                    }
                                    $basket = UploadableBasket::create($data,$keyArticle, $keyQuantity);
                                    $this->basketStorage->saveBasket($basket->toArray());
                                    $this->basketStorage->saveMode(UploadableBasketStorage::MODE_EXPORT);
                                }

                            } catch (PhpOffice\PhpSpreadsheet\Exception $e) {
                                $result['message'] = Loc::getMessage('INCORRECT_FORMAT');
                                $result['type'] = 'file';
                            } catch (Exception $e) {
                                $result['message'] = $e->getMessage();
                                $result['type'] = 'file';
                            }

                            break;

                        case 'clearBasket':
                            $this->basketStorage->clearData();
                            break;

                        case 'getData':
                            break;

                        case 'changeItem':
                            break;

                        case 'selectItem':
                            break;

                        case 'deleteItem':
                            $itemId = $data['itemId'];
                            $basket->remove($itemId);
                            $this->basketStorage->saveBasket($basket->toArray());
                            break;

                        case 'increaseQuantityItem':
                            $itemId = $data['itemId'];
                            $basket->increaseQuantityItem($itemId);
                            $this->basketStorage->saveBasket($basket->toArray());
                            break;

                        case 'decreaseQuantityItem':
                            $itemId = $data['itemId'];
                            $basket->decreaseQuantityItem($itemId);
                            $this->basketStorage->saveBasket($basket->toArray());
                            break;

                        case 'editQuantityItem':
                            $itemId = $data['itemId'];
                            $quantity = $data['quantity'];
                            $basket->editQuantityItem($itemId, $quantity);
                            $this->basketStorage->saveBasket($basket->toArray());
                            break;

                        case 'addItemsAndMoveImport':
                        case 'addItemsAndMoveBasket':
                            $this->addItemsToSiteBasket($basket);
                            $this->basketStorage->clearData();
                            break;

                        case 'setMode':
                            $result['data']['mode'] = 'catalog';
                            break;
                    }

                    $result['data'] = $this->basketStorage->loadData();

                } else {
                    $result = [
                        'message' => Loc::getMessage('NEED_AUTH'),
                        'type' => 'text',
                        'status' => false,
                        'data' => $this->basketStorage->blankData()
                    ];
                }

            } catch (Exception $e) {
                $result['message'] = $e->getMessage();
                $result['error'] = $e->getMessage();
                $result['type'] = $e instanceof FileUploadException ? 'file' : 'text';
                $result['data'] = null;
            }


            $this->toJson($result);

        }

        $this->includeComponentTemplate('');
    }

    public function clearData(array &$data)
    {
        foreach ($data as $key => &$line){
            foreach ($line as &$item){
                if(gettype($item) == 'array' && empty($item)){
                    $item = "";
                }
            }
            if(array_diff($line, ['']) == []){
                unset($data[$key]);
            }
            unset($item);
        }
        unset($line);
    }

    public function toJson(array $data)
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        exit(json_encode($data));
    }

    /** @param $uploadBasket UploadableBasket */
    public function addItemsToSiteBasket($uploadBasket)
    {
        if (!$uploadBasket->isEmpty()) {

            /** @var array $products */
            $products = [];

            /** @var UploadableProduct $product */
            foreach ($uploadBasket as $product) {
                if ($product->isFound()) {
                    $products[] = [
                        'PRODUCT_ID' => intval($product->getId()),
                        'QUANTITY' => intval($product->getQuantity()),
                    ];
                }
            }

            if (!empty($products)) {
                Basket::addProducts($products, ['PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProviderCustom']);
            }
        }
    }

}