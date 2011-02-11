<?php
namespace Xi\Collections\Collection;

class IndexedCollectionTest extends AbstractCollectionTest
{
    public function getCollection($elements = array())
    {
        return IndexedCollection::create($elements);
    }
}