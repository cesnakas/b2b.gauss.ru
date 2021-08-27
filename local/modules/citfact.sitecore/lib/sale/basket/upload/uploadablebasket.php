<?php

namespace Citfact\SiteCore\Sale\Basket\Upload;


use Bitrix\Main\Diag\Debug;
use Countable;
use SplFixedArray;

/**
 * Class UploadableBasket
 * @package Citfact\Core\Sale\Basket
 */
class UploadableBasket implements \IteratorAggregate
{

    // TODO: Replace with modern data structure
    /**
     * @var SplFixedArray|UploadableProduct[]|Countable
     */
    private $holder;

    /**
     * UploadableBasket constructor.
     * @param UploadableProduct[] $products
     */
    private function __construct(array $products)
    {
        $this->holder = SplFixedArray::fromArray($products, false);
    }

    public static function fromArray(array $products)
    {
        $result = [];
        foreach ($products as $k => $product) {
            $result[] = (new UploadableProduct($product['article'], $product['quantity']))
                ->setFound(($product['found'] == 1))
                ->setId($product['id'])
                ->setOriginName($product['originName'])
                ->setName($product['name'])
                ->setPrice($product['price']);
        }

        $basket = new UploadableBasket($result);
        return $basket;
    }


    /**
     * A factory method for building of uploadable basket
     *
     * @param string $input
     * @param string $keyArticle
     * @param string $keyQuantity
     * @return UploadableBasket
     * @throws InterpretationException
     * @throws FileUploadException
     */
    public static function create($input, $keyArticle=0, $keyQuantity=1)
    {
        $interpreter = new InputInterpeter();

        try {
            $products = $interpreter->interpret($input, $keyArticle, $keyQuantity);
            $basket = new UploadableBasket($products);
            return $basket;
        } catch (InterpretationException $e) {
            ///Debug::writeToFile('Fail to interpet input of uploadable basket, cause: ' . $e->getMessage(), '', LOGFILE);

            throw new InterpretationException($e->getMessage());

        } catch (FileUploadException $e) {

            throw new FileUploadException($e->getMessage());
        }
    }

    /**
     * A factory method for building of empty uploadable basket
     *
     * @return UploadableBasket
     */
    public static function createEmpty()
    {
        $basket = new UploadableBasket([]);
        return $basket;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->holder->count() === 0;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return $this->holder;
    }

    /**
     * @param UploadableProduct $product
     * @return int
     */
    public function indexOf(UploadableProduct $product)
    {
        foreach ($this->holder as $hold) {
            if ($hold->equals($product)) {
                return $this->holder->key() + 1;
            }
        }

        throw new NotFoundException();
    }

    public function remove($itemId)
    {
        $newHold = [];
        foreach ($this->holder as $hold) {
            if ($hold->hashCode() != $itemId) {
                $newHold[] = $hold;
            }
        }

        $this->holder = SplFixedArray::fromArray($newHold);
    }

    public function increaseQuantityItem($itemId)
    {
        foreach ($this->holder as $key => $hold) {
            if ($hold->hashCode() == $itemId) {
                $curQuantity = $hold->getQuantity();
                $hold->setQuantity($curQuantity + 1);
            }
        }
    }

    public function decreaseQuantityItem($itemId)
    {
        foreach ($this->holder as $key => $hold) {
            if ($hold->hashCode() == $itemId) {
                $curQuantity = $hold->getQuantity();
                if (1 < $curQuantity ) {
                    $hold->setQuantity($curQuantity - 1);
                }
            }
        }
    }

    public function editQuantityItem($itemId, int $quantity)
    {
        foreach ($this->holder as $key => $hold) {
            if ($hold->hashCode() == $itemId) {
                if (1 <= $quantity ) {
                    $hold->setQuantity($quantity);
                }
            }
        }
    }

    public function toArray()
    {
        $result = [];

        /** @var UploadableProduct $product */
        foreach ($this->holder as $k => $product) {
            $result[] = $product->toArray();
        }
        return $result;
    }

    public function getById($itemID)
    {
        foreach ($this->holder as $hold) {
            if ($hold->hashCode() == $itemID) {
                return $hold;
            }
        }

        return null;
    }

    public function add(UploadableProduct $product)
    {
        foreach ($this->holder as $hold) {
            $newHold[] = $hold;
        }

        $newHold[] = $product;

        $this->holder = SplFixedArray::fromArray($newHold);
    }

    public function changeItem($oldItemId, array $newItem)
    {
        /** @var UploadableProduct $oldProduct */
        if ($oldProduct = $this->getById($oldItemId)) {
            $product = (new UploadableProduct($newItem['CML_ARTICLE'], $oldProduct->getQuantity()))
                ->setName($newItem['NAME'])
                ->setFound(true)
                ->setPrice($newItem['PRICE']);
            $this->remove($oldProduct->hashCode());
            $this->add($product);

            return true;
        }

        return false;
    }


}