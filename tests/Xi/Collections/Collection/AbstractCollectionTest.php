<?php
namespace Xi\Collections\Collection;
use Xi\Collections\Enumerable\AbstractEnumerableTest,
    Xi\Collections\Collection;

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
    
    public function indexedIntegerIncrementSet()
    {
        return array(
            array(array(), array()),
            array(array('foo' => 1), array('foo' => 2)),
            array(array('foo' => 1, 'bar' => 2), array('foo' => 2, 'bar' => 3))
        );
    }
    
    /**
     * @test
     * @dataProvider indexedIntegerIncrementSet
     * @depends shouldBeAbleToMapEachElement
     */
    public function shouldMaintainIndexAssociationsWhenMapping($elements, $expected)
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
    
    public function indexBySet()
    {
        return array(
            array(array(), array()),
            array(array('foo'), array('foo' => 'foo')),
            array(array('foo' => 'bar'), array('bar' => 'bar')),
            array(array('foo' => 'bar', 'foo'), array('bar' => 'bar', 'foo' => 'foo'))
        );
    }
    
    /**
     * @test
     * @dataProvider indexBySet
     */
    public function shouldBeAbleToIndexByCallback($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->indexBy(function($value) {
            return $value;
        });
        $this->assertEquals($expected, $result->toArray());
    }
    
    public function groupBySet()
    {
        return array(
            array(array(), array()),
            array(array('foo'), array('foo' => array('foo'))),
            array(array('foo', 'foo'), array('foo' => array('foo', 'foo'))),
            array(array('foo', 'bar'), array('foo' => array('foo'), 'bar' => array('bar'))),
            array(array('foo' => 'bar'), array('bar' => array('bar')))
        );
    }
    
    /**
     * @test
     * @dataProvider groupBySet
     */
    public function shouldBeAbleToGroupByCallback($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $collection = $collection->groupBy(function($value) {
            return $value;
        });
        $result = array();
        foreach ($collection as $key => $value) {
            $result[$key] = ($value instanceof Collection) ? $value->toArray() : $value;
        }
        $this->assertEquals($expected, $result);
    }
    
    public function pickFromArraySet()
    {
        return array(
            array(array(), array()),
            array(array(array('foo' => 'bar')), array('bar')),
            array(array(array('foo' => 'bar'), array('foo' => 'qux')), array('bar', 'qux')),
            array(array(array('bar')), array(null)),
        );
    }
    
    /**
     * @test
     * @dataProvider pickFromArraySet
     */
    public function shouldBeAbleToPickKeysFromArray($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->pick('foo');
        $this->assertEquals($expected, $result->toArray());
    }
    
    public function pickFromObjectSet()
    {
        return array(
            array(array(), array()),
            array(array((object) array('foo' => 'bar')), array('bar')),
            array(array((object) array('foo' => 'bar'), (object) array('foo' => 'qux')), array('bar', 'qux')),
            array(array((object) array('bar')), array(null)),
        );
    }
    
    /**
     * @test
     * @dataProvider pickFromObjectSet
     */
    public function shouldBeAbleToPickKeysFromObject($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->pick('foo');
        $this->assertEquals($expected, $result->toArray());
    }
    
    public function flattenArraySet()
    {
        return array(
            array(array(), array()),
            array(array('foo'), array('foo')),
            array(array(array('foo')), array('foo')),
            array(array('foo', array('bar')), array('foo', 'bar'))
        );
    }
    
    /**
     * @test
     * @dataProvider flattenArraySet
     */
    public function shouldBeAbleToFlattenArrays($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->flatten();
        $this->assertEquals($expected, $result->toArray());
    }
    
    public function flattenTraversableSet()
    {
        $set = $this->flattenArraySet();
        foreach ($set as $key => $args) {
            list($elements, $expected) = $args;
            $set[$key] = array($this->traversable($elements), $expected);
        }
        return $set;
    }
    
    /**
     * @return \ArrayIterator 
     */
    private function traversable(array $items)
    {
        foreach ($items as $key => $value) {
            if (is_array($value)) {
                $items[$key] = new \ArrayObject($this->traversable($value));
            } else {
                $items[$key] = $value;
            }
        }
        return $items;
    }
    
    /**
     * @test
     * @dataProvider flattenTraversableSet
     */
    public function shouldBeAbleToFlattenTraversables($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->flatten();
        $this->assertEquals($expected, $result->toArray());
    }
}