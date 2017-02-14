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
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Exception\CannotReadPropertyException;
use Spaark\CompositeUtils\Exception\CannotWritePropertyException;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Reflection\Model as ReflectionModel;

/**
 * @coversDefaultClass Spaark\CompositeUtils\Service\RawPropertyAccessor
 */
class RawPropertyAccessorTest extends TestCase
{
    protected $accessor;

    public function setUp()
    {
        $entity = new TestEntity();
        $this->accessor = new RawPropertyAccessor($entity);
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
            $this->accessor->getRawValue('prop2')
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
        $this->accessor->setRawValue('prop2', '456');
        $this->assertEquals
        (
            '456',
            $this->accessor->getRawValue('prop2')
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
            $this->accessor->getRawValue('prop3')->size()
        );

        $this->accessor->rawAddToValue('prop3', 'value');

        $this->assertEquals
        (
            1,
            $this->accessor->getRawValue('prop3')->size()
        );
    }
}
