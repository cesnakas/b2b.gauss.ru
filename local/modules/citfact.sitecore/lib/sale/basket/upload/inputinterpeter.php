<?php

namespace Citfact\SiteCore\Sale\Basket\Upload;

use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use CIBlockElement;
use Citfact\Sitecore\CatalogHelper\Price;
use Citfact\SiteCore\Core;
use Citfact\Sitecore\UserDataManager;

class InputInterpeter
{

    /**
     * @const string
     */
    const re = '/(((\w+)\b(.*)\b(\d+))+)+/';

    /**
     * @param $input
     * @param int $keyArticle
     * @param int $keyQuantity
     * @return array
     * @throws FileUploadException
     * @throws InterpretationException
     */
    public function interpret($input, $keyArticle=0, $keyQuantity=1)
    {
        $products = [];
        // TODO: Replace with factory
        if (is_string($input)) {
            $input = trim($input);
            //self::validate($input);

            $rows = explode("\n", $input);

            $matches = array_map(function($r){
                $r = trim($r);
                $r = preg_replace('/\s+/', "\t", $r);
                return explode("\t", $r);
            }, $rows);

            $products = [];
            foreach ($matches as $match) {
                switch (count($match)) {
                    case 2:
                        $article = $match[$keyArticle];
                        $name = "";
                        $quantity = abs((int) $match[$keyQuantity]);
                        break;
                    default:
                        throw new InterpretationException('Введены некорректные данные.');
                }

                $products[] = $this->getProductPriceByArticle($article, $quantity, $name);
            }

        } elseif (is_array($input)) {

            $matches = $input;

            $products = [];
            foreach ($matches as $match) {
                switch (true) {
                    case count($match) >= 2:
                        $article = $match[$keyArticle];
                        $name = "";
                        $quantity = abs((int) $match[$keyQuantity]);
                        break;
                    default:
                        throw new FileUploadException('Введены некорректные данные, пожалуйста скопируйте данные из таблицы в примере.');
                }
                $products[] = $this->getProductPriceByArticle($article, $quantity, $name);
            }
        }

        $products = array_filter($products, function (UploadableProduct $product) { return $product->getQuantity() > 0 && !empty($product->getArticle()); });

        if (empty($products)) {

            if (is_array($input)) {
                throw new FileUploadException('Введены некорректные данные, пожалуйста скопируйте данные из таблицы в примере.');
            } else {
                throw new InterpretationException('Введены некорректные данные.');
            }
        }
        
        return $products;
    }

    /**
     * Метод динамически получает номера колонок Артикул и Количество
     * @param $input
     * @return array
     */
    public function getHeaders($input){
        $headerNames = $input[3];
        foreach ($headerNames as $key=>$name) {
            if (strpos($name,'ртикул')) {
                $artnumber = $key;
            }
            if(strpos($name,'кол-во')){
                $quantity = $key;
            }
        }
        $headers = [$artnumber,$quantity];
        return $headers;
    }

    /**
     * @param string $input
     * @throws InvalidInputException
     */
    public static function validate($input)
    {
        $valid = (bool) preg_match(self::re, $input);
        if (!$valid) {
            throw new InvalidInputException();
        }
    }

    public function getProductPriceByArticle($article = null, $quantity = 0, $name = null)
    {
        if (!is_null($article) && $article && $quantity > 0) {
            $core = Core::getInstance();

            $basePrice = Price::getPriceByCode(Price::PRICE_CODE_MIC);
            $basePriceId = empty($basePrice) ? '' : $basePrice['ID'];
            $priceTypeId = UserDataManager\UserDataManager::getUserPriceType()['ID'] ?: $basePriceId;

            $arFilter = array(
                'IBLOCK_ID' => $core->getIblockId($core::IBLOCK_CODE_CATALOG),
                'PROPERTY_CML2_ARTICLE' => $article,
                'ACTIVE' => 'Y'
            );
            $res = CIBlockElement::GetList([],
                $arFilter,
                false,
                false,
                [
                    'ID',
                    'NAME',
                    'XML_ID',
                    'DETAIL_PAGE_URL']);

            if ($element = $res->GetNext(1,0)){

                $product = (new UploadableProduct($article, $quantity))
                    ->setFound(true)
                    ->setId($element['ID'])
                    ->setName($element["NAME"])
                    ->setOriginName($name);


                $filter = ['=ID' => $element['ID']];
                $select = ['ID'];
                $item = ProductTable::getRow(['filter' => $filter, 'select' => $select]);

                $filter = [
                    '=PRODUCT_ID' => $item['ID'],
                    'CATALOG_GROUP_ID' => $priceTypeId
                ];

                $select = ['PRICE'];
                $item = PriceTable::getRow([
                    'filter' => $filter,
                    'select' => $select
                ]);



                $product->setBasePrice($item['PRICE']);
                return $product;
            }
        }
        return (new UploadableProduct($article, $quantity))
            ->setName($name);
    }
}
