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
use Spaark\CompositeUtils\Model\Collection\HashMap;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Service\GenericNameProvider;
use Spaark\CompositeUtils\Factory\Reflection\TypeParser;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;

class GenericNameProviderTest extends TestCase
{
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
            (new GenericNameProvider())->inferName($object)
        );
    }
}
