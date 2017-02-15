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

namespace Spaark\CompositeUtils\Test\Service;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Service\ConditionalPropertyAccessor;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Exception\PropertyNotWritableException;
use Spaark\CompositeUtils\Exception\PropertyNotReadableException;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Collection\HashMap;

class ConditionalPropertyAccessorTest extends TestCase
{
    private $accessor;

    public function setUp()
    {
        $this->accessor = new ConditionalPropertyAccessor
        (
            new TestEntity(),
            ReflectionCompositeFactory::fromClassName(TestEntity::class)
                ->build()
        );
    }

    /**
     * @dataProvider propertyList
     */
    public function testRead
    (
        string $property,
        $expectedValue,
        bool $shouldFailRead,
        bool $shouldFailWrite
    )
    {
        if ($shouldFailRead)
        {
            $this->expectException
            (
                PropertyNotReadableException::class
            );
        }

        $this->assertEquals
        (
            $expectedValue,
            $this->accessor->getValue($property)
        );
    }

    /**
     * @dataProvider propertyList
     */
    public function testWrite
    (
        string $property,
        $expectedValue,
        bool $shouldFailRead,
        bool $shouldFailWrite
    )
    {
        if ($shouldFailWrite)
        {
            $this->expectException
            (
                PropertyNotWritableException::class
            );
        }

        $set = is_string($expectedValue) ? 'bar' : new HashMap();

        $this->accessor->setValue($property, $set);

        $this->assertSame
        (
            $set,
            $this->accessor->getRawValue($property)
        );
    }

    public function propertyList()
    {
        return
        [
            ['prop1', 'foo', false, false],
            ['prop2', '123', false, true],
            ['prop3', null, true, false],
            ['prop4', false, true, true]
        ];
    }
} 
