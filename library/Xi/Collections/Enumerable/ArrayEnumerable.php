<?php
namespace Xi\Collections\Enumerable;

use Xi\Collections\Enumerable;

/**
 * Implements the Enumerable operations with native array functions wherever
 * possible. Also adds a few methods that are easy specifically due to the
 * array-based implementation.
 */
class ArrayEnumerable implements Enumerable
{
    /**
     * @var array
     */
    protected $elements;

    /**
     * @param array $elements
     */
    public function __construct($elements)
    {
        $this->elements = $elements;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    public function toArray()
    {
        return $this->elements;
    }

    public function each($callback, $userdata = null)
    {
        array_walk($this->elements, $callback, $userdata);
        return $this;
    }

    public function reduce($callback, $initial = null)
    {
        $result = $initial;
        foreach ($this->elements as $key => $value) {
            $result = $callback($result, $value, $key);
        }
        return $result;
    }

    public function tap($callback)
    {
        $callback($this);
        return $this;
    }

    public function exists($callback)
    {
        foreach ($this->elements as $value) {
            if ($callback($value)) {
                return true;
            }
        }
        return false;
    }

    public function forAll($callback)
    {
        foreach ($this->elements as $value) {
            if (!$callback($value)) {
                return false;
            }
        }
        return true;
    }

    public function find($callback)
    {
        foreach ($this->elements as $value) {
            if ($callback($value)) {
                return $value;
            }
        }
        return null;
    }

    public function first()
    {
        return reset($this->elements);
    }

    public function last()
    {
        return end($this->elements);
    }

    public function countAll($predicate)
    {
        $count = 0;
        foreach ($this as $value) {
            if ($predicate($value)) {
                $count++;
            }
        }
        return $count;
    }

    public function count()
    {
        return count($this->elements);
    }
}
