<?php

namespace Spaark\Core\Model\Reflection\Type;

class ObjectType extends AbstractType
{
    /**
     * @readable
     * @var string
     */
    protected $classname;

    public function __construct(string $classname)
    {
        $this->classname = $classname;
    }
}
