<?php

namespace Spaark\CompositeUtils\Test\Model;

use Some\Test\NamespacePath\ClassName;
use Some\Other\Test\ClassName as AliasedClass;
use Spaark\CompositeUtils\Model\Collection\Collection;

class TestEntity 
{
    /**
     * @var string
     * @readable
     * @writable
     */
    protected $id = 'foo';

    /**
     * @var ?string
     */
    protected $property = '123';

    /**
     * @var Collection
     */
    protected $arrayProperty;

    public function __construct()
    {
        $this->arrayProperty = new Collection();
    }
}
