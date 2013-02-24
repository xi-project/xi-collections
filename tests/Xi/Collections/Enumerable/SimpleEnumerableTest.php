<?php
namespace Xi\Collections\Enumerable;

class SimpleEnumerableTest extends AbstractEnumerableTest
{
    public function getEnumerable($elements = array())
    {
        return new SimpleEnumerable($elements);
    }
}
