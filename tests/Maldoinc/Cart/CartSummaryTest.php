<?php

use endrilala\Cart\Cart;
use endrilala\Cart\CartFee;
use endrilala\Cart\CartItem;

class CartSummaryTest extends PHPUnit_Framework_TestCase
{
    /** @var Cart */
    private $cart;

    public function setUp()
    {
        $this->cart = new Cart();
    }

    public function testSummaryNoVatInfo()
    {
        $this->cart->clear();
        $this->cart->add(new CartItem('SKU', 15));

        $summary = $this->cart->getSummary();

        $this->assertEquals(15, $summary->getGrossPrice());
        $this->assertEquals(12.5, $summary->getNetPrice());
        $this->assertEquals(2.5, $summary->getVat());
    }

    public function testSummaryEmpty()
    {
        $this->cart->clear();

        $this->assertEquals(0, $this->cart->getSummary()->getGrossPrice());
        $this->assertEquals(0, $this->cart->getSummary()->getVat());
        $this->assertEquals(0, $this->cart->getSummary()->getNetPrice());
    }

    public function testSummaryWithVatInfo()
    {
        $this->cart->clear();

        $item = new CartItem('SKU', 100);
        $item->setVatIncluded(true);
        $item->setVatRate(20);

        $this->cart->add($item);

        $summary = $this->cart->getSummary();

        $this->assertEquals(100, $summary->getGrossPrice());
        $this->assertEquals(83.33, $summary->getNetPrice(), '', 0.01);
        $this->assertEquals(16.67, $summary->getVat(), '', 0.01);
    }

    public function testSummaryWithExtraFees()
    {
        $cart = new Cart();

        $cart->add(new CartItem('SKU', 10));
        $cart->add(new CartItem('SKU2', 5));
        $cart->getCartFees()->add(new CartFee('5 EUR discount coupon', -5));

        $summary = $cart->getSummary();

        $this->assertEquals(10, $summary->getGrossPrice(), '', 0.01);
        $this->assertEquals(8.33, $summary->getNetPrice(), '', 0.01);
        $this->assertEquals(1.67, $summary->getVat(), '', 0.01);

        // test original prices before discount
        $this->assertEquals(15, $summary->getGrossPriceBeforeFees());
        $this->assertEquals(12.5, $summary->getNetPriceBeforeFees());
        $this->assertEquals(2.5, $summary->getVatBeforeFees());
    }

    public function testSummaryWithMultipleDiscounts()
    {
        $cart = new Cart();

        $cart->add(new CartItem('SKU', 10));
        $cart->add(new CartItem('SKU2', 5));
        $cart->getCartFees()->add(new CartFee('10% discount', '-10%'));
        $cart->getCartFees()->add(new CartFee('5% discount', '-5%'));

        $summary = $cart->getSummary();

        $this->assertEquals(12.83, $summary->getGrossPrice(), '', 0.01);
        $this->assertEquals(10.69, $summary->getNetPrice(), '', 0.01);
        $this->assertEquals(2.14, $summary->getVat(), '', 0.01);
        $this->assertEquals(2.17, 15 - $summary->getGrossPrice(), '', 0.01); // total savings
    }
}