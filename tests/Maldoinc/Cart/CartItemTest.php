<?php

use Maldoinc\Cart\CartItem;

class CartItemTest extends PHPUnit_Framework_TestCase
{
    public function testCartItemPriceDetails()
    {
        $item = new CartItem('A001', 10, 1);
        $item->setVatRate(20);

        $this->assertEquals(8.33, $item->getPriceInfo()->getNetPrice(), '', 0.01);
        $this->assertEquals(1.67, $item->getPriceInfo()->getVat(), '', 0.01);
        $this->assertEquals(10, $item->getPriceInfo()->getGrossPrice());
    }

    public function testProperties()
    {
        $item = (new CartItem('TSHIRT', 133))
            ->setVatRate(10)
            ->setQuantity(14)
            ->setData(array('size' => 'L'))
            ->setVatIncluded(false);

        $data = $item->getData();

        $this->assertEquals(133, $item->getPriceInfo()->getNetPricePerUnit());
        $this->assertEquals(14, $item->getQuantity());
        $this->assertEquals('L', $data['size']);
        $this->assertEquals('TSHIRT', $item->getIdentifier());
        $this->assertEquals(10, $item->getVatRate());

        $item->setPrice(20);
        $this->assertEquals(20, $item->getPriceInfo()->getNetPricePerUnit());

        $this->assertEquals(false, $item->isVatIncluded());
    }

    public function testUpdateDataReturnsArrayReference()
    {
        $item = new CartItem('CODE', 100, 1, ['size' => 'M']);
        $item->getData()['color'] = 'red';

        $this->assertEquals('red', $item->getData()['color']);
    }
}