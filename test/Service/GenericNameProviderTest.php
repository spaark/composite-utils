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
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Test\Model\DummyType;
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\FloatType;
use Spaark\CompositeUtils\Model\Reflection\Type\GenericType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Service\GenericNameProvider;
use Spaark\CompositeUtils\Factory\Reflection\TypeParser;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Model\Generic\GenericContext;

class GenericNameProviderTest extends TestCase
{
    /**
     * @dataProvider scalarProvider
     */
    public function testScalars($type, $string)
    {
        $this->assertSame
        (
            $string,
            (new GenericNameProvider())->inferName($type)
        );
    }

    public function testUnknownType()
    {
        $this->expectException(\DomainException::class);

        (new GenericNameProvider())->inferName(new DummyType());
    }

    /**
     * @expectedException \Spaark\CompositeUtils\Exception\MissingContextException
     */
    public function testGenericWithoutContext()
    {
        (new GenericNameProvider())->inferName(new GenericType('name'));
    }

    public function testGenericWithContext()
    {
        $object = new ObjectType('', '');
        $object->generics->add(new ObjectType('TypeClassName', ''));
        $context = new GenericContext
        (
            $object,
            ReflectionCompositeFactory::fromClassName(TestEntity::class)
                ->build()
        );

        $this->assertSame
        (
            'TypeClassName',
            (string)(new GenericNameProvider($context))
                ->inferName(new GenericType('TypeA'))
        );
    }

    public function testGenericName()
    {
        $factory = ReflectionCompositeFactory::fromClassName
        (
            TestEntity::class
        );
        $object = (new TypeParser($factory->build()))->parse
        (
            'TestEntity<int, HashMap<string, TestEntity>>'
        );

        $this->assertSame
        (
            'Spaark\CompositeUtils\Generic\\'
            . TestEntity::class . '_gint_cSpaark\CompositeUtils\Generic'
            . '\\' . HashMap::class . '_g'
            . 'string_c' . TestEntity::class . '_e_e',
            (string)(new GenericNameProvider())->inferName($object)
        );
    }

    public function scalarProvider()
    {
        return
        [
            [new IntegerType(), 'int'],
            [new StringType(), 'string'],
            [new BooleanType(), 'bool'],
            [new FloatType(), 'float'],
            [new MixedType(), '']
        ];
    }
}
