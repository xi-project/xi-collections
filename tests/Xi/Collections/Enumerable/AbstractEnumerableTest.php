<?php
namespace Xi\Collections\Enumerable;

abstract class AbstractEnumerableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $elements optional
     * @return \Xi\Collections\Enumerable
     */
    abstract public function getEnumerable($elements = array());

    /**
     * @test
     */
    public function shouldProvideSelfToCallbackWhenTapped()
    {
        $result = new Result();
        $enum = $this->getEnumerable();
        $enum->tap(function($v) use($result) {
            $result->resolve($v);
        });
        $this->assertSame($enum, $result->get());
    }

    public function mixedElements()
    {
        return array(
            array(array()),
            array(array('foo')),
            array(array('foo' => 'bar', 'bar' => 'foo', 1, 2, 3))
        );
    }

    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToCountElements($values)
    {
        $enum = $this->getEnumerable($values);
        $result = count($enum);
        $this->assertEquals(count($values), $result);
    }

    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToReconstructValuesWithTraversal($values)
    {
        $result = array();
        $enum = $this->getEnumerable($values);
        foreach ($enum as $key => $value) {
            $result[$key] = $value;
        }
        $this->assertEquals($values, $result);
    }


    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToReconstructValuesWithEach($values)
    {
        $result = new \ArrayObject;
        $enum = $this->getEnumerable($values);
        $enum->each(function($v, $k) use($result) {
            $result[$k] = $v;
        });
        $this->assertEquals($values, $result->getArrayCopy());
    }

    /**
     * @test
     * @dataProvider mixedElements
     */
    public function shouldBeAbleToReconstructValuesWithReduce($values)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->reduce(function($result, $value, $key) {
            $result[$key] = $value;
            return $result;
        }, array());
        $this->assertEquals($result, $values);
    }

    public function integerElements()
    {
        return array(
            array(array()),
            array(array(1)),
            array(array(1, 2, 3, 4))
        );
    }

    /**
     * @test
     * @dataProvider integerElements
     */
    public function shouldBeAbleToSumIntegersWithReduce($values)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->reduce(function($result, $value) {
            return $result + $value;
        }, 0);
        $this->assertEquals($result, array_sum($values));
    }

    public function integerHaystack()
    {
        return array(
            array(array(), null),
            array(array(null, '', new \stdClass()), null),
            array(array(1), 1),
            array(array(1, 2), 1),
            array(array(null, '', new \stdClass(), 1), 1)
        );
    }

    /**
     * @test
     * @dataProvider integerHaystack
     */
    public function shouldBeAbleToFindMatchingValue($values, $expect)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->find('is_integer');
        $this->assertEquals($expect, $result);
    }

    /**
     * @test
     * @dataProvider integerHaystack
     */
    public function shouldBeAbleToCheckForExistenceOfMatchingValue($values, $expect)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->exists('is_integer');
        $this->assertEquals(!empty($expect), $result);
    }
    
    public function integerSets()
    {
        return array(
            array(array(), 0),
            array(array(1), 1),
            array(array(1, 2, 3), 3),
            array(array('nope'), 0),
            array(array(1, 2, 3, 'nope'), 3)
        );
    }

    /**
     * @test
     * @dataProvider integerSets
     */
    public function shouldBeAbleToAssertPredicateForAllValues($values, $integers)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->forAll('is_integer');
        $this->assertEquals(count($values) == $integers, $result);
    }

    /**
     * @test
     * @dataProvider integerSets
     */
    public function shouldBeAbleToCountValuesMatchingPredicate($values, $integers)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->countAll('is_integer');
        $this->assertEquals($integers, $result);
    }

    public function firstValues()
    {
        return array(
            array(array(), null),
            array(array(1), 1),
            array(array(1, 2), 1)
        );
    }

    /**
     * @test
     * @dataProvider firstValues
     */
    public function shouldBeAbleToRetrieveFirstValue($values, $first)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->first();
        $this->assertEquals($first, $result);
    }

    public function lastValues()
    {
        return array(
            array(array(), null),
            array(array(1), 1),
            array(array(1, 2), 2)
        );
    }

    /**
     * @test
     * @dataProvider lastValues
     */
    public function shouldBeAbleToRetrieveLastValue($values, $last)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->last();
        $this->assertEquals($last, $result);
    }
}