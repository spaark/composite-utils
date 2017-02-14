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
use Spaark\CompositeUtils\Service\PropertyAccessor;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Collection\Collection;
use Spaark\CompositeUtils\Model\Collection\HashMap;

class PropertyAccessorTest extends TestCase
{
    private $accessor;

    public function setUp()
    {
        $this->accessor = new PropertyAccessor
        (
            new TestEntity(),
            ReflectionCompositeFactory::fromClassName(TestEntity::class)
                ->build()
        );
    }

    public function testRead()
    {
        $this->assertEquals
        (
            '123',
            $this->accessor->getValue('prop2')
        );
    }

    public function testAcceptableWrite()
    {
        $instance = new Collection();
        $this->accessor->setValue('prop3', $instance);
        $this->assertSame
        (
            $instance,
            $this->accessor->getValue('prop3')
        );
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\IllegalPropertyTypeException
     */
    public function testUnacceptableWrite()
    {
        $this->accessor->setValue('prop3', null);
    }

    public function testNullableWrite()
    {
        $this->accessor->setValue('prop2', null);
        $this->assertNull($this->accessor->getValue('prop2'));
    }

    public function testConstruct()
    {
        $this->accessor->constructObject('test');
        $this->assertSame
        (
            'test',
            $this->accessor->getRawValue('prop1')
        );
        $this->assertFalse($this->accessor->getRawValue('prop4'));
    }

    public function testConstructOptional()
    {
        $set = new HashMap();
        $this->accessor->constructObject('test', $set, true);
        $this->assertSame($set, $this->accessor->getRawValue('prop3'));
        $this->assertTrue($this->accessor->getRawValue('prop4'));
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\MissingRequiredParameterException
     */
    public function testConstructWithoutRequired()
    {
        $this->accessor->constructObject();
    }
}
