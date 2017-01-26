<?php

namespace Spaark\Core\Model\Reflection;

class Parameter extends Reflector
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Method
     */
    protected $owner;

    /**
     * @var string
     */
    protected $type;
}
