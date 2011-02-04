<?php
namespace Xi\Collections;

/**
 * Extends the Enumerable collection operations to a superset that includes all
 * operations that yield other collections in return. This means Collections
 * can be transformed into other Collections.
 */
interface Collection extends Enumerable
{
    /**
     * Create a new Collection of this type. Implementations should take
     * advantage of late static binding.
     *
     * @param array|Traversable $elements
     * @return Collection
     */
    public static function create($elements);

    /**
     * Creates a new Collection of this type from the output of a given callback
     * that takes this Collection as its argument.
     *
     * @param callback($collection) $callback
     * @return Collection
     */
    public function apply($callback);

    /**
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function map($callback);

    /**
     * Creates a collection with the values of this collection that match a
     * given predicate. If the predicate is omitted, the values will simply be
     * checked for truthiness.
     *
     * Implementors may provide the callback with an optional $key argument at
     * their discretion.
     *
     * @param callback($value) $predicate optional
     * @return Collection
     */
    public function filter($predicate = null);

    /**
     * Creates a Collection with elements from this and another one. Is not
     * required to maintain index associations.
     *
     * @param Collection $other
     * @return Collection
     */
    public function concatenate($other);

    /**
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
}