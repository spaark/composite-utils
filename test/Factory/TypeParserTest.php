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

use Spaark\CompositeUtils\Factory\Reflection\TypeParser;
use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\FloatType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Model\Reflection\Type\NullType;
use Spaark\CompositeUtils\Model\Reflection\NamespaceBlock;
use Spaark\CompositeUtils\Model\Reflection\UseStatement;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;

/**
 *
 */
class TypeParserTest extends TestCase
{
    /**
     * @dataProvider superTypeProvider
     */
    public function testSuperType($type, $class, $nonNull)
    {
        $parser = new TypeParser();

        $nonNullType = $parser->parse($type);
        $access = new RawPropertyAccessor($nonNullType);
        $this->assertInstanceOf($class, $nonNullType);
        $this->assertSame($nonNull, $access->getRawValue('nullable'));

        $collectionType = $parser->parse($type . '[]');
        $access = new RawPropertyAccessor($collectionType);
        $this->assertInstanceOf(CollectionType::class, $collectionType);
        $this->assertInstanceOf($class, $access->getRawValue('of'));

        $nullType = $parser->parse('?' . $type);
        $access = new RawPropertyAccessor($nullType);
        $this->assertInstanceOf($class, $nullType);
        $this->assertTrue($access->getRawValue('nullable'));
    }

    public function testParserWithContext()
    {
        $reflectionComposite = new ReflectionComposite();
        $accessor = new RawPropertyAccessor($reflectionComposite);
        $namespace = new NamespaceBlock('');
        $accessor->setRawValue('namespace', $namespace);
        $namespace->useStatements['class'] =
            new UseStatement('full\class', 'class');
        $parser = new TypeParser($reflectionComposite);

        $type = $parser->parse('class');
        $this->assertInstanceOf(ObjectType::class, $type);
        $this->assertSame('full\class', $type->classname->__toString());
    }

    /**
     * @dataProvider badCollectionProvider
     */
    public function testMalformedCollection(string $string, string $msg)
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($msg);

        (new TypeParser())->parse($string);
    }

    /**
     * @dataProvider dataTypeProvider
     */
    public function testParseFromType($value, string $type)
    {
        $parser = new TypeParser();
        $this->assertInstanceOf($type, $parser->parseFromType($value));
    }

    public function badCollectionProvider()
    {
        return
        [
            ['endOfFile[', 'Unexpected EOF'],
            ['WeirdItem[lol]', '[ must be followed by ]'],
            ['nonsense[]e', 'Unexpected char after collection']
        ];
    }

    public function nonObjectProvider()
    {
        return
        [
            ['string'],
            ['int'],
            ['integer'],
            ['bool'],
            ['boolean'],
            ['mixed'],
            ['float'],
            ['null']
        ];
    }

    public function superTypeProvider()
    {
        return
        [
            ['string', StringType::class, false],
            ['int', IntegerType::class, false],
            ['integer', IntegerType::class, false],
            ['bool', BooleanType::class, false],
            ['boolean', BooleanType::class, false],
            ['float', FloatType::class, false],
            ['', MixedType::class, true],
            ['mixed', MixedType::class, true],
            ['null', NullType::class, true],
            ['Something', ObjectType::class, false]
        ];
    }

    public function dataTypeProvider()
    {
        return
        [
            ['abc', StringType::class],
            [123, IntegerType::class],
            [1.3, FloatType::class],
            [true, BooleanType::class],
            [false, BooleanType::class],
            [null, NullType::class],
            [new TestEntity(), ObjectType::class]
        ];
    }
}
