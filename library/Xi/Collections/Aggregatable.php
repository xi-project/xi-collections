<?php

namespace Xi\Collections;

/**
 * Extends the Enumerable collection with aggregatable operations.
 *
 * @author Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
interface Aggregatable extends Enumerable
{
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
