<?php
namespace Xi\Collections;

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
     * @return \IteratorIterator
     */
    public function getIterator()
    {
        return new \IteratorIterator($this->traversable);
    }
}