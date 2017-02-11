<?php

namespace Spaark\CompositeUtils\Test\Factory;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionFileFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionFile;
use Spaark\CompositeUtils\Model\Reflection\NamespaceBlock;
use Spaark\CompositeUtils\Model\Reflection\UseStatement;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Model\Collection\Collection;

class ReflectionFileFactoryTest extends TestCase
{
    const TEST_FILE = __DIR__ . '/../Model/TestEntity.php';

    private $reflect;

    private $accessor;

    public function setUp()
    {
        $this->reflect =
            (new ReflectionFileFactory(self::TEST_FILE))->build();
        $this->accessor = new RawPropertyAccessor($this->reflect);
    }

    public function testCreation()
    {
        $this->assertInstanceOf(ReflectionFile::class, $this->reflect);
    }

    public function testNamespaces()
    {
        $namespaces = $this->accessor->getRawValue('namespaces');

        $this->assertInstanceOf(Collection::class, $namespaces);
        $this->assertEquals(1, $namespaces->count());

        return $namespaces;
    }

    /**
     * @depends testNamespaces
     */
    public function testNamespaceBlock(Collection $namespaces)
    {
        $namespace = $namespaces[0];
        $this->assertInstanceOf(NamespaceBlock::class, $namespace);

        $this->assertAttributeEquals
        (
            'Spaark\CompositeUtils\Test\Model',
            'namespace',
            $namespace
        );
        $this->assertAttributeEquals
        (
            $this->reflect,
            'file',
            $namespace
        );
        $this->assertAttributeInstanceOf
        (
            Collection::class,
            'useStatements',
            $namespace
        );
        $this->assertAttributeCount(3, 'useStatements', $namespace);

        return (new RawPropertyAccessor($namespace))
            ->getRawValue('useStatements');
    }

    /**
     * @dataProvider useStatementsProvider
     * @depends testNamespaceBlock
     */
    public function testUseStatement
    (
        $i,
        $classname,
        $name,
        Collection $useStatements
    )
    {
        $useStatement = $useStatements[$i];

        $this->assertInstanceOf(UseStatement::class, $useStatement);
        $this->assertAttributeEquals
        (
            $classname,
            'classname',
            $useStatement
        );
        $this->assertAttributeEquals
        (
            $name,
            'name',
            $useStatement
        );
    }

    public function useStatementsProvider()
    {
        return [
            [
                0,
                'Some\Test\NamespacePath\ClassName',
                'ClassName'
            ],
            [
                1,
                'Some\Other\Test\ClassName',
                'AliasedClass'
            ]
        ];
    }
}
