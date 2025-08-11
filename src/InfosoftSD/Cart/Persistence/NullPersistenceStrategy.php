<?php

namespace InfosoftSD\Cart\Persistence;

use InfosoftSD\Cart\Contract\CartPersistentInterface;

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