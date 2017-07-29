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

namespace Spaark\CompositeUtils\Test\Traits;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Test\Model\GenericComposite;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Generic\GenericContext;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use \StdClass;
use \ReflectionClass;

class GenericTraitTest extends TestCase
{
    /**
     */
    public function testGetObjectType()
    {
        $testComposite = new GenericComposite();

        $object = $testComposite->getObjectType();
        $this->assertInstanceOf(ObjectType::class, $object);
        $this->assertSame($object, $testComposite->getObjectType());
    }

    /**
     * Test
     */
    public function testGetGenericContext()
    {
        $testComposite = new GenericComposite();

        $context = $testComposite->getGenericContext();
        $this->assertInstanceOf(GenericContext::class, $context);
        $this->assertSame(
            $context,
            $testComposite->getGenericContext()
        );
    }

    /**
     */
    public function testSetGenericContext()
    {
        $testComposite = new GenericComposite();
        $context = $this->makeGenericContext();

        $testComposite->setGenericContext($context);
        $this->assertSame(
            $context,
            $testComposite->getGenericContext()
        );

        return $testComposite;
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\ImmutablePropertyException
     * @depends testSetGenericContext
     */
    public function testSetGenericContextImmutable(
        GenericComposite $testComposite
    )
    {
        $testComposite->setGenericContext($this->makeGenericContext());
    }

    /**
     * @depends testSetGenericContext
     */
    public function testGetInstanceOfGeneric(
        GenericComposite $testComposite
    )
    {
        $method = (new ReflectionClass($testComposite))->getMethod(
            'getInstanceOfGeneric'
        );

        $method->setAccessible(true);

        $this->assertInstanceOf(
            StdClass::class,
            $method->invokeArgs($testComposite, ['A'])
        );
    }

    /**
     */
    private function makeGenericContext()
    {
        $reflector = new ReflectionComposite();
        $reflector->generics['A'] = new ObjectType('test');

        $object = new ObjectType('test');
        $object->generics[] = new ObjectType(StdClass::class);

        return new GenericContext($object, $reflector);
    }
}
