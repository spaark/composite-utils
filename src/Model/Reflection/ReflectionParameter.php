<?php

namespace Spaark\Core\Model\Reflection;

class ReflectionParameter extends Reflector
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
