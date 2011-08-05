<?php
namespace Xi\Collections\Collection;

use Xi\Collections\Collection,
    Xi\Collections\Functions,
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
    
    public static function getCreator()
    {
        return Functions::getCallback(get_called_class(), 'create');
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
        // Passing null to array_filter results in error, but omitting the second argument is ok.
        $result = (null === $callback)
            ? array_filter($this->_elements)
            // array_filter only provides values; adding keys manually
            : array_filter($this->_elements, $this->addKeyArgument($callback));
        return static::create($result);
    }

    public function map($callback)
    {
        // Providing keys to the callback manually, because index associations
        // are not maintained when array_map is called with multiple arrays.
        return static::create(array_map($this->addKeyArgument($callback), $this->_elements));
    }
    
    /**
     * Wraps a callback that accepts a value-key pair as its arguments into a
     * callback that only accepts the value and retrieves a key from the
     * elements manually for each call. Can be used when a function that accepts
     * a callback and iterates through the elements will only provide values to
     * the passed callback.
     * 
     * TODO: Perform analysis on whether it would be altogether more sensible to
     * implement filter and map manually, if providing a consistent interface
     * while taking advantage of PHP functions means resorting tricks like this.
     * 
     * @param callback($value, $key) $callback
     * @return callback($value)
     */
    private function addKeyArgument($callback)
    {
        $values = $this->_elements;
        return function($value) use($callback, $values) {
            list($key) = each($values);
            return $callback($value, $key);
        };
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

    public function indexBy($callback)
    {
        return $this->apply(Functions::indexBy($callback));
    }

    public function groupBy($callback)
    {
        return $this->apply(Functions::groupBy($callback, $this->getCreator()));
    }

    public function pick($key)
    {
        return $this->map(Functions::pick($key));
    }
    
    public function invoke($method)
    {
        return $this->map(Functions::invoke($method));
    }
    
    public function flatten()
    {
        return $this->apply(Functions::flatten());
    }
    
    public function unique($strict = true)
    {
        if (false === $strict) {
            return static::create(array_unique($this->_elements));
        }
        return $this->apply(Functions::unique($strict));
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