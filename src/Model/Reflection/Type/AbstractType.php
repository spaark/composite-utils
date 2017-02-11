<?php

namespace Spaark\CompositeUtils\Model\Reflection\Type;

use Spaark\CompositeUtils\Model\Base\Composite;
use Spaark\CompositeUtils\Traits\AllReadableTrait;

class AbstractType
{
    use AllReadableTrait;

    /**
     * @readable
     * @var boolean
     */
    protected $nullable = false;
}
