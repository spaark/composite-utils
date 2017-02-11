<?php

namespace Spaark\CompositeUtils\Test\Model;

use Spaark\CompositeUtils\Model\Collection\Collection;

class TestEntity 
{
    /**
     * @var int
     * @readable
     * @writable
     */
    protected $id;

    /**
     * @var string
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
