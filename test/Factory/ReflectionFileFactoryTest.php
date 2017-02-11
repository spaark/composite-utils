<?php

namespace Spaark\CompositeUtils\Test\Factory;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionFileFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionFile;
use Spaark\CompositeUtils\Model\Reflection\NamespaceBlock;
use Spaark\CompositeUtils\Model\Reflection\UseStatement;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Model\Collection\Collection;
use Spaark\CompositeUtils\Model\Collection\HashMap;

class ReflectionFileFactoryTest extends TestCase
{
    const TEST_FILE = __DIR__ . '/../Model/TestEntity.php';
    const TEST_NS = 'Spaark\CompositeUtils\Test\Model';

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

        $this->assertInstanceOf(HashMap::class, $namespaces);
        $this->assertEquals(1, $namespaces->count());

        return $namespaces;
    }

    /**
     * @depends testNamespaces
     */
    public function testNamespaceBlock(HashMap $namespaces)
    {
        $this->assertTrue($namespaces->contains(self::TEST_NS));
        
        $namespace = $namespaces[self::TEST_NS];
        $this->assertInstanceOf(NamespaceBlock::class, $namespace);

        $this->assertAttributeEquals
        (
            self::TEST_NS,
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
            HashMap::class,
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
        $classname,
        $name,
        HashMap $useStatements
    )
    {
        $this->assertTrue($useStatements->contains($name));
        $useStatement = $useStatements[$name];

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
                'Some\Test\NamespacePath\ClassName',
                'ClassName'
            ],
            [
                'Some\Other\Test\ClassName',
                'AliasedClass'
            ]
        ];
    }
}
