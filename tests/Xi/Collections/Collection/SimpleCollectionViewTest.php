<?php
namespace Xi\Collections\Collection;

class SimpleCollectionViewTest extends AbstractCollectionTest
{
    public function getCollection($elements = array())
    {
        return SimpleCollectionView::create($elements);
    }

    /**
     * @test
     */
    public function shouldBeAbleToCoerceIntoPlainCollection()
    {
        $this->assertFalse($this->getCollection()->force() instanceof SimpleCollectionView);
    }
}