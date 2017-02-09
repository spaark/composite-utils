<?php

namespace Spaark\Core\Test\Model;

use Spaark\Core\Model\Base\Entity;
use Spaark\Core\Model\Collection\Collection;

class TestEntity extends Entity
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
