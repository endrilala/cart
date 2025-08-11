<?php

namespace Maldoinc\Cart\Persistence;

use Maldoinc\Cart\Contract\CartPersistentInterface;

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