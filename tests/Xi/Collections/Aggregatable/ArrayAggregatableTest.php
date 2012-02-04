<?php

namespace Xi\Collections\Aggregatable;

use ArrayIterator;

/**
 * @author Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
class ArrayAggregatableTest extends AbstractAggregatableTest
{
    /**
     * @param  array             $elements optional
     * @return ArrayAggregatable
     */
    protected function getAggregatable($elements = array())
    {
        return new ArrayAggregatable($elements);
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateFromArray()
    {
        $elements = array(1, 2);

        $this->assertEquals(
            new ArrayAggregatable($elements),
            ArrayAggregatable::create($elements)
        );
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateFromTraversable()
    {
        $elements = array(1, 2);

        $this->assertEquals(
            new ArrayAggregatable($elements),
            ArrayAggregatable::create(new ArrayIterator($elements))
        );
    }
}
