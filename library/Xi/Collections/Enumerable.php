<?php

namespace Xi\Collections;

/**
 * Implements a set of collection operations relying only on traversability.
 */
interface Enumerable extends \IteratorAggregate, \Countable
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * Calls a provided callback with this object as a parameter. Can be used
     * for inspecting the object's state eg. for debugging purposes.
     *
     * @param  callable($self) $callback
     * @return Enumerable
     */
    public function tap($callback);

    /**
     * Performs an operation once per key-value pair
     *
     * @param  callable($value, $key, $userdata) $callback
     * @param  mixed      $userdata optional
     * @return Collection
     */
    public function each($callback, $userdata = null);

    /**
     * Uses a given callback to reduce the collection's elements to a single
     * value, starting from a provided initial value.
     *
     * @param  callable($accumulator, $value, $key) $callback
     * @param  mixed $initial optional
     * @return mixed
     */
    public function reduce($callback, $initial = null);

    /**
     * Returns the first value that satisfies a given predicate
     *
     * @param  callable($value) $predicate
     * @return mixed|null
     */
    public function find($predicate);

    /**
     * Checks whether the collection has at least one element satisfying a given
     * predicate
     *
     * @param  callable($value) $predicate
     * @return boolean
     */
    public function exists($predicate);

    /**
     * Checks whether all of the elements in the collection satisfy a given
     * predicate
     *
     * @param  callable($value) $predicate
     * @return boolean
     */
    public function forAll($predicate);

    /**
     * Counts the amount of elements in the collection that satisfy a given
     * predicate
     *
     * @param  callable($value) $predicate
     * @return int
     */
    public function countAll($predicate);

    /**
     * Returns the first element in the collection
     *
     * @return mixed|null
     */
    public function first();

    /**
     * Returns the last element in the collection.
     *
     * @return mixed|null
     */
    public function last();
}
