<?php

namespace Spaark\Core\DataSource\Reflection;

use Spaark\Core\DataSource\BaseBuilder;
use Spaark\Core\Model\Reflection\ReflectionComposite;
use Spaark\Core\Model\Reflection\ReflectionProperty;
use Spaark\Core\Model\Reflection\ReflectionParameter;
use \ReflectionProperty as PHPNativeReflectionProperty;

class ReflectionPropertyFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionProperty::class;

    public static function fromName($class, $property)
    {
        return new static(new PHPNativeReflectionProperty
        (
            $class, $property
        ));
    }

    public function build(?ReflectionComposite $parent = null)
    {
        $this->accessor->setRawValue('readable', true);
        $this->accessor->setRawValue('writable', true);
        $this->accessor->setRawValue('owner', $parent);
        $this->accessor->setRawValue
        (
            'name',
            $this->reflector->getName()
        );

        return $this->object;
    }
}

