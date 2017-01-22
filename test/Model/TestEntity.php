<?php

namespace Spaark\Core\Test\Model;

use Spaark\Core\Model\Base\Entity;

class TestEntity extends Entity
{
    /**
     * @var int
     * @readable
     * @writable
     */
    protected $id;

    public function __construct()
    {
        //
    }
}
