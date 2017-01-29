<?php

namespace Spaark\Core\Test\DataSource;

use Spaark\Core\DataSource\Reflection\ReflectorFactory;
use Spaark\Core\Test\Model\TestEntity;
use PHPUnit\Framework\TestCase;
use Spaark\Core\Model\Reflection\Model;

class ReflectionBuilderTest extends TestCase
{
    public function testIt()
    {
        $reflect = ReflectorFactory::buildFromClass(TestEntity::class);

        $this->assertInstanceOf(Model::class, $reflect);
    }
}
