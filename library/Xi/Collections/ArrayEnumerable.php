<?php
namespace Xi\Collections;

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
    protected $_elements;

    /**
     * @param array $elements
     */
    public function __construct($elements)
    {
        $this->_elements = $elements;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->_elements);
    }

    public function toArray()
    {
        return $this->_elements;
    }

    public function each($callback, $userdata = null)
    {
        array_walk($this->_elements, $callback, $userdata);
        return $this;
    }

    public function reduce($callback, $initial = null)
    {
        $result = $initial;
        foreach ($this->_elements as $key => $value) {
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
        foreach ($this->_elements as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }
        return false;
    }

    public function forAll($callback)
    {
        foreach ($this->_elements as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }
        return true;
    }

    public function find($callback)
    {
        foreach ($this->_elements as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        return null;
    }

    public function first()
    {
        return reset($this->_elements);
    }

    public function last()
    {
        return end($this->_elements);
    }

    public function count($predicate = null)
    {
        if (null === $predicate) {
            return count($this->_elements);
        }

        $count = 0;
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                $count++;
            }
        }
        return $count;
    }
}