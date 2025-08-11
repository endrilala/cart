<?php

namespace Maldoinc\Cart;

class CartSummary
{
    private $grossPrice;
    private $netPrice;
    private $vat;

    private $grossPriceBeforeFees;
    private $netPriceBeforeFees;
    private $vatBeforeFees;

    /**
     * @param CartItem[] $items
     */
    protected function calculateSummary($items)
    {
        foreach ($items as $item) {
            $price = $item->getPriceInfo();

            $this->grossPrice += $price->getGrossPrice();
            $this->vat += $price->getVat();
            $this->netPrice += $price->getNetPrice();
        }

        $this->grossPriceBeforeFees = $this->grossPrice;
        $this->netPriceBeforeFees = $this->netPrice;
        $this->vatBeforeFees = $this->vat;
    }

    /**
     * @param CartFee[] $fees
     * @throws \Maldoinc\Cart\Exception\InvalidCartFeeValueException
     */
    protected function applyCartFees($fees)
    {
        foreach ($fees as $fee) {
            // in order to apply the flat fee we have to convert it to percentage fee first
            $f = new CartFee('',
                $fee->isPercentValue()
                    ? $fee->getValue()
                    : ($fee->getValue() / $this->grossPrice * 100) . '%'
            );

            $this->grossPrice = $f->getAmount($this->grossPrice);
            $this->vat = $f->getAmount($this->vat);
            $this->netPrice = $f->getAmount($this->netPrice);
        }
    }

    /**
     * @param CartItem[] $items
     * @param CartFee[] $extraFees
     *
     * @return CartSummary
     */
    public static function getSummary($items, $extraFees)
    {
        $res = new self();
        $res->calculateSummary($items);

        if ($res->getGrossPrice() > 0) {
            $res->applyCartFees($extraFees);
        }

        return $res;
    }

    /**
     * @return double
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
    }

    /**
     * @return double
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * @return double
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @return mixed
     */
    public function getGrossPriceBeforeFees()
    {
        return $this->grossPriceBeforeFees;
    }

    /**
     * @return mixed
     */
    public function getNetPriceBeforeFees()
    {
        return $this->netPriceBeforeFees;
    }

    /**
     * @return mixed
     */
    public function getVatBeforeFees()
    {
        return $this->vatBeforeFees;
    }
}
