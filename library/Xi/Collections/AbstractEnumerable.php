<?php
namespace Xi\Collections;

abstract class AbstractEnumerable implements Enumerable
{
    public function toArray()
    {
        return iterator_to_array($this->getIterator());
    }

    public function each($callback, $userdata = null)
    {
        foreach ($this as $key => $value) {
            $callback($value, $key, $userdata);
        }
        return $this;
    }

    public function reduce($callback, $initial = null)
    {
        $result = $initial;
        foreach ($this as $key => $value) {
            $result = $callback($result, $value, $key);
        }
        return $result;
    }

    public function find($predicate)
    {
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }
    }

    public function exists($predicate)
    {
        foreach ($this as $key => $value) {
            if ($predicate($value, $key)) {
                return true;
            }
        }
    }

    public function forAll($predicate)
    {
        foreach ($this as $key => $value) {
            if (!$predicate($value, $key)) {
                return false;
            }
        }
        return true;
    }
    
    public function head()
    {
        foreach ($this as $value) {
            return $value;
        }
    }
}
