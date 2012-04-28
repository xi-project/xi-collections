<?php
namespace Xi\Collections\Collection;
use Xi\Collections\Util;
use Xi\Collections\CollectionView;

class SimpleCollectionView extends SimpleCollection implements CollectionView
{
    /**
     * @var callback(Traversable) $creator
     */
    protected $creator;

    /**
     * @param Traversable $traversable
     * @param callback(Traversable) $creator optional
     */
    public function __construct($traversable, $creator = null)
    {
        $this->traversable = $traversable;
        if (null === $creator) {
            $creator = SimpleCollection::getCreator();
        }
        $this->creator = $creator;
    }

    public function force()
    {
        $creator = $this->creator;
        return $creator($this);
    }

    protected function lazy($iterator)
    {
        return new static($iterator, $this->creator);
    }

    public function apply($callback)
    {
        $self = $this;
        return $this->lazy(new Util\LazyIterator(function() use($self, $callback) {
            return $callback($self);
        }));
    }

    public function take($number)
    {
        if (0 >= $number) {
            return $this->lazy(new \EmptyIterator);
        }
        return $this->lazy(new \LimitIterator($this->getIterator(), 0, $number));
    }

    public function map($callback)
    {
        return $this->lazy($this->getMapIteratorFor($this->getIterator(), $callback));
    }

    public function filter($predicate = null)
    {
        if (null === $predicate) {
            $predicate = function($value) {
                return !empty($value);
            };
        }

        return $this->lazy($this->getFilterIteratorFor($this->getIterator(), $predicate));
    }

    public function concatenate($other)
    {
        $iterator = new \AppendIterator;
        $iterator->append($this->getIterator());
        $iterator->append($this->getIteratorFor($other));
        return $this->lazy($this->getReindexIteratorFor($iterator));
    }

    public function union($other)
    {
        $iterator = new \AppendIterator;
        $iterator->append($this->getIterator());
        $iterator->append($this->getIteratorFor($other));
        return $this->lazy(new Util\LazyIterator(function() use($iterator) {
            return iterator_to_array($iterator);
        }));
    }

    public function flatMap($callback)
    {
        return $this->lazy(new Util\ReindexIterator(new \RecursiveIteratorIterator($this->getFlatMapIteratorFor($this->getIterator(), $callback))));
    }

    protected function getIteratorFor($other)
    {
        return Util\Functions::getIterator($other);
    }

    protected function getReindexIteratorFor($iterator)
    {
        return new Util\ReindexIterator($iterator);
    }

    protected function getFlatMapIteratorFor($iterator, $callback)
    {
        return new Util\FlatMapIterator($iterator, $callback);
    }

    protected function getMapIteratorFor($iterator, $callback)
    {
        return new Util\CallbackMapIterator($iterator, $callback);
    }

    protected function getFilterIteratorFor($iterator, $callback)
    {
        return new Util\CallbackFilterIterator($iterator, $callback);
    }
}