<?php
namespace Xi\Collections\Util;

class CallbackMapIterator extends \IteratorIterator
{
    /**
     * @var callback($value, $key)
     */
    protected $callback;

    public function __construct(\Traversable $iterator, $callback)
    {
        parent::__construct($iterator);
        $this->callback = $callback;
    }

    public function current()
    {
        $callback = $this->callback;
        return $callback(parent::current(), $this->key());
    }
}