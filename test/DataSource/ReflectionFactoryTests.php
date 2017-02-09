<?php

namespace Spaark\Core\Test\DataSource;

use Spaark\Core\DataSource\Reflection\ReflectionCompositeFactory;
use Spaark\Core\Test\Model\TestEntity;
use PHPUnit\Framework\TestCase;
use Spaark\Core\Model\Reflection\ReflectionComposite;
use Spaark\Core\DataSource\EntityCache;

class ReflectionFactoryTests extends TestCase
{
    public function testIt()
    {
        $reflectorFactory = new ReflectionCompositeFactory
        (
            ReflectionComposite::class
        );
        $reflect = $reflectorFactory->build();

        $this->assertInstanceOf(ReflectionComposite::class, $reflect);
    }
}
