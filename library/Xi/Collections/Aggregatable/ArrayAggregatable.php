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
        return !empty($this->_elements) ? min($this->_elements) : null;
    }

    /**
     * Returns the maximum value in the collection.
     *
     * @return mixed|null
     */
    public function max()
    {
        return !empty($this->_elements) ? max($this->_elements) : null;
    }

    /**
     * Returns the the sum of values in the collection.
     *
     * @return mixed|null
     */
    public function sum()
    {
        return array_sum($this->_elements);
    }
}
