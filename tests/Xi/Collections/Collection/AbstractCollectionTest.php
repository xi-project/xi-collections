<?php
namespace Xi\Collections\Collection;
use Xi\Collections\Enumerable\AbstractEnumerableTest;

abstract class AbstractCollectionTest extends AbstractEnumerableTest
{
    /**
     * @param array $elements
     * @return \Xi\Collections\Collection
     */
    abstract public function getCollection($elements = array());

    public function getEnumerable($elements = array())
    {
        return $this->getCollection($elements);
    }

    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToTransformWholeCollectionUsingApply($elements)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->apply(function ($c) {
            return array_flip($c->toArray());
        });
        $this->assertEquals($elements, array_flip($result->toArray()));
    }

    public function takeElements()
    {
        return array(
            array(array(), 0, array()),
            array(array(1), 0, array()),
            array(array(1), 1, array(1)),
            array(array(1, 2, 3), 3, array(1, 2, 3)),
            array(array(1, 2, 3), 4, array(1, 2, 3)),
            array(array(1, 2, 3), -1, array())
        );
    }

    /**
     * @test
     * @dataProvider takeElements
     */
    public function shouldBeAbleToTakeNFirstElements($elements, $number, $expect)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->take($number);
        $this->assertEquals($expect, $result->toArray());
    }

    public function integerIncrementSet()
    {
        return array(
            array(array(), array()),
            array(array(1), array(2)),
            array(array(1, 2, 3), array(2, 3, 4))
        );
    }

    /**
     * @test
     * @dataProvider integerIncrementSet
     */
    public function shouldBeAbleToMapEachElement($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->map(function ($v) {
            return $v + 1;
        });
        $this->assertEquals($expected, $result->toArray());
    }

    public function truthinessSet()
    {
        return array(
            array(array(), array()),
            array(array(true, false), array(true)),
            array(array(0, 1, 2), array(1, 2))
        );
    }

    /**
     * @test
     * @dataProvider truthinessSet
     */
    public function shouldFilterForTruthinessByDefault($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->filter();
        $this->assertEquals($expected, array_values($result->toArray()));
    }

    /**
     * @test
     * @dataProvider truthinessSet
     */
    public function shouldBeAbleToFilterByCustomCriteria($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->filter(function ($v) {
            return (bool) $v;
        });
        $this->assertEquals($expected, array_values($result->toArray()));
    }
    
    public function concatSet()
    {
        return array(
            array(array(), array(), array()),
            array(array(1), array(2), array(1, 2)),
            array(array('foo' => 1), array('foo' => 2), array(1, 2))
        );
    }

    /**
     * @test
     * @dataProvider concatSet
     */
    public function shouldBeAbleToConcatenate($a, $b, $expected)
    {
        $left = $this->getCollection($a);
        $right = $this->getCollection($b);
        $result = $left->concatenate($right);
        $this->assertEquals($expected, $result->toArray());
    }

    public function unionSet()
    {
        return array(
            array(array(), array(), array()),
            array(array(1), array(2), array(2)),
            array(array('foo' => 1), array('foo' => 2), array('foo' => 2))
        );
    }

    /**
     * @test
     * @dataProvider unionSet
     */
    public function shouldBeAbleToCreateUnionMap($a, $b, $expected)
    {
        $left = $this->getCollection($a);
        $right = $this->getCollection($b);
        $result = $left->union($right);
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToGetValueCollection($elements)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->values();
        $this->assertEquals(array_values($elements), $result->toArray());
    }

    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToGetKeyCollection($elements)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->keys();
        $this->assertEquals(array_keys($elements), $result->toArray());
    }
}