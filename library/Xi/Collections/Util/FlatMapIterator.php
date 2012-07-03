<?php
namespace Xi\Collections\Util;

class FlatMapIterator extends \IteratorIterator implements \RecursiveIterator
{
    protected $callback;

    public function __construct($iterator, $callback = null)
    {
        parent::__construct($iterator);
        $this->callback = $callback;
    }

    public function hasChildren()
    {
        return null !== $this->callback;
    }

    public function getChildren()
    {
        $callback = $this->callback;
        $children = $callback($this->current());
        return new static($this->getIteratorFor($children));
    }

    protected function getIteratorFor($other)
    {
        return Functions::getIterator($other);
    }
}