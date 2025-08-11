<?php

namespace InfosoftSD\Cart\Contract;

/**
 * Allows persisting shopping the ShoppingCart class.
 *
 * NB: this is not handling a collection class. All it asks to do is to store and retrieve a string which
 * represents the Cart data. Serialization itself is handled by php's built-in mechanisms and is not up to
 * the interface to decide how to do it.
 *
 * Interface ShoppingCartPersistentInterface
 * @package InfosoftSD\Cart
 */
interface CartPersistentInterface
{
    /**
     *
     * @param string $data
     * @return void
     */
    public function save($data);

    /**
     * @return string
     */
    public function load();

    /**
     * Destroy all data
     *
     * @return void
     */
    public function clear();
}