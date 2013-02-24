<?php

namespace Xi\Collections\Util;

/**
 * An iterator that retrieves its contents for use via a callback before being
 * used for the first time.
 */
class LazyIterator extends \AppendIterator
{
    protected $callback;
    private $initialized = false;

    /**
     * @param callable $callback should return an Iterator
     */
    public function __construct($callback)
    {
        parent::__construct();
        $this->callback = $callback;
    }

    public function rewind()
    {
        if (!$this->initialized) {
            $callback = $this->callback;
            $this->append($this->getIteratorFor($callback()));
            $this->initialized = true;
        }
        parent::rewind();
    }

    protected function getIteratorFor($value)
    {
        return Functions::getIterator($value);
    }
}
