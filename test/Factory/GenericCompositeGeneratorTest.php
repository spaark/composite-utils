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

use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Factory\Reflection\GenericCompositeGenerator;
use Spaark\CompositeUtils\Service\GenericNameProvider;
use Spaark\CompositeUtils\Test\Model\TestGenericEntity;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Collection\ArrayList;
use Spaark\CompositeUtils\Model\Generic\GenericContext;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class GenericCompositeGeneratorTest extends TestCase
{
    /**
     * @var GenericCompositeProvider
     */
    private $genericCompositeGenerator;

    /**
     * @var ReflectionComposite
     */
    private $composite;

    /**
     * @var GenericNameProvider
     */
    private $genericNameProvider;

    /**
     * @var ObjectType
     */
    private $object;

    public function setUp() : void
    {
        $this->composite = ReflectionCompositeFactory::fromClassName
        (
            TestGenericEntity::class
        )->build();

        $this->genericCompositeGenerator
            = new GenericCompositeGenerator($this->composite);

        $this->object = new ObjectType(TestGenericEntity::class);
        $this->object->generics->add(new StringType());
        $this->object->generics->add(new ObjectType(TestEntity::class));
        $this->genericNameProvider = new GenericNameProvider
        (
            new GenericContext($this->object, $this->composite)
        );
    }

    protected function generateCode()
    {
        return $this->genericCompositeGenerator->generateClassCode
        (
            $this->object->generics[0],
            $this->object->generics[1]
        );
    }

    public function testGeneratedClassCodeName()
    {
        $code = $this->generateCode();
        $classname =
            $this->genericNameProvider->inferName($this->object);

        $this->assertNotNull
        (
            $this->genericCompositeGenerator->generatedClassName
        );

        $this->assertTrue
        (
            $this->genericCompositeGenerator->generatedClassName->equals
            (
                $classname
            )
        );

        $this->assertContains
        (
            'class ' . $classname->classname,
            $code
        );

        $this->assertContains
        (
            'namespace ' . $classname->namespace,
            $code
        );
    }

    public function testGeneratedClassCodeArguments()
    {
        $code = $this->generateCode();

        $argumentA = new ObjectType(ArrayList::class);
        $argumentA->generics->add(new ObjectType(TestEntity::class));

        $this->assertContains
        (
            (string)$this->genericNameProvider->inferName($argumentA),
            $code
        );
        $this->assertContains('string $b', $code);

        return $code;
    }

    public function testCreateClass()
    {
        $this->genericCompositeGenerator->createClass
        (
            $this->object->generics[0],
            $this->object->generics[1]
        );

        $this->assertTrue(class_exists
        (
            $this->genericNameProvider->inferName($this->object)
        ));
    }
}
