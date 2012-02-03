<?php
namespace Xi\Collections\Enumerable;

use Xi\Collections\Util\Functions;

/**
 * Provides a trivial concrete implementation of an Enumerable. Accepts any
 * traversable object or array as a constructor argument.
 */
class SimpleEnumerable extends AbstractEnumerable
{
    /**
     * @var \Traversable|array
     */
    protected $traversable;

    /**
     * @param \Traversable|array $traversable
     */
    public function __construct($traversable)
    {
        $this->traversable = $traversable;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return Functions::getIterator($this->traversable);
    }
}