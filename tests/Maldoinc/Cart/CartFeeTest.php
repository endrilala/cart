<?php

namespace Maldoinc\Cart;

class CartFeeTest extends \PHPUnit_Framework_TestCase
{
    public function isPercentValueGetAmountDataProvider()
    {
        return array(
            array('-5%', 100, 95),
            array(10, 50, 60),
            array('+30%', 300, 390)
        );
    }

    /**
     * @dataProvider isPercentValueGetAmountDataProvider
     * @param $value
     * @param $subtotal
     * @param $newSubtotal
     */
    public function testIsPercentValueGetAmountShouldSucceed($value, $subtotal, $newSubtotal)
    {
        $this->assertEquals($newSubtotal, (new CartFee('Test case', $value))->getAmount($subtotal));
    }

    public function isPercentValueErrorDataProvider()
    {
        return array(
            array('Description', null),
            array('Description', array()),
            array('Description', new \stdClass()),
            array('Description', '-$10.00'),
        );
    }

    /**
     * @dataProvider isPercentValueErrorDataProvider
     * @expectedException \Maldoinc\Cart\Exception\InvalidCartFeeValueException
     * @param $name
     * @param $value
     */
    public function testIsPercentValueShouldThrowException($name, $value)
    {
        (new CartFee($name, $value))->getAmount(0);
    }

    public function testGetterSetters()
    {
        $fee = new CartFee('Old', 5);
        $this->assertEquals('Old', $fee->getDescription());
        $this->assertEquals(5, $fee->getValue());

        $fee->setDescription('New');
        $fee->setValue(15);
        $this->assertEquals('New', $fee->getDescription());
        $this->assertEquals(15, $fee->getValue());
    }
}
