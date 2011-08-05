<?php
namespace Xi\Collections\Collection;

use Xi\Collections\Collection,
    Xi\Collections\Functions,
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
}