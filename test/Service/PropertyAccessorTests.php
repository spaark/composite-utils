<?php

namespace Spaark\Core\Test\Service;

use PHPUnit\Framework\TestCase;
use Spaark\Core\Service\PropertyAccessor;
use Spaark\Core\Exception\CannotReadPropertyException;
use Spaark\Core\Exception\CannotWritePropertyException;
use Spaark\Core\Test\Model\TestEntity;
use Spaark\Core\Model\Reflection\Model as ReflectionModel;

/**
 * @coversDefaultClass Spaark\Core\Service\PropertyAccessor;
 */
class PropertyAccessorTests extends TestCase
{
    protected $accessor;

    public function testBuild()
    {
        $this->markTestIncomplete();
    }

    public function setUp()
    {
        $entity = new TestEntity();
        $model = new ReflectionModel();
        $this->accessor = new PropertyAccessor($entity, $model);
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
     * @expectedException Spaark\Core\Exception\CannotReadPropertyException
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
     * @expectedException Spaark\Core\Exception\CannotWritePropertyException
     */
    public function testWriteNonExistentProperty()
    {
        $this->accessor->setRawValue('no_such_property', 's');
    }
}
