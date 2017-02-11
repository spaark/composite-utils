<?php

namespace Spaark\CompositeUtils\Test\Service;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Service\PropertyAccessor;
use Spaark\CompositeUtils\Exception\CannotReadPropertyException;
use Spaark\CompositeUtils\Exception\CannotWritePropertyException;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Reflection\Model as ReflectionModel;

/**
 * @coversDefaultClass Spaark\CompositeUtils\Service\PropertyAccessor;
 */
class PropertyAccessorTest extends TestCase
{
    protected $accessor;

    public function testBuild()
    {
        $this->markTestIncomplete();
    }

    public function setUp()
    {
        $entity = new TestEntity();
        $this->accessor = new PropertyAccessor($entity, null);
    }

    /**
     * @covers ::getRawValue
     * @covers ::getPropertyOrFail
     */
    public function testRead()
    {
        $this->assertEquals
        (
            '123',
            $this->accessor->getRawValue('property')
        );
    }

    /**
     * @covers ::getPropertyOrFail
     * @expectedException Spaark\CompositeUtils\Exception\CannotReadPropertyException
     */
    public function testReadNonExistentProperty()
    {
        $this->accessor->getRawValue('no_such_property');
    }

    /**
     * @covers ::setRawValue
     */
    public function testWrite()
    {
        $this->accessor->setRawValue('property', '456');
        $this->assertEquals
        (
            '456',
            $this->accessor->getRawValue('property')
        );
    }

    /**
     * @covers ::getPropertyOrFail
     * @expectedException Spaark\CompositeUtils\Exception\CannotWritePropertyException
     */
    public function testWriteNonExistentProperty()
    {
        $this->accessor->setRawValue('no_such_property', 's');
    }

    /**
     * @covers ::rawAddToValue
     */
    public function testAddToCollectionProperty()
    {
        $this->assertEquals
        (
            0,
            $this->accessor->getRawValue('arrayProperty')->size()
        );

        $this->accessor->rawAddToValue('arrayProperty', 'value');

        $this->assertEquals
        (
            1,
            $this->accessor->getRawValue('arrayProperty')->size()
        );
    }
}
