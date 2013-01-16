<?php
namespace Xi\Collections\Collection;

use Xi\Collections\Collection,
    Xi\Collections\Util\Functions,
    Xi\Collections\Enumerable\AbstractEnumerable;

/**
 * Provides a trivial implementation of a Collection
 */
abstract class AbstractCollection extends AbstractEnumerable implements Collection
{
    public static function getCreator()
    {
        return Functions::getCallback(get_called_class(), 'create');
    }

    public function view()
    {
        return new SimpleCollectionView($this, static::getCreator());
    }
    
    public function apply($callback)
    {
        return static::create($callback($this));
    }

    public function take($number)
    {
        $results = array();
        foreach ($this as $key => $value) {
            if ($number-- <= 0) {
                break;
            }
            $results[$key] = $value;
        }
        return static::create($results);
    }

    public function map($callback)
    {
        $results = array();
        foreach ($this as $key => $value) {
            $results[$key] = $callback($value, $key);
        }
        return static::create($results);
    }

    public function filter($predicate = null)
    {
        if (null === $predicate) {
            $predicate = function($value) {
                return !empty($value);
            };
        }

        $results = array();
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                $results[$key] = $value;
            }
        }
        return static::create($results);
    }

    public function concatenate($other)
    {
        $results = array();
        foreach ($this as $value) {
            $results[] = $value;
        }
        foreach ($other as $value) {
            $results[] = $value;
        }
        return static::create($results);
    }

    public function union($other)
    {
        $results = $this->toArray();
        foreach ($other as $key => $value) {
            $results[$key] = $value;
        }
        return static::create($results);
    }

    public function values()
    {
        $results = array();
        foreach ($this as $value) {
            $results[] = $value;
        }
        return static::create($results);
    }

    public function keys()
    {
        $results = array();
        foreach ($this as $key => $value) {
            $results[] = $key;
        }
        return static::create($results);
    }

    public function flatMap($callback)
    {
        return $this->apply(Functions::flatMap($callback));
    }

    public function indexBy($callback)
    {
        return $this->apply(Functions::indexBy($callback));
    }

    public function groupBy($callback)
    {
        return $this->apply(Functions::groupBy($callback, $this->getCreator()));
    }

    public function pick($key)
    {
        return $this->map(Functions::pick($key));
    }
    
    public function invoke($method)
    {
        return $this->map(Functions::invoke($method));
    }
    
    public function flatten()
    {
        return $this->apply(Functions::flatten());
    }
    
    public function unique($strict = true)
    {
        return $this->apply(Functions::unique($strict));
    }

    public function sortWith($comparator)
    {
        return $this->apply(Functions::sortWith($comparator));
    }

    public function sortBy($metric)
    {
        return $this->apply(Functions::sortBy($metric));
    }

    /**
     * {@inheritdoc}
     */
    public function add($value, $key = null)
    {
        $results = $this->toArray();

        if ($key === null) {
            $results[] = $value;
        } else {
            $results[$key] = $value;
        }

        return static::create($results);
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        $min = null;

        foreach ($this as $value) {
            if ($min === null || $value < $min) {
                $min = $value;
            }
        }

        return $min;
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        $max = null;

        foreach ($this as $value) {
            if ($max === null || $value > $max) {
                $max = $value;
            }
        }

        return $max;
    }

    /**
     * {@inheritdoc}
     */
    public function sum()
    {
        $sum = null;

        foreach ($this as $value) {
            $sum += $value;
        }

        return $sum;
    }

    /**
     * {@inheritdoc}
     */
    public function product()
    {
        $product = null;

        foreach ($this as $value) {
            if ($product === null) {
                $product = $value;
            } else {
                $product *= $value;
            }
        }

        return $product;
    }
}
