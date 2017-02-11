<?php

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionMethod;
use Spaark\CompositeUtils\Model\Reflection\ReflectionParameter;
use \ReflectionMethod as PHPNativeReflectionMethod;

class ReflectionMethodFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionMethod::class;

    public static function fromName($class, $method)
    {
        return new static(new PHPNativeReflectionMethod
        (
            $class, $method
        ));
    }

    public function build(?ReflectionComposite $parent = null)
    {
        $this->accessor->setRawValue('owner', $parent);
        $this->accessor->setRawValue
        (
            'name',
            $this->reflector->getName()
        );

        return $this->object;
    }
}

