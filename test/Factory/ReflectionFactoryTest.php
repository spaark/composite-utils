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

namespace Spaark\CompositeUtils\Test\Factory;

use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Test\Model\InheritedEntity;
use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Factory\EntityCache;
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;
use Spaark\CompositeUtils\Model\Collection\Map\OrderedMap;
use Spaark\CompositeUtils\Model\Collection\ListCollection\FixedList;
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
        ['prop1', StringType::class, false, true, true, true, true, false],
        ['prop2', StringType::class, true, true, false, false, false, true],
        ['prop3', ObjectType::class, false, false, true, true, false, false],
        ['prop4', BooleanType::class, false, false, false, true, false, true],
        ['prop5', ObjectType::class, false, false, false, false, false, false]
    ];

    private $parameters =
    [
        [0, 'a'],
        [1, 'b']
    ];

    public function testComposite(string $classname = TestEntity::class)
    {
        $reflect = ReflectionCompositeFactory::fromClassName
        (
            $classname
        )
        ->build();

        $this->assertInstanceOf
        (
            ReflectionComposite::class, $reflect
        );
        $this->assertAttributeCount(2, 'methods', $reflect);
        $this->assertAttributeEquals
        (
            $classname, 'classname', $reflect
        );

        return $reflect;
    }

    /**
     * @depends testComposite
     */
    public function testMethods(ReflectionComposite $reflect)
    {
        $this->assertTrue($reflect->methods->containsKey('methodName'));

        $method = $reflect->methods['methodName'];
        $this->assertAttributeCount
        (
            count($this->parameters),
            'parameters',
            $method
        );

        return $method->parameters;
    }

    /**
     * @depends testMethods
     * @dataProvider parameterProvider
     */
    public function testParameter
    (
        int $index,
        string $name,
        FixedList $list
    )
    {
        $param = $list[$index];
        $this->assertSame($name, $param->name);
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
        bool $passedToConstructor,
        bool $requiredInConstructor,
        bool $builtInConstructor,
        HashMap $properties
    )
    {
        $this->assertTrue($properties->containsKey($name));
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
     * @dataProvider objectPropertiesProvider
     */
    public function testObjectProperty
    (
        string $property,
        string $classname,
        HashMap $properties
    )
    {
        $this->assertEquals
        (
            $classname,
            $properties[$property]->type->classname
        );
    }

    /**
     * @depends testComposite
     */
    public function testConstructorItems(ReflectionComposite $reflect)
    {
        $this->assertEquals(1, $reflect->requiredProperties->size());
        $this->assertTrue($reflect->requiredProperties->contains
        (
            $reflect->properties['prop1']
        ));

        $this->assertEquals(2, $reflect->optionalProperties->size());
        $this->assertTrue($reflect->optionalProperties->contains
        (
            $reflect->properties['prop3']
        ));
        $this->assertTrue($reflect->optionalProperties->contains
        (
            $reflect->properties['prop4']
        ));

        $this->assertEquals(1, $reflect->builtProperties->size());
        $this->assertTrue($reflect->builtProperties->contains
        (
            $reflect->properties['prop2']
        ));
    }

    public function testInheritance()
    {
        $this->testComposite(InheritedEntity::class);
    }

    public function parameterProvider()
    {
        return $this->parameters;
    }

    public function propertiesProvider()
    {
        return $this->properties;
    }

    public function objectPropertiesProvider()
    {
        return
        [
            ['prop3', HashMap::class],
            ['prop5', TestEntity::class]
        ];
    }
}
