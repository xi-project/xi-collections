<?php
namespace Xi\Collections\Collection;

/**
 * Provides a trivial concrete implementation of a Collection. Accepts any
 * traversable object or array as a constructor argument.
 */
class SimpleCollection extends AbstractCollection
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

    public function getIterator()
    {
        if (is_array($this->traversable)) {
            return new \ArrayIterator($this->traversable);
        }
        return new \IteratorIterator($this->traversable);
    }

    public static function create($elements)
    {
        return new static($elements);
    }
}