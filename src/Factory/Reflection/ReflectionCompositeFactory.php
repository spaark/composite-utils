<?php

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Factory\BaseFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
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
        $file = 
            (new ReflectionFileFactory($this->reflector->getFileName()))
                ->build();
        $this->accessor->setRawValue('file', $file);
        $this->accessor->setRawValue
        (
            'classname',
            $this->reflector->name
        );
        $this->accessor->setRawValue
        (
            'namespace',
            $file->namespaces[$this->reflector->getNamespaceName()]
        );

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
        $properties = $this->accessor->getRawValue
        (
            'properties'
        );

        $properties[$reflect->getName()] = 
            (new ReflectionPropertyFactory($reflect))
                ->build
                (
                    $this->object,
                    $this->reflector
                        ->getDefaultProperties()[$reflect->getName()]
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

