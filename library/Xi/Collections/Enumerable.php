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
     * @param callback($self) $callback
     * @return Enumerable
     */
    public function tap($callback);

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
    public function first();

    /**
     * Returns the last element in the collection.
     *
     * @return mixed|null
     */
    public function last();

    /**
     * Counts the amount of elements in the collection that satisfy a given
     * predicate. If no predicate is given, returns the amount of elements.
     *
     * @param callback($value, $key) $predicate
     * @return int
     */
    public function count($predicate = null);

    /**
     * Joins the string representation of the elements with an optional
     * delimiter
     * 
     * @return string
     */
    public function join($delim = '');
}