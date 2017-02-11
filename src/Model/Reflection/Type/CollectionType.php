<?php

namespace Spaark\CompositeUtils\Model\Reflection\Type;

class ObjectType extends AbstractType
{
    /**
     * @readable
     * @var AbstractType
     */
    protected $of;

    public function __construct(AbstractType $of)
    {
        $this->of = $of;
    }
}
