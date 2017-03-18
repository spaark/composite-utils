<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Test\Model;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\FloatType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\NullType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;

/**
 * Do something
 */
class TypeTest extends TestCase
{
    /**
     * @dataProvider castDataProvider
     */
    public function testCast
    (
        AbstractType $type,
        $inputValue,
        $expectedOutput
    )
    {
        $this->assertSame($expectedOutput, $type->cast($inputValue));
    }

    public function testSimpleEqual()
    {
        foreach ($this->simpleDataCasts() as $typeAName)
        {
            $typeA = new $typeAName();

            foreach ($this->simpleDataCasts() as $typeBName)
            {
                $typeB = new $typeBName();

                $this->assertSame
                (
                    $typeAName === $typeBName,
                    $typeA->equals($typeB)
                );
            }
        }
    }

    public function testObjectEqual()
    {
        $objectA = new ObjectType('test');
        $objectB = new ObjectType('test');
        $objectC = new ObjectType('foo');

        $this->assertTrue($objectA->equals($objectB));
        $this->assertFalse($objectA->equals($objectC));

        $objectA->generics[] = new StringType();
        $objectA->generics[] = $objectC;
        $objectB->generics[] = new StringType();
        $objectB->generics[] = new ObjectType('foo');

        $this->assertTrue($objectA->equals($objectB));
    }

    public function castDataProvider()
    {
        return
        [
            [new StringType(), 123, '123'],
            [new StringType(), true, '1'],
            [new StringType(), null, ''],
            [new IntegerType(), true, 1],
            [new IntegerType(), false, 0],
            [new IntegerType(), '123', 123],
            [new BooleanType(), '132', true],
            [new BooleanType(), 0, false],
            [new FloatType(), 1, 1.0]
        ];
    }

    public function simpleDataCasts()
    {
        return
        [
            StringType::class,
            IntegerType::class,
            FloatType::class,
            BooleanType::class,
            NullType::class
        ];
    }
}
