<?php
namespace Xi\Collections\Enumerable;

class OuterEnumerableTest extends AbstractEnumerableTest
{
    public function getEnumerable($elements = array())
    {
        return new OuterEnumerable(new ArrayEnumerable($elements));
    }
}