<?php

namespace Spaark\CompositeUtils\Model\Reflection;

class UseStatement extends Reflector
{
    /**
     * @var string
     * @readable
     */
    protected $classname;

    /**
     * @var string
     * @readable
     */
    protected $name;

    public function __construct(string $classname, string $name)
    {
        $this->classname = $classname;
        $this->name = $name;
    }
}
