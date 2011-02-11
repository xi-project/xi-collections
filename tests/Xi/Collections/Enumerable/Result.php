<?php
namespace Xi\Collections\Enumerable;

class Result
{
    protected $value;

    public function resolve($value)
    {
        $this->value = $value;
        return $this;
    }

    public function get()
    {
        return $this->value;
    }
}