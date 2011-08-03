<?php
namespace Xi\Collections;

/**
 * Extends the Enumerable collection operations to a superset that includes
 * operations that yield other collections in return. This means Collections
 * can be transformed into other Collections.
 * 
 * As a principle, Collection operations should be invariant on the Collection's
 * type - ie. methods should return an instance of the type of Collection that
 * the method call was made on. This principle should only be rejected case by
 * case and for clear, documented reasons.
 */
interface Collection extends Enumerable
{
    /**
     * Create a new Collection of this type. Implementations should take
     * advantage of late static binding, ie. this method should return an
     * instance of the class that is the receiver of the static method call.
     *
     * @param array|Traversable $elements
     * @return Collection
     */
    public static function create($elements);
    
    /**
     * Get a callback that references the static create method for the class
     * that is the receiver of the static method call.
     * 
     * @return Closure
     */
    public static function getCreator();

    /**
     * Creates a new Collection of this type from the output of a given callback
     * that takes this Collection as its argument.
     *
     * @param callback($collection) $callback
     * @return Collection
     */
    public function apply($callback);

    /**
     * Creates a new Collection with up to $number first elements from this one.
     * Maintains index associations.
     * 
     * @param int $number
     * @return Collection
     */
    public function take($number);

    /**
     * Applies a callback for each value-key-pair in the Collection and returns
     * a new one with values replaced by the return values from the callback.
     * Maintains index associations.
     * 
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function map($callback);

    /**
     * Creates a collection with the values of this collection that match a
     * given predicate. If the predicate is omitted, the values will simply be
     * checked for truthiness. Maintains index associations.
     *
     * Implementors may provide the callback with an optional $key argument at
     * their discretion.
     *
     * @param callback($value) $predicate optional
     * @return Collection
     */
    public function filter($predicate = null);

    /**
     * Creates a Collection with elements from this and another one. Does not
     * maintain index associations.
     *
     * @param Collection $other
     * @return Collection
     */
    public function concatenate($other);

    /**
     * Creates a Collection with key-value pairs in the $other Collection
     * overriding ones in $this Collection. Maintains index associations.
     *
     * @param Collection $other
     * @return Collection
     */
    public function union($other);

    /**
     * @return Collection
     */
    public function values();

    /**
     * @return Collection
     */
    public function keys();

    /**
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function indexBy($callback);

    /**
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function groupBy($callback);

    /**
     * @param scalar $key
     * @return Collection
     */
    public function pick($key);
    
    /**
     * @return Collection
     */
    public function flatten();
}