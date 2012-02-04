<?php

namespace Xi\Collections\Aggregatable;

use PHPUnit_Framework_TestCase,
    Xi\Collections\Aggregatable;

/**
 * @author Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
abstract class AbstractAggregatableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param  array        $elements optional
     * @return Aggregatable
     */
    abstract protected function getAggregatable($elements = array());

    /**
     * @test
     */
    public function shouldBeInstanceOfEnumerable()
    {
        $this->assertInstanceOf(
            'Xi\Collections\Enumerable',
            $this->getAggregatable()
        );
    }

    /**
     * @test
     */
    public function shouldBeInstanceOfAggregatable()
    {
        $this->assertInstanceOf(
            'Xi\Collections\Aggregatable',
            $this->getAggregatable()
        );
    }

    /**
     * @test
     * @dataProvider minimumProvider
     */
    public function shouldBeAgleGetMinimumOfValues($values, $expected)
    {
        $aggregatable = $this->getAggregatable($values);

        $this->assertEquals($expected, $aggregatable->min());
    }

    /**
     * @return array
     */
    public function minimumProvider()
    {
        return array(
            array(array(), null),
            array(array(1, 2, 3), 1),
            array(array(5, 2), 2),
        );
    }

    /**
     * @test
     * @dataProvider maximumProvider
     */
    public function shouldBeAgleGetMaximumOfValues($values, $expected)
    {
        $aggregatable = $this->getAggregatable($values);

        $this->assertEquals($expected, $aggregatable->max());
    }

    /**
     * @return array
     */
    public function maximumProvider()
    {
        return array(
            array(array(), null),
            array(array(1, 2, 3), 3),
            array(array(5, 2), 5),
        );
    }

    /**
     * @test
     * @dataProvider sumProvider
     */
    public function shouldBeAgleGetSumOfValues($values, $expected)
    {
        $aggregatable = $this->getAggregatable($values);

        $this->assertEquals($expected, $aggregatable->sum());
    }

    /**
     * @return array
     */
    public function sumProvider()
    {
        return array(
            array(array(), null),
            array(array(0), 0),
            array(array(1, 2), 3),
            array(array(2, 4, 6), 12),
        );
    }
}
