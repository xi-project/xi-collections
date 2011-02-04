<?php
namespace Xi\Collections;

/**
 * Provides a trivial implementation of a Collection
 */
abstract class AbstractCollection extends AbstractEnumerable implements Collection
{
    public function apply($callback)
    {
        return static::create($callback($this));
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
}