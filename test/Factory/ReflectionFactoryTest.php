<?php

namespace Spaark\CompositeUtils\Test\Factory;

use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Factory\EntityCache;
use Spaark\CompositeUtils\Model\Collection\Collection;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;

/**
 *
 */
class ReflectionFactoryTest extends TestCase
{
    protected $reflect;

    public function setUp()
    {
        $reflectorFactory = ReflectionCompositeFactory::fromClassName
        (
            TestEntity::class
        );
        $this->reflect = $reflectorFactory->build();
    }

    public function testCreation()
    {
        $this->assertInstanceOf
        (
            ReflectionComposite::class, $this->reflect
        );
        $this->assertAttributeCount(1, 'methods', $this->reflect);
    }

    public function testProperties()
    {
        $properties = (new RawPropertyAccessor($this->reflect))
            ->getRawValue('properties');

        $this->assertInstanceOf(Collection::class, $properties);
        $this->assertEquals(3, $properties->size());

        return $properties;
    }

    /**
     * @depends testProperties
     */
    public function testProperty(Collection $properties)
    {
        $property = $properties['id'];
        $this->assertInstanceOf(ReflectionProperty::class, $property);
    }

    /**
     * @depends testProperties
     */
    public function testObjectProperty(Collection $properties)
    {
        $property = $properties['arrayProperty'];

        $this->assertInstanceOf(ObjectType::class, $property->type);
        $this->assertEquals(Collection::class, $property->type->classname);
    }
}
