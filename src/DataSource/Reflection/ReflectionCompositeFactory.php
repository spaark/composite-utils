<?php

namespace Spaark\Core\DataSource\Reflection;

use Spaark\Core\DataSource\BaseBuilder;
use Spaark\Core\Model\Reflection\ReflectionComposite;
use Spaark\Core\Service\PropertyAccessor;
use \ReflectionClass as PHPNativeReflectionClass;

class ReflectionCompositeFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionComposite::class;

    public static function fromClassName(string $classname)
    {
        return new static(new PHPNativeReflectionClass($classname));
    }

    public function build()
    {
        foreach ($this->reflector->getProperties() as $property)
        {
            if ($this->checkIfLocal($property))
            {
                $this->buildProperty($property);
            }
        }

        foreach ($this->reflector->getMethods() as $method)
        {
            if ($this->checkIfLocal($method))
            {
                $this->buildMethod($method);
            }
        }

        return $this->object;
    }

    protected function buildProperty($reflect)
    {
        $this->accessor->rawAddToValue
        (
            'properties',
            (new ReflectionPropertyFactory($reflect))
                ->build($this->object)
        );
    }

    protected function buildMethod($reflect)
    {
        $this->accessor->rawAddToValue
        (
            'methods',
            (new ReflectionMethodFactory($reflect))
                ->build($this->object)
        );
    }

    protected function checkIfLocal($reflector)
    {
        return $reflector->class === $this->reflector->getName();
    }
}

