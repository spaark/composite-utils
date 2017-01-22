<?php

namespace Spaark\Core\Test\DataSource;

use Spaark\Core\DataSource\BaseBuilder;
use Spaark\Core\Test\Model\TestEntity;

class TestEntityBuilder extends BaseBuilder
{
    public static function buildFromId($id)
    {
        $obj = new TestEntity();
        
        return $obj;
    }
}
