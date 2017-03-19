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
use Spaark\CompositeUtils\Factory\Reflection\GenericCompositeGenerator;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Test\Model\TestEntityWithGenericProperties;
use Spaark\CompositeUtils\Test\Model\TestGenericEntity;
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Exception\IllegalPropertyTypeException;

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
        $instance = new HashMap();
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

    protected function generateGeneric($typeA, $typeB)
    {
        $generator = new GenericCompositeGenerator
        (
            ReflectionCompositeFactory::fromClassName
            (
                TestGenericEntity::class
            )
            ->build()
        );
        $generator->createClass($typeA, $typeB);
        $class = (string)$generator->generatedClassName;

        return new $class();
    }

    public function testSettingAcceptableGeneric()
    {
        $entity = new TestEntityWithGenericProperties();
        $property = $this->generateGeneric
        (
            new IntegerType(),
            new StringType()
        );
        $entity->property = $property;
        $this->assertSame($property, $entity->property);
    }

    public function testSettingUnacceptableGeneric()
    {
        $this->expectException(IllegalPropertyTypeException::class);

        $entity = new TestEntityWithGenericProperties();

        $entity->property =
            $this->generateGeneric(new StringType(), new StringType());
    }
}
