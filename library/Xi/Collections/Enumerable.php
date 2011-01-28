<?php
namespace Xi\Collections;

/**
 * Implements a set of collection operations relying only on traversability.
 */
interface Enumerable extends \IteratorAggregate
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * Performs an operation once per key-value pair
     *
     * @param callback($value, $key, $userdata) $callback
     * @param mixed $userdata optional
     * @return Collection
     */
    public function each($callback, $userdata = null);

    /**
     * @param callback($accumulator, $value, $key) $callback
     * @param mixed $initial optional
     * @return mixed
     */
    public function reduce($callback, $initial = null);

    /**
     * Returns the first value whose key-value pair satisfies a given predicate
     *
     * @param callback($value, $key) $predicate
     * @return mixed|null
     */
    public function find($predicate);

    /**
     * Checks whether the collection has at least one element satisfying a given
     * predicate
     *
     * @param callback($value, $key) $predicate
     * @return boolean
     */
    public function exists($predicate);

    /**
     * Checks whether all of the elements in the collection satisfy a given
     * predicate
     *
     * @param callback($value, $key) $predicate
     * @return boolean
     */
    public function forAll($predicate);

    /**
     * Returns the first element in the collection
     *
     * @return mixed|null
     */
    public function head();
}