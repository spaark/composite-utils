<?php

namespace Spaark\CompositeUtils\Test\Model;

use Spaark\CompositeUtils\Traits\AllReadableTrait;

class AllReadableComposite
{
    use AllReadableTrait;

    /**
     * @var string
     */
    protected $a = '123';

    /**
     * @var int
     */
    protected $b = 123;

    /**
     * @var boolean
     */
    protected $c = true;
}
