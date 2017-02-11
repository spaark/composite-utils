<?php

namespace Spaark\CompositeUtils\Test\Factory;

use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Factory\EntityCache;

class ReflectionFactoryTest extends TestCase
{
    public function testIt()
    {
        $reflectorFactory = ReflectionCompositeFactory::fromClassName
        (
            ReflectionComposite::class
        );
        $reflect = $reflectorFactory->build();

        $this->assertInstanceOf(ReflectionComposite::class, $reflect);
    }
}
