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
     * Provides a collection where transformer operations are applied lazily
     *
     * @return CollectionView
     */
    public function view();

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
     * Creates a new Collection with the rest of the elements except first.
     * Does not maintain index associations for numeric keys.
     *
     * @return Collection
     */
    public function rest();

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
     * @param callback($value, $key) $predicate optional
     * @return Collection
     */
    public function filter($predicate = null);

    /**
     * Split a collection into a pair of two collections; one with elements that
     * match a given predicate, the other with the elements that do not.
     * Maintains index associations.
     *
     * @param callable($value, $key) $predicate
     * @return Collection
     */
    public function partition($predicate);

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
     * Get a Collection with just the values from this Collection
     *
     * @return Collection
     */
    public function values();

    /**
     * Get a Collection with the keys from this one as values
     *
     * @return Collection
     */
    public function keys();

    /**
     * Applies a callback for each key-value-pair in the Collection assuming
     * that the callback result value is iterable and returns a new one with
     * values from those iterables. Does not maintain index associations.
     *
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function flatMap($callback);

    /**
     * Reindex the Collection using a given callback
     *
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function indexBy($callback);

    /**
     * Group the values in the Collection into nested Collections according to
     * a given callback
     * 
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function groupBy($callback);

    /**
     * Get a Collection with a key or member property picked from each value
     *
     * @param scalar $key
     * @return Collection
     */
    public function pick($key);
    
    /**
     * Map this Collection by invoking a method on every value
     *
     * @param string $method
     * @return Collection
     */
    public function invoke($method);
    
    /**
     * Flatten nested arrays and Traversables
     *
     * @return Collection
     */
    public function flatten();
    
    /**
     * Get a Collection with only the unique values from this one.
     *
     * @param boolean $strict optional, defaults to true
     * @return Collection
     */
    public function unique($strict = true);

    /**
     * Get a new Collection sorted with a given comparison function
     *
     * @param callback($a, $b) $comparator
     * @return Collection
     */
    public function sortWith($comparator);

    /**
     * Get a new Collection sorted with a given metric
     *
     * @param callback($value, $key) $metric
     * @return Collection
     */
    public function sortBy($metric);

    /**
     * Get a new Collection with given value and optionally key appended.
     * Maintains index associations.
     *
     * @param  mixed      $value
     * @param  mixed      $key
     * @return Collection
     */
    public function add($value, $key = null);

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
     * Returns the sum of values in the collection.
     *
     * @return mixed|null
     */
    public function sum();

    /**
     * Returns the product of values in the collection.
     *
     * @return mixed|null
     */
    public function product();
}
