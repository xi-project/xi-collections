<?php
namespace Xi\Collections\Enumerable;

abstract class AbstractEnumerableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $elements optional
     * @return \Xi\Collections\Enumerable
     */
    abstract public function getEnumerable($elements = array());

    public function mixedElements()
    {
        return array(
            array(array()),
            array(array('foo')),
            array(array('foo' => 'bar', 'bar' => 'foo', 1, 2, 3))
        );
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

    public function integerHaystack()
    {
        return array(
            array(array(), null),
            array(array(1), 1),
            array(array(1, 2), 1),
            array(array(null, '', new \stdClass(), 1), 1)
        );
    }

    /**
     * @test
     * @datProvider integerHaystack
     */
    public function shouldBeAbleToFindMatchingValue($values, $expect)
    {
        $enum = $this->getEnumerable($values);
        $result = $enum->find('is_integer');
        $this->assertEquals($expect, $result);
    }
}