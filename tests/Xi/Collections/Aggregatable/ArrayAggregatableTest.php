<?php

namespace Xi\Collections\Aggregatable;

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
}