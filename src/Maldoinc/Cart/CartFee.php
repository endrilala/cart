<?php

namespace Maldoinc\Cart;

use Maldoinc\Cart\Exception\InvalidCartFeeValueException;

class CartFee
{
    /** @var string */
    private $description;
    /** @var float|int */
    private $value;

    protected function stringIsPercentage($value)
    {
        return is_string($value) && preg_match('/^(?:\+|-)?(?:\d+\.)?\d+\%$/', $value) === 1;
    }

    public function isPercentValue()
    {
        return $this->stringIsPercentage($this->value);
    }

    /**
     * @param $total
     * @return float|int
     */
    public function getAmount($total)
    {
        if ($this->isPercentValue()) {
            return $total + ($total * (float)$this->value / 100);
        }

        return $total + (float)$this->value;
    }

    /**
     * CartSubtotalLine constructor.
     * @param $description
     * @param $value
     * @throws \Maldoinc\Cart\Exception\InvalidCartFeeValueException
     */
    public function __construct($description, $value)
    {
        $this->description = $description;
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float|int $value
     * @throws \Maldoinc\Cart\Exception\InvalidCartFeeValueException
     */
    public function setValue($value)
    {
        if (!(is_numeric($value) || $this->stringIsPercentage($value))) {
            throw new InvalidCartFeeValueException("Invalid subtotal line value");
        }

        $this->value = $value;
    }
}
