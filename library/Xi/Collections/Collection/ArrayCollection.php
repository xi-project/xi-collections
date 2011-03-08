<?php
namespace Xi\Collections\Collection;

use Xi\Collections\Collection,
    Xi\Collections\Enumerable\ArrayEnumerable;

/**
 * Implements the Collection operations with native array functions wherever
 * possible. Also extends the basic Collection interface with some operations
 * specific to the native array functions, eg. merge with its curious corner
 * cases.
 */
class ArrayCollection extends ArrayEnumerable implements Collection
{
    /**
     * @param array|\Traversable $elements
     * @return Collection
     */
    public static function create($elements)
    {
        if ($elements instanceof \Traversable) {
            $elements = iterator_to_array($elements, true);
        }
        return new static((array) $elements);
    }
    
    public function apply($callback)
    {
        return static::create($callback($this));
    }

    public function take($number)
    {
        $result = array();
        if ($number > 0) {
            $result = array_slice($this->_elements, 0, $number, true);
        }
        return static::create($result);
    }

    public function filter($callback = null)
    {
        // Passing null to array_filter results in error, but omitting the second argument is ok
        $result = (null === $callback) ? array_filter($this->_elements) : array_filter($this->_elements, $callback);
        return static::create($result);
    }

    public function map($callback)
    {
        return static::create(array_map($callback, $this->_elements, array_keys($this->_elements)));
    }

    public function concatenate($other)
    {
        $left = array_values($this->_elements);
        $right = array_values($other->toArray());
        return static::create(array_merge($left, $right));
    }

    public function union($other)
    {
        return static::create($other->toArray() + $this->_elements);
    }

    public function values()
    {
        return static::create(array_values($this->_elements));
    }

    public function keys()
    {
        return static::create(array_keys($this->_elements));
    }

    /**
     * @return ArrayCollection
     */
    public function unique()
    {
        return static::create(array_unique($this->_elements));
    }

    /**
     * @return ArrayCollection
     */
    public function reverse()
    {
        return static::create(array_reverse($this->_elements));
    }

    /**
     * @param Collection $other
     * @return ArrayCollection
     */
    public function merge(Collection $other)
    {
        return static::create(array_merge($this->_elements, $other->toArray()));
    }
}