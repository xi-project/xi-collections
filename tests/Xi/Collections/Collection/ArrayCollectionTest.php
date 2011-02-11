<?php
namespace Xi\Collections\Collection;

class ArrayCollectionTest extends AbstractCollectionTest
{
    public function getCollection($elements = array())
    {
        return ArrayCollection::create($elements);
    }
}