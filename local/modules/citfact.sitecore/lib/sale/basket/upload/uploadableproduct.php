<?php

namespace Citfact\SiteCore\Sale\Basket\Upload;


use CCurrencyLang;
use CIBlockElement;
use Citfact\SiteCore\Core;

class UploadableProduct
{
    /** @var  string */
    private $id;

    /**
     * @var string
     */
    private $article;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var boolean
     */
    private $found;

    /**
     * @var string
     */
    private $originName;

    /**
     * @var string
     */
    private $name;

    private $discount;

    private $basePrice;

    private $avail;

    /**
     * UploadableProduct constructor.
     *
     * @param string $article
     * @param int $quantity
     */
    public function __construct($article, $quantity)
    {
        $this->article = $article;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return boolean
     */
    public function isFound()
    {
        return $this->found;
    }


    /**
     * @return string
     */
    public function getOriginName()
    {
        return $this->originName;
    }


    /**
     * @return string
     */
    public function getName()
    {
        $name = !is_null($this->name) ? $this->name : $this->getOriginName();
        return $name;
    }


    /**
     * @param UploadableProduct $other
     * @return boolean
     */
    public function equals(UploadableProduct $other)
    {
        return $this->hashCode() === $other->hashCode();
    }


    /**
     * @return string
     */
    public function hashCode()
    {
        return hash('md5', $this->article);
    }

    /**
     * @param UploadableProduct $other
     * @return int
     */
    public function compareTo(UploadableProduct $other)
    {
        return self::compare($this, $other);
    }

    /**
     * @param UploadableProduct $some
     * @param UploadableProduct $another
     * @return int
     */
    public static function compare(UploadableProduct $some, UploadableProduct $another)
    {
        return strcmp($some->hashCode(), $another->hashCode());
    }

    /**
     * @param bool $format
     * @return float
     */
    public function getPrice($format =  false)
    {
        return $this->getDiscountPrice($format );
    }


    /**
     * @param bool $format
     * @return float
     */
    public function getSum($format = false)
    {
        $sum = !is_null($this->getDiscountPrice()) ? $this->getDiscountPrice() * $this->quantity : null;
        return ($format) ? $this->format($sum) : $sum;
    }

    /**
     * @param string $article
     * @return UploadableProduct
     */
    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @param int $quantity
     * @return UploadableProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param bool $found
     * @return UploadableProduct
     */
    public function setFound($found)
    {
        $this->found = $found;
        return $this;
    }

    /**
     * @param string $originName
     * @return UploadableProduct
     */
    public function setOriginName($originName)
    {
        $this->originName = $originName;
        return $this;
    }

    /**
     * @param string $name
     * @return UploadableProduct
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param float $price
     * @return UploadableProduct
     */
    public function setPrice($price)
    {
        $this->basePrice = (float) $price;
        return $this;
    }

    /**
     * @param int avail
     * @return UploadableProduct
     */
    public function setAvail($cnt)
    {
        $this->avail = (int) $cnt;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvail()
    {
        return $this->avail;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return UploadableProduct
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @param bool $format
     * @return mixed
     */
    public function getBasePrice($format = false)
    {
        $price =  $this->basePrice;
        return ($format) ? $this->format($price)  : $price;
    }

    /**
     * @param mixed $basePrice
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = (float) $basePrice;
        return $this;
    }

    public function format($value)
    {
        return CCurrencyLang::CurrencyFormat($value, 'RUB');
    }

    public function getDiscountPrice($format = false)
    {
        $price =   $this->basePrice * ((100 - $this->discount)/100);
        return ($format) ? $this->format($price) : $price;
    }

    public function toArray()
    {

        return [
            'id' => $this->getId(),
            'article' => $this->getArticle(),
            'quantity' => $this->getQuantity(),
            'found' => $this->isFound(),
            'originName' => $this->getOriginName(),
            'name' => $this->getName(),
            'discount' => $this->getDiscount(),
            'base_price' => $this->getBasePrice(),
            'base_price_format' => $this->getBasePrice(true),
            'price' => $this->getPrice(),
            'price_format' => $this->getPrice(true),
            'hash' => $this->hashCode(),
            'sum' => $this->getSum(),
            'sum_format' => $this->getSum(true),
        ];
    }

}