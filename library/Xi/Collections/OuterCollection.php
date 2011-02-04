<?php
namespace Xi\Collections;

/**
 * Forwards all operations to another Collection provided at construction. In
 * case of operations that return other Collection objects, wraps the results
 * with new instances of the class.
 */
class OuterCollection extends OuterEnumerable implements Collection
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param Collection $collection
     */
    public function __construct($collection)
    {
        parent::__construct($collection);
        $this->collection = $collection;
    }

    /**
     * @return Collection
     */
    public function getInnerCollection()
    {
        return $this->collection;
    }

    /**
     * @param Collection $elements
     * @return OuterCollection
     * @throws InvalidArgumentException
     */
    public static function create($elements)
    {
        if ($elements instanceof Collection) {
            return new static($elements);
        }
        throw new \InvalidArgumentException("OuterCollection can only wrap Collection instances");
    }

    public function apply($callback)
    {
        return static::create($this->collection->apply($callback));
    }

    public function map($callback)
    {
        return static::create($this->collection->map($callback));
    }

    public function filter($predicate = null)
    {
        return static::create($this->collection->filter($predicate));
    }

    public function concatenate($other)
    {
        return static::create($this->collection->concatenate($other));
    }

    public function union($other)
    {
        return static::create($this->collection->union($other));
    }

    public function values()
    {
        return static::create($this->collection->values());
    }

    public function keys()
    {
        return static::create($this->collection->keys());
    }
}