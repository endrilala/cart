<?php

namespace endrilala\Cart\Persistence;

use endrilala\Cart\Contract\CartPersistentInterface;

class NullPersistenceStrategy implements CartPersistentInterface
{
    public function save($data)
    {
    }

    public function load()
    {
    }

    public function clear()
    {
    }
}