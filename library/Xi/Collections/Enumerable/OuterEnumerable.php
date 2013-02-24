<?php
namespace Xi\Collections\Enumerable;

use Xi\Collections\Enumerable;

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
        $this->enumerable = $enumerable;
    }

    /**
     * @return Enumerable
     */
    public function getInnerEnumerable()
    {
        return $this->enumerable;
    }

    public function toArray()
    {
        return $this->enumerable->toArray();
    }

    public function tap($callback)
    {
        $callback($this);

        return $this;
    }

    public function each($callback, $userdata = null)
    {
        $this->enumerable->each($callback, $userdata);

        return $this;
    }

    public function reduce($callback, $initial = null)
    {
        return $this->enumerable->reduce($callback, $initial);
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

    public function count()
    {
        return $this->enumerable->count();
    }

    public function countAll($predicate)
    {
        return $this->enumerable->countAll($predicate);
    }
}
