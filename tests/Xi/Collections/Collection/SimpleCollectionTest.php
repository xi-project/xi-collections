<?php
namespace Xi\Collections\Collection;

class SimpleCollectionTest extends AbstractCollectionTest
{
    public function getCollection($elements = array())
    {
        return SimpleCollection::create($elements);
    }
}
