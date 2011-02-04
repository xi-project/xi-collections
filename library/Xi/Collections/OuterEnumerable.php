<?php
namespace Xi\Collections;

/**
 * Forwards all operations to another Enumerable provided at construction. In
 * case of operations providing a fluent API, eg. tap(), maintains the outer
 * enumerable as the return value.
 */
class OuterEnumerable implements Enumerable
{
    /**
     * @var Enumerable
     */
    protected $enumerable;

    /**
     * @param Enumerable $enumerable
     */
    public function __construct($enumerable)
    {
        $this->enumerable;
    }

    /**
     * @return Enumerable
     */
    public function getInnerEnumerable()
    {
        return $this->enumerable;
    }

    public function tap($callback)
    {
        $this->enumerable->tap($callback);
        return $this;
    }

    public function each($callback, $userdata = null)
    {
        $this->enumerable->each($callback, $userdata);
        return $this;
    }

    public function exists($predicate)
    {
        return $this->enumerable->exists($predicate);
    }

    public function find($predicate)
    {
        return $this->enumerable->find($predicate);
    }

    public function forAll($predicate)
    {
        return $this->enumerable->forAll($predicate);
    }

    public function getIterator()
    {
        return $this->enumerable->getIterator();
    }

    public function first()
    {
        return $this->enumerable->first();
    }

    public function last()
    {
        return $this->enumerable->last();
    }

    public function count($predicate = null)
    {
        return $this->enumerable->count($predicate);
    }
}
