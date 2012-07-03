<?php
namespace Xi\Collections\Enumerable;

class OuterEnumerableTest extends AbstractEnumerableTest
{
    public function getEnumerable($elements = array())
    {
        return new OuterEnumerable(new ArrayEnumerable($elements));
    }

    /**
     * @test
     */
    public function shouldBeAbleToProvideInnerEnumerable()
    {
        $inner = new ArrayEnumerable(array());
        $outer = new OuterEnumerable($inner);
        $this->assertSame($inner, $outer->getInnerEnumerable());
    }
}