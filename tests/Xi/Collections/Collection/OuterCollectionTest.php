<?php
namespace Xi\Collections\Collection;

class OuterCollectionTest extends AbstractCollectionTest
{
    public function getCollection($elements = array())
    {
        return OuterCollection::create(ArrayCollection::create($elements));
    }

    protected function unit()
    {
        return ArrayCollection::create(parent::unit());
    }
}
