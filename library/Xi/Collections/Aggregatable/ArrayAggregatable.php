<?php

namespace Xi\Collections\Aggregatable;

use Xi\Collections\Aggregatable,
    Xi\Collections\Enumerable\ArrayEnumerable,
    Traversable;

/**
 * Implements the Aggregatable operations with native array functions.
 *
 * @author Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
class ArrayAggregatable extends ArrayEnumerable implements Aggregatable
{
    /**
     * @param  array|Traversable $elements
     * @return Aggregatable
     */
    public static function create($elements)
    {
        if ($elements instanceof Traversable) {
            $elements = iterator_to_array($elements, true);
        }

        return new static((array) $elements);
    }

    /**
     * Returns the minimum value in the collection.
     *
     * @return mixed|null
     */
    public function min()
    {
        return $this->applyOrNull('min');
    }

    /**
     * Returns the maximum value in the collection.
     *
     * @return mixed|null
     */
    public function max()
    {
        return $this->applyOrNull('max');
    }

    /**
     * Returns the the sum of values in the collection.
     *
     * @return mixed|null
     */
    public function sum()
    {
        return $this->applyOrNull('array_sum');
    }

    /**
     * Returns the value of the callback applied to the elements or null if
     * there are no elements.
     *
     * @param callback($elements) $callback
     * @return mixed|null
     */
    private function applyOrNull($callback)
    {
        return !empty($this->_elements) ? $callback($this->_elements) : null;
    }
}
