<?php

use endrilala\Cart\PriceDetails;

class PriceCalculatorTest extends PHPUnit_Framework_TestCase
{
    public function testVatIncludedShouldPass()
    {
        $calc = new PriceDetails($pricePerUnit = 10, $quantity = 1, $vatRate = 20, $priceIncluded = true);

        $this->assertEquals(8.33, $calc->getNetPrice(), '', 0.01);
        $this->assertEquals(8.33, $calc->getNetPricePerUnit(), '', 0.01);
        $this->assertEquals(1.67, $calc->getVat(), '', 0.01);
        $this->assertEquals(10, $calc->getGrossPrice());

        $this->assertEquals($calc->getNetPricePerUnit(), $calc->getNetPrice());
        $this->assertEquals($calc->getVatPerUnit(), $calc->getVat());
        $this->assertEquals($calc->getGrossPricePerUnit(), $calc->getGrossPrice());
    }

    public function testVatIncludedBigQuantityShouldPass()
    {
        $calc = new PriceDetails($pricePerUnit = 10, $quantity = 60, $vatRate = 20, $priceIncluded = true);

        $this->assertEquals(500, $calc->getNetPrice(), '', 0.01);
        $this->assertEquals(100, $calc->getVat(), '', 0.01);
        $this->assertEquals(600, $calc->getGrossPrice());

        $this->assertEquals(8.33, $calc->getNetPricePerUnit(), '', 0.01);
        $this->assertEquals(1.67, $calc->getVatPerUnit(), '', 0.01);
        $this->assertEquals(10, $calc->getGrossPricePerUnit());
    }

    public function testVatNotIncludedShouldPass()
    {
        $calc = new PriceDetails($pricePerUnit = 10, $quantity = 1, $vatRate = 20, $priceIncluded = false);

        $this->assertEquals(10, $calc->getNetPrice());
        $this->assertEquals(10, $calc->getNetPricePerUnit());
        $this->assertEquals(2, $calc->getVat(), '', 0.001);
        $this->assertEquals(12, $calc->getGrossPrice());
    }

    public function testVatNotIncludedBigQuantityShouldPass()
    {
        $calc = new PriceDetails($pricePerUnit = 10, $quantity = 60, $vatRate = 20, $priceIncluded = false);

        $this->assertEquals(600, $calc->getNetPrice(), '', 0.001);
        $this->assertEquals(120, $calc->getVat(), '', 0.001);
        $this->assertEquals(720, $calc->getGrossPrice());

        $this->assertEquals(10, $calc->getNetPricePerUnit());
        $this->assertEquals(2, $calc->getVatPerUnit());
        $this->assertEquals(12, $calc->getGrossPricePerUnit());
    }
}