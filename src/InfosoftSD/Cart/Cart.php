<?php

namespace endrilala\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use endrilala\Cart\Contract\CartPersistentInterface;
use endrilala\Cart\Exception\ItemNotFoundException;
use endrilala\Cart\Persistence\NullPersistenceStrategy;

class Cart
{
    /** @var CartItem[] */
    protected $items = array();

    /** @var ArrayCollection */
    protected $cartFees;

    /** @var int */
    protected $cartItemDefaultPrecision;

    /** @var int */
    private $defaultCartItemPrecision;
    /**
     * @var CartPersistentInterface
     */
    private $intf;

    public function __construct(CartPersistentInterface $intf = null)
    {
        $this->intf = $intf ?: new NullPersistenceStrategy();
        $this->cartFees = new ArrayCollection();

        $this->load();
    }

    /**
     * Load shopping Cart data.
     *
     * Overwrites any existing items the Cart might have
     */
    protected function load()
    {
        $data = $this->intf->load();

        if ($data !== null && trim($data) !== '') {
            $data = unserialize($data);

            $this->items = $data[0];
            $this->cartFees = new ArrayCollection($data[1]);
            $this->setDefaultCartItemPrecision($data[2]);
        }
    }

    /**
     * Save the shopping Cart data
     */
    public function save()
    {
        $this->intf->save(serialize([
            $this->items,
            $this->cartFees->toArray(),
            $this->cartItemDefaultPrecision
        ]));
    }

    /**
     * Clears the shopping Cart
     */
    public function clear()
    {
        $this->items = array();
        $this->cartFees->clear();
    }

    /**
     * Determines whether the shopping Cart is empty or not
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function count()
    {
        return count($this->items);
    }

    /**
     * Returns the item based on it's rowId
     *
     * @param $rowId
     * @return CartItem
     * @throws ItemNotFoundException
     */
    public function get($rowId)
    {
        $this->checkRowid($rowId);

        return $this->items[$rowId];
    }

    /**
     * @param $rowid
     * @throws ItemNotFoundException
     */
    protected function checkRowid($rowid)
    {
        if (!$this->has($rowid)) {
            throw new ItemNotFoundException(sprintf("Item with rowid '%s' not found", $rowid));
        }
    }

    /**
     * Determines whether the Cart has or not the item with specified rowid
     *
     * @param $rowid
     * @return bool
     */
    public function has($rowid)
    {
        return array_key_exists($rowid, $this->items) && $this->items[$rowid] instanceof CartItem;
    }

    /**
     * Returns a copy of the shopping Cart items
     *
     * @return CartItem[]
     */
    public function getItems()
    {
        return array_values($this->items);
    }

    /**
     * Return all the items that match a condition
     *
     * @param callable $callback
     * @return CartItem[]
     */
    public function filter($callback)
    {
        return array_values(array_filter($this->items, $callback));
    }

    public function add(CartItem $item)
    {
        if ($this->getDefaultCartItemPrecision() !== null) {
            $item->setPrecision($this->getDefaultCartItemPrecision());
        }

        $this->items[$item->getRowId()] = $item;

        return $item->getRowId();
    }

    /**
     * Removes the product with the specified identifier from the shopping Cart
     *
     * @param $rowId
     * @throws ItemNotFoundException
     */
    public function remove($rowId)
    {
        $this->checkRowid($rowId);

        unset($this->items[$rowId]);
    }

    /**
     * @return CartSummary
     */
    public function getSummary()
    {
        return CartSummary::getSummary($this->getItems(), $this->getCartFees()->toArray());
    }

    /**
     * @return ArrayCollection
     */
    public function getCartFees()
    {
        return $this->cartFees;
    }

    /**
     * @return int
     */
    public function getDefaultCartItemPrecision()
    {
        return $this->defaultCartItemPrecision;
    }

    /**
     * @param int $defaultCartItemPrecision
     */
    public function setDefaultCartItemPrecision($defaultCartItemPrecision)
    {
        $this->defaultCartItemPrecision = $defaultCartItemPrecision;
    }
}