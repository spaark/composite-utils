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
use Spaark\CompositeUtils\Test\Model\TestGenericEntity;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Collection\ArrayList;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class GenericCompositeGeneratorTest extends TestCase
{
    public function testA()
    {
        $reflect = ReflectionCompositeFactory::fromClassName
        (
            TestGenericEntity::class
        )->build();

        $generic = new GenericCompositeGenerator($reflect);
        $code = $generic->generateClassCode
        (
            new StringType(),
            new ObjectType('FullClassName', '')
        );

        $this->assertContains
        (
            ArrayList::class . '_gFullClassName_e $a',
            $code
        );
        $this->assertContains('string $b', $code);
    }
}
