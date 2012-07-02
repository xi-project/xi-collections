<?php
namespace Xi\Collections\Collection;

class OuterCollectionViewTest extends OuterCollectionTest
{
    public function getCollection($elements = array())
    {
        return parent::getCollection($elements)->view();
    }
}