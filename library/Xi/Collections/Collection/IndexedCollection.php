<?php
namespace Xi\Collections\Collection;

/**
 * A collection decorator that implements operations specifically dealing with
 * key-value relations in the inner collection.
 */
class IndexedCollection extends OuterCollection
{
    /**
     * If the given argument is not a Collection, defaults to an ArrayCollection
     * implementation. Allows the use of IndexedCollection directly without
     * explicitly declaring an implementation for the inner Collection.
     *
     * @param array|\Traversable|Collection $elements
     * @return IndexedCollection
     */
    public static function create($elements)
    {
        if (!($elements instanceof Collection)) {
            $elements = ArrayCollection::create($elements);
        }
        return parent::create($elements);
    }

    /**
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function indexBy($callback)
    {
        $results = array();
        foreach ($this as $key => $value) {
            $results[$callback($value, $key)] = $value;
        }
        return static::create($results);
    }

    /**
     * @param callback($value, $key) $callback
     * @return Collection
     */
    public function groupBy($callback)
    {
        $results = array();
        foreach ($this as $key => $value) {
            $results[$callback($value, $key)][] = $value;
        }
        foreach ($results as $key => $value) {
            $results[$key] = static::create($value);
        }
        return static::create($results);
    }

    /**
     * @param scalar $key
     * @return Collection
     */
    public function pick($key)
    {
        $picker = function($input) use($key) {
            switch(true) {
                case $input instanceof ArrayAccess:
                case is_array($input):  return $input[$key];
                case is_object($input): return $input->$key;
                default:                return null;
            }
        };
        return $this->map($picker);
    }
}