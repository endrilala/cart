<?php

namespace Maldoinc\Cart\Persistence;

use Maldoinc\Cart\Contract\CartPersistentInterface;

class ArrayPersistenceStrategy implements CartPersistentInterface
{
    protected $key;
    protected $sess;

    public function __construct(array &$sess, $key)
    {
        $this->sess = &$sess;
        $this->key = $key;
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->sess[$this->key] = '';
    }

    public function save($data)
    {
        $this->sess[$this->key] = $data;
    }

    public function load()
    {
        return array_key_exists($this->key, $this->sess) ? $this->sess[$this->key] : '';
    }
}