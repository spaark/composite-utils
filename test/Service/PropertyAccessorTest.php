<?php

namespace Spaark\CompositeUtils\Test\Service;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Service\PropertyAccessor;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Collection\Collection;

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
            $this->accessor->getValue('property')
        );
    }

    public function testAcceptableWrite()
    {
        $instance = new Collection();
        $this->accessor->setValue('arrayProperty', $instance);
        $this->assertSame
        (
            $instance,
            $this->accessor->getValue('arrayProperty')
        );
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\IllegalPropertyTypeException
     */
    public function testUnacceptableWrite()
    {
        $this->accessor->setValue('arrayProperty', null);
    }

    public function testNullableWrite()
    {
        $this->accessor->setValue('property', null);
        $this->assertNull($this->accessor->getValue('property'));
    }
}
