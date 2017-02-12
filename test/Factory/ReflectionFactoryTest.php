<?php

namespace Spaark\CompositeUtils\Test\Factory;

use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Factory\EntityCache;
use Spaark\CompositeUtils\Model\Collection\Collection;
use Spaark\CompositeUtils\Model\Collection\HashMap;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;

/**
 *
 */
class ReflectionFactoryTest extends TestCase
{
    protected $reflect;

    private $properties =
    [
        /* name, type, nullable, readable, writeable */
        ['id', StringType::class, false, true, true],
        ['property', StringType::class, true, false, false],
        ['arrayProperty', ObjectType::class, false, false, false]
    ];

    public function testComposite()
    {
        $reflect = ReflectionCompositeFactory::fromClassName
        (
            TestEntity::class
        )
        ->build();

        $this->assertInstanceOf
        (
            ReflectionComposite::class, $reflect
        );
        $this->assertAttributeCount(1, 'methods', $reflect);

        return $reflect;
    }

    /**
     * @depends testComposite
     */
    public function testProperties(ReflectionComposite $reflect)
    {
        $properties = (new RawPropertyAccessor($reflect))
            ->getRawValue('properties');

        $this->assertInstanceOf(HashMap::class, $properties);
        $this->assertEquals
        (
            count($this->properties),
            $properties->size()
        );

        return $properties;
    }

    /**
     * @depends testProperties
     * @dataProvider propertiesProvider
     */
    public function testProperty
    (
        string $name,
        string $type,
        bool $nullable,
        bool $readable,
        bool $writable,
        HashMap $properties
    )
    {
        $this->assertTrue($properties->contains($name));
        $property = $properties[$name];
        $this->assertInstanceOf(ReflectionProperty::class, $property);

        $this->assertInstanceOf($type, $property->type);
        $this->assertSame($nullable, $property->type->nullable);

        $this->assertSame($readable, $property->readable);
        $this->assertSame($writable, $property->writable);
    }

    /**
     * @depends testProperties
     * @depends testProperty
     */
    public function testObjectProperty(Collection $properties)
    {
        $property = $properties['arrayProperty'];

        $this->assertEquals
        (
            Collection::class,
            $property->type->classname
        );
    }

    public function propertiesProvider()
    {
        return $this->properties;
    }
}
