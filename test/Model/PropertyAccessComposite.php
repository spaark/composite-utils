<?php

namespace Spaark\CompositeUtils\Test\Model;

use Spaark\CompositeUtils\Traits\PropertyAccessTrait;

class PropertyAccessComposite
{
    use PropertyAccessTrait;

    /**
     * @var string
     * @readable
     */
    protected $a = '123';

    /**
     * @var int
     * @writable
     */
    protected $b = 123;

    /**
     * @var boolean
     * @readable
     * @writable
     */
    protected $c = true;
}
