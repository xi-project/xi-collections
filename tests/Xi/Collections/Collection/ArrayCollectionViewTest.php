<?php
namespace Xi\Collections\Collection;

class ArrayCollectionViewTest extends ArrayCollectionTest
{
    public function getCollection($elements = array())
    {
        return parent::getCollection($elements)->view();
    }
}