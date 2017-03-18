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

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionFileFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionFile;
use Spaark\CompositeUtils\Model\Reflection\NamespaceBlock;
use Spaark\CompositeUtils\Model\Reflection\UseStatement;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;

class ReflectionFileFactoryTest extends TestCase
{
    const TEST_FILE = __DIR__ . '/../Model/TestEntity.php';
    const TEST_NS = 'Spaark\CompositeUtils\Test\Model';

    public function testReflectionFile()
    {
        $file = (new ReflectionFileFactory(self::TEST_FILE))->build();
        $this->assertInstanceOf(ReflectionFile::class, $file);

        return $file;
    }

    /**
     * @depends testReflectionFile
     */
    public function testNamespaces(ReflectionFile $file)
    {
        $namespaces = $file->namespaces;

        $this->assertInstanceOf(HashMap::class, $namespaces);
        $this->assertEquals(1, $namespaces->count());

        return [$file, $namespaces];
    }

    /**
     * @depends testNamespaces
     */
    public function testNamespaceBlock(array $data)
    {
        $file = $data[0];
        $namespaces = $data[1];

        $this->assertTrue($namespaces->containsKey(self::TEST_NS));
        
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
            $file,
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

        return $namespace->useStatements;
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
        $this->assertTrue($useStatements->containsKey($name));
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
