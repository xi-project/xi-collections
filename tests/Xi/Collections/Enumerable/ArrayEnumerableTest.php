<?php
namespace Xi\Collections\Enumerable;

class ArrayEnumerableTest extends AbstractEnumerableTest
{
    public function getEnumerable($elements = array())
    {
        return new ArrayEnumerable($elements);
    }
}