<?php

use Doctrine\Common\Collections\ArrayCollection;
use endrilala\Cart\Cart;
use endrilala\Cart\CartFee;
use endrilala\Cart\CartItem;
use endrilala\Cart\Persistence\ArrayPersistenceStrategy;
use endrilala\Cart\Persistence\FilePersistenceStrategy;

class PersistentShoppingCartTest extends PHPUnit_Framework_TestCase
{
    protected $mock = array('shopping_cart_test' => '');

    protected function getTestFilename()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'shopping_cart_test';
    }

    protected function removeTestFile()
    {
        if (is_file($this->getTestFilename())) {
            unlink($this->getTestFilename());
        }
    }

    public function sessionPersistenceDataProvider()
    {
        return array(
            array(function () {
                return new Cart(new FilePersistenceStrategy($this->getTestFilename()));
            }),
            array(function () {
                return new Cart(new ArrayPersistenceStrategy($this->mock, 'shopping_cart_test'));
            })
        );
    }

    public function setUp()
    {
        $this->removeTestFile();
    }

    public function tearDown()
    {
        $this->removeTestFile();
    }

    /**
     * @dataProvider sessionPersistenceDataProvider
     * @param $factory
     */
    public function testSessionPersistence($factory)
    {
        /** @var $a Cart */
        $a = $factory();
        $rowid = $a->add(new CartItem('A', 1, 2));
        $a->getCartFees()->add(new CartFee('5EUR late fee', 5));
        $a->save();

        /** @var $b Cart */
        $b = $factory();

        $this->assertTrue($b->getCartFees() instanceof ArrayCollection);

        $this->assertEquals($a->count(), $b->count());
        $this->assertEquals($a->getSummary()->getGrossPrice(), $b->getSummary()->getGrossPrice());

        $item = $b->get($rowid);
        $this->assertEquals($rowid, $item->getRowId());

        $item->setQuantity(10);
        $b->save();
        $this->assertEquals(15, $b->getSummary()->getGrossPrice());
        $b->remove($rowid);
        $this->assertEquals(0, $b->count());

        $a->clear();
    }
}