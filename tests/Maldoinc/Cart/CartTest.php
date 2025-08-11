<?php

use endrilala\Cart\Cart;
use endrilala\Cart\CartFee;
use endrilala\Cart\CartItem;

class CartTest extends PHPUnit_Framework_TestCase
{
    /** @var Cart */
    protected $cart;

    protected function setUp()
    {
        $this->cart = new Cart();
    }

    public function testClear()
    {
        $cart = new Cart();

        $cart->add(new CartItem('AB123', 100));
        $cart->add(new CartItem('AB123', 100));
        $cart->getCartFees()->add(new CartFee('Test', 5));

        $cart->clear();

        $this->assertCount(0, $cart->getCartFees());
        $this->assertEquals(0, $cart->count());
    }

    public function testFilter()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem('AB12', 100));
        $this->cart->add(new CartItem('ABC', 1001));

        $items = $this->cart->filter(function (CartItem $item) {
            return $item->getIdentifier() === 'ABC';
        });

        $this->assertCount(1, $items);
        $this->assertEquals(true, isset($items[0]));
    }

    public function testIsEmpty()
    {
        $this->cart->clear();
        $this->assertEquals(true, $this->cart->isEmpty());
    }

    public function testGetItems()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem('A', 3.14));
        $items = $this->cart->getItems();

        $this->assertEquals(true, reset($items) instanceof CartItem);
    }

    public function testGet()
    {
        $this->cart->clear();

        $a = $this->cart->add(new CartItem('A', 3.14));
        $this->cart->add(new CartItem('A', 4.14, 1));

        $item = $this->cart->get($a);
        $this->assertEquals(3.14, $item->getPriceInfo()->getGrossPrice(), '', 0.001);
    }

    /**
     * @expectedException \endrilala\Cart\Exception\ItemNotFoundException
     */
    public function testGetNonExistingItem()
    {
        $this->cart->get("Item that doesn't exist!");
    }

    public function testUpdate()
    {
        $this->cart->clear();

        $itemA = new CartItem('A', 1);
        $itemB = new CartItem('B', 3, 1);

        $a = $this->cart->add($itemA);
        $this->cart->add($itemB);

        $itemA->setQuantity(3)->setData(array('x' => 'y'));

        $this->assertEquals(6, $this->cart->getSummary()->getGrossPrice());

        $data = $this->cart->get($a)->getData();
        $this->assertEquals('y', $data['x']);
    }

    public function testRemove()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem(101, 100, 1.5));
        $first = $this->cart->add(new CartItem('XXYZ', 100, 1.5));
        $this->cart->add(new CartItem('ITEMCODE', 100, 1.5));

        $this->cart->remove($first);
        $this->assertEquals($this->cart->count(), 2);
    }

    public function testCartItemPriceDetails()
    {
        $item = new CartItem('A001', 10, 1);
        $item->setVatRate(20);

        $this->assertEquals(8.33, $item->getPriceInfo()->getNetPrice(), '', 0.01);
        $this->assertEquals(1.67, $item->getPriceInfo()->getVat(), '', 0.01);
        $this->assertEquals(10, $item->getPriceInfo()->getGrossPrice());
    }

    public function testCartDefaultPrecision()
    {
        $cart = new Cart();
        $cart->setDefaultCartItemPrecision(1);
        $item = new CartItem('CODE', 100);
        $cart->add($item);

        $this->assertEquals(1, $cart->getDefaultCartItemPrecision());
        $this->assertEquals($item->getPrecision(), $cart->getDefaultCartItemPrecision());
        $this->assertEquals(83.3, $cart->getSummary()->getNetPrice(), '', 0.1);
        $this->assertEquals(16.7, $cart->getSummary()->getVat(), '', 0.1);
        $this->assertEquals(100, $cart->getSummary()->getGrossPrice(), '', 0.1);
    }
}