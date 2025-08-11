<?php

namespace InfosoftSD\Cart;

class CartItem
{
    /** @var string */
    protected $rowId;

    /** @var string */
    protected $identifier;

    /** @var float */
    protected $quantity;

    /** @var float */
    protected $price;

    protected $data;

    /** @var float */
    protected $vatRate;

    /** @var bool */
    protected $vatIncluded;

    /** @var int */
    private $precision = 2;

    /** @var string */
    protected $bc = '';

    /**
     * CartItem constructor.
     * @param $identifier
     * @param float|int $price
     * @param float|int $quantity
     * @param $data
     * @param bool $vatIncluded
     * @param float|int $vatRate
     * @param $bc
     */
    public function __construct($identifier, $price, $quantity = 1, $data = null, $vatIncluded = true, $vatRate = 20, $bc = '')
    {
        $this->rowId = uniqid($identifier, false);
        $this->identifier = $identifier;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->data = $data;
        $this->vatIncluded = $vatIncluded;
        $this->vatRate = $vatRate;
        $this->bc = $bc;
    }

    /**
     * @return string
     */
    public function getRowId()
    {
        return $this->rowId;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * @return string
     */
    public function getBc()
    {
        return $this->bc;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function &getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    
    /**
     * @param mixed $bc
     * @return $this
     */
    public function setBc($bc)
    {
        $this->bc = $bc;
        return $this;
    }

    /**
     * @return float
     */
    public function getVatRate()
    {
        return $this->vatRate;
    }

    /**
     * @param float $vatRate
     * @return CartItem
     */
    public function setVatRate($vatRate)
    {
        $this->vatRate = $vatRate;
        return $this;
    }

    /**
     * @param boolean $vatIncluded
     * @return CartItem
     */
    public function setVatIncluded($vatIncluded)
    {
        $this->vatIncluded = $vatIncluded;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVatIncluded()
    {
        return $this->vatIncluded;
    }

    public function getPriceInfo()
    {
        return new PriceDetails($this->price, $this->quantity, $this->vatRate, $this->vatIncluded, $this->precision);
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     * @return CartItem
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
        return $this;
    }
}