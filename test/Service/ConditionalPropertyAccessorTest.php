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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Test\Service;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Service\ConditionalPropertyAccessor;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Exception\PropertyNotWritableException;
use Spaark\CompositeUtils\Exception\PropertyNotReadableException;
use Spaark\CompositeUtils\Test\Model\TestEntity;

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
    public function testRead($property, $expectedValue, $shouldFail)
    {
        if ($shouldFail)
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
    public function testWrite($property, $expectedValue, $shouldFail)
    {
        if ($shouldFail)
        {
            $this->expectException
            (
                PropertyNotWritableException::class
            );
        }

        $this->accessor->setValue($property, 'bar');

        $this->assertEquals
        (
            'bar',
            $this->accessor->getRawValue($property)
        );
    }

    public function propertyList()
    {
        return
        [
            ['id', 'foo', false],
            ['property', '123', true]
        ];
    }
} 
