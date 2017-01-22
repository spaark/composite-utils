<?php

namespace Spaark\Core\Test\DataSource;

use PHPUnit\Framework\TestCase;
use Spaark\Core\Test\Model\TestEntity;

class BaseBuilderTests extends TestCase
{
    public function testbuildFrom()
    {
        $obj = TestEntityBuilder::fromId('test');

        $this->assertInstanceOf(TestEntity::class, $obj);
    }
}

