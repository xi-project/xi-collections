<?php
namespace Xi\Collections\Collection;
use Xi\Collections\Enumerable\AbstractEnumerableTest,
    Xi\Collections\Collection;

abstract class AbstractCollectionTest extends AbstractEnumerableTest
{
    /**
     * @param array $elements optional
     * @return \Xi\Collections\Collection
     */
    abstract public function getCollection($elements = array());

    /**
     * @param array $elements optional
     * @return \Xi\Collections\Collection
     */
    public function getEnumerable($elements = array())
    {
        return $this->getCollection($elements);
    }

    /**
     * @return mixed
     */
    protected function unit()
    {
        return array();
    }

    /**
     * @test
     */
    public function creatorFunctionShouldBeLateStaticBound()
    {
        $collection = $this->getCollection();
        $class = get_class($collection);
        $this->assertEquals(get_class($class::getCreator()->__invoke($this->unit())), $class);
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

    public function flatMapSet()
    {
        return array(
            array(array(), array()),
            array(array('f'), array('f')),
            array(array('foo'), array('f', 'o', 'o')),
            array(array('foo', 'bar'), array('f', 'o', 'o', 'b', 'a', 'r')),
            array(array('foo', 'ba', 'q'), array('f', 'o', 'o', 'b', 'a', 'q'))
        );
    }

    /**
     * @test
     * @dataProvider flatMapSet
     */
    public function shouldBeAbleToGetMapOutputAsFlat($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->flatMap(function($v) { return str_split($v); });
        $this->assertEquals($expected, $result->toArray());
    }
    
    public function indexedIntegerIncrementSet()
    {
        return array(
            array(array(), array()),
            array(array(1), array(2)),
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

    public function keyMapSet()
    {
        return array(
            array(array('foo' => null), array('foo' => 'foo')),
        );
    }
    
    /**
     * @test
     * @dataProvider keyMapSet
     * @depends shouldMaintainIndexAssociationsWhenMapping
     */
    public function shouldProvideKeysToMapFunction($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->map(function($v, $k) {
            return $k;
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
            array(array('foo' => 1), array('foo' => 2), array('foo' => 2)),
            array(array('bar' => 2, 'foo' => 1), array('foo' => 2), array('bar' => 2, 'foo' => 2))
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

    /**
     * @test
     */
    public function shouldBeAbleToMapByInvokingMethodOnElements()
    {
        $collection = $this->getCollection(array(function() { return 'foo'; }));
        $result = $collection->invoke('__invoke');
        $this->assertEquals(array('foo'), $result->toArray());
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

    /**
     * @return array
     */
    public function strictlyUniqueSet()
    {
        return array(
            array(array(), array()),
            array(array('foo', 'bar'), array('foo', 'bar')),
            array(array('foo', 'foo'), array('foo')),
            array(array($a = new \stdClass, $b = new \stdClass), array($a, $b)),
            array(array($o = new \stdClass, $o), array($o))
        );
    }

    /**
     * @test
     * @dataProvider strictlyUniqueSet
     */
    public function shouldBeAbleToFilterUniquesStrictly($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->unique();
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @return array
     */
    public function nonStrictlyUniqueSet()
    {
        return array(
            array(array(), array()),
            array(array('foo', 'bar'), array('foo', 'bar')),
            array(array('foo', 'foo'), array('foo')),
            array(array('100', 100), array('100')),
            array(array(100, '100'), array(100))
        );
    }

    /**
     * @test
     * @dataProvider nonStrictlyUniqueSet
     */
    public function shouldBeAbleToFilterUniquesNonStrictly($elements, $expected)
    {
        // FIXME: The boolean parameter implies a code smell
        $collection = $this->getCollection($elements);
        $result = $collection->unique(false);
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @return array
     */
    public function sortWithSet()
    {
        return array(
            array(array(), array()),
            array(array(1), array(1)),
            array(array(2, 1), array(1, 2))
        );
    }

    /**
     * @test
     * @dataProvider sortWithSet
     */
    public function shouldBeAbleToSortWithComparator($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->sortWith(function($a, $b) {
            return $a - $b;
        });
        $this->assertEquals($expected, $result->toArray());
    }

    public function sortBySet()
    {
        return array(
            array(array(), array()),
            array(array('foo'), array('foo')),
            array(array('foo', 'quxen', 'qux'), array('foo', 'qux', 'quxen'))
        );
    }

    /**
     * @test
     * @dataProvider sortBySet
     */
    public function shouldBeAbleToSortByMetric($elements, $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->sortBy(function($v) {
            return strlen($v);
        });
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @test
     * @dataProvider addValue
     *
     * @param array $elements
     * @param mixed $value
     * @param array $expected
     */
    public function shouldBeAbleToAddValue(array $elements, $value, array $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->add($value);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @return array
     */
    public function addValue()
    {
        return array(
            array(array('a', 'b'), 'c', array('a', 'b', 'c')),
            array(array(1, 3), 2, array(1, 3, 2)),
        );
    }

    /**
     * @test
     * @dataProvider addKeyAndValue
     *
     * @param array $elements
     * @param mixed $key
     * @param mixed $value
     * @param array $expected
     */
    public function shouldBeAbleToAddKeyAndValue(array $elements, $key, $value, array $expected)
    {
        $collection = $this->getCollection($elements);
        $result = $collection->add($value, $key);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @return array
     */
    public function addKeyAndValue()
    {
        return array(
            array(array('a' => 'b'), 'c', 'd', array('a' => 'b', 'c' => 'd')),
            array(array(1 => 'a', 3 => 'b'), 2, 'c', array(1 => 'a', 3 => 'b', 2 => 'c')),
        );
    }

    /**
     * @test
     * @dataProvider minimumProvider
     */
    public function shouldBeAbleToGetMinimumOfValues($elements, $expected)
    {
        $collection = $this->getCollection($elements);

        $this->assertEquals($expected, $collection->min());
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
    public function shouldBeAbleToGetMaximumOfValues($elements, $expected)
    {
        $collection = $this->getCollection($elements);

        $this->assertEquals($expected, $collection->max());
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
    public function shouldBeAbleToGetSumOfValues($elements, $expected)
    {
        $collection = $this->getCollection($elements);

        $this->assertEquals($expected, $collection->sum());
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

    /**
     * @test
     * @dataProvider productProvider
     */
    public function shouldBeAbleToGetProductOfValues($elements, $expected)
    {
        $collection = $this->getCollection($elements);

        $this->assertEquals($expected, $collection->product());
    }

    /**
     * @return array
     */
    public function productProvider()
    {
        return array(
            array(array(), null),
            array(array(0), 0),
            array(array(1, 2), 2),
            array(array(2, 3, 5), 30),
        );
    }

    /**
     * @test
     * @dataProvider restElements
     */
    public function shouldBeAbleToTakeRestOfTheElementsExceptFirst($elements, $expected)
    {
        $collection = $this->getCollection($elements);

        $result = $collection->rest();

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @return array
     */
    public function restElements()
    {
        return array(
            array(array(), array()),
            array(array('a'), array()),
            array(array('a', 'b', 'c'), array('b', 'c')),
            array(array(1 => 'a', 2 => 'b', 'c' => 'd'), array(0 => 'b', 'c' => 'd')),
            array(array(0 => 'a', 1 => 'b', '2' => 'c', 3 => 'd'), array(0 => 'b', 1 => 'c', 2 => 'd')),
        );
    }
}
