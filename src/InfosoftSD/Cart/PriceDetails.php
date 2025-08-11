<?php

namespace endrilala\Cart;

class PriceDetails
{
    protected $netPrice;
    protected $netPricePerUnit;

    protected $vat;
    protected $vatPerUnit;

    protected $grossPrice;
    protected $grossPricePerUnit;

    /**
     * PriceCalculator constructor.
     * @param float $pricePerUnit
     * @param float $quantity
     * @param float|int $vatRate
     * @param bool $vatIncluded
     * @param int $precision
     */
    public function __construct($pricePerUnit, $quantity, $vatRate = 20, $vatIncluded = true, $precision = 2)
    {
        $this->quantity = $quantity;
        $round = function ($val) use ($precision) {
            return round($val, $precision);
        };
		
		
		//var_dump("Neto=".$this->netPrice.", Vat=".$this->vat.", Bruto=".$this->grossPrice.", VatRate=".$vatRate.", VatIncluded=".(int)$vatIncluded);
		//die();
		

        if ($vatIncluded) {
            $this->grossPrice = $round($pricePerUnit * $quantity);
            $this->netPrice = $round($this->grossPrice / (1 + $vatRate / 100));
            $this->vat = $round($this->grossPrice - $this->netPrice);

            $this->netPricePerUnit = $round($this->netPrice / $quantity);
            $this->vatPerUnit = $round($this->vat / $quantity);
            $this->grossPricePerUnit = $round($this->grossPrice / $quantity);
        } else {
            $this->netPricePerUnit = $round($pricePerUnit);

            $this->netPrice = $round($pricePerUnit * $quantity);
            $this->vat = $round($this->netPrice * ($vatRate / 100));
            $this->grossPrice = $round($this->netPrice + $this->vat);

            $this->vatPerUnit = $round($this->vat / $quantity);
            $this->grossPricePerUnit = $round($this->grossPrice / $quantity);
        }
		
		
    }

    /**
     * @return double
     */
    public function getNetPricePerUnit()
    {
        return $this->netPricePerUnit;
    }

    /**
     * @return double
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @return double
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
    }

    /**
     * @return mixed
     */
    public function getVatPerUnit()
    {
        return $this->vatPerUnit;
    }

    /**
     * @return mixed
     */
    public function getGrossPricePerUnit()
    {
        return $this->grossPricePerUnit;
    }
}