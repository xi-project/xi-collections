<?php
namespace Xi\Collections;

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

    public function filter($callback = null)
    {
        // Passing null to array_filter results in error, but omitting the second argument is ok
        $result = (null === $callback) ? array_filter($this->_elements) : array_filter($this->_elements, $callback);
        return static::create($result);
    }

    public function map($callback)
    {
        $result = array();
        foreach ($this->_elements as $key => $value) {
            $result[$key] = $callback($value, $key);
        }
        return static::create($result);
    }

    public function concatenate($other)
    {
        return static::create(array_merge($this->values(), $other->values()));
    }

    public function union($other)
    {
        return static::create($this->_elements + $other->toArray());
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