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
use Spaark\CompositeUtils\Service\TypeComparator;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\NullType;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Test\Model\DummyType;
use Spaark\CompositeUtils\Test\Model\InheritedEntity;

class TypeComparatorTest extends TestCase
{
    public function testMixed()
    {
        $comparator = new TypeComparator();
        $mixed = new MixedType();
        $a = new StringType();
        $b = new ObjectType('something', '');

        $this->assertTrue($comparator->compatible($mixed, $a));
        $this->assertTrue($comparator->compatible($mixed, $b));
    }

    public function testNull()
    {
        $comparator = new TypeComparator();
        $string = new StringType();
        $accessor = new RawPropertyAccessor($string);
        $accessor->setRawValue('nullable', true);
        $null = new NullType();

        $this->assertTrue($comparator->compatible($string, $null));
    }

    /**
     * @dataProvider scalarProvider
     */
    public function testScalars(string $classname)
    {
        $comparator = new TypeComparator();
        $a = new $classname();
        $b = new $classname();

        $this->assertTrue($comparator->compatible($a, $b));
    }

    public function testUnknownType()
    {
        $this->expectException(\DomainException::class);

        (new TypeComparator())
            ->compatible(new DummyType(), new DummyType());
    }

    public function testIncompatibleScalars()
    {
        $comparator = new TypeComparator();
        $a = new StringType();
        $b = new IntegerType();
        $c = new MixedType();
        $d = new ObjectType('', '');

        $this->assertFalse($comparator->compatible($a, $b));
        $this->assertFalse($comparator->compatible($a, $c));
        $this->assertFalse($comparator->compatible($a, $d));
    }

    public function testObject()
    {
        $comparator = new TypeComparator();
        $a = new ObjectType(TestEntity::class, '');
        $b = new ObjectType(TestEntity::class, '');
        $c = new ObjectType(InheritedEntity::class, '');
        $d = new IntegerType();

        $this->assertTrue($comparator->compatible($a, $b));
        $this->assertTrue($comparator->compatible($a, $c));
        $this->assertFalse($comparator->compatible($c, $a));
        $this->assertFalse($comparator->compatible($b, $d));
    }

    public function scalarProvider()
    {
        return
        [
            [StringType::class],
            [IntegerType::class],
            [BooleanType::class]
        ];
    }
}
