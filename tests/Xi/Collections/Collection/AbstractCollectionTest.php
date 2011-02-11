<?php
namespace Xi\Collections\Collection;
use Xi\Collections\Enumerable\AbstractEnumerableTest;

abstract class AbstractCollectionTest extends AbstractEnumerableTest
{
    /**
     * @param array $elements
     * @return \Xi\Collections\Collection
     */
    abstract public function getCollection($elements = array());

    public function getEnumerable($elements = array())
    {
        return $this->getCollection($elements);
    }
}