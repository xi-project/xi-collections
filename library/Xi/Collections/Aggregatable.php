<?php

namespace Xi\Collections;

use Traversable;

/**
 * Extends the Enumerable collection with aggregatable operations.
 *
 * @author Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
interface Aggregatable extends Enumerable
{
    /**
     * Create a new Aggregatable of this type. Implementations should take
     * advantage of late static binding, ie. this method should return an
     * instance of the class that is the receiver of the static method call.
     *
     * @param  array|Traversable $elements
     * @return Aggregatable
     */
    public static function create($elements);

    /**
     * Returns the minimum value in the collection.
     *
     * @return mixed|null
     */
    public function min();

    /**
     * Returns the maximum value in the collection.
     *
     * @return mixed|null
     */
    public function max();

    /**
     * Returns the the sum of values in the collection.
     *
     * @return mixed|null
     */
    public function sum();
}
