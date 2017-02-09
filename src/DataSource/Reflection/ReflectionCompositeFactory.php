<?php

namespace Spaark\Core\DataSource\Reflection;

use Spaark\Core\DataSource\BaseBuilder;
use Spaark\Core\Model\Reflection\ReflectionComposite;
use Spaark\Core\Model\Reflection\ReflectionProperty;
use Spaark\Core\Model\Reflection\ReflectionMethod;
use Spaark\Core\Model\Reflection\ReflectionParameter;
use Spaark\Core\Service\PropertyAccessor;

class ReflectionCompositeFactory extends BaseBuilder
{
    protected $classname;

    protected $reflector;

    protected $object;

    protected $accessor;

    public function __construct(string $classname)
    {
        $this->classname = $classname;
        $this->reflector = new \ReflectionClass($this->classname);
        $this->object = new ReflectionComposite();
        $this->accessor = new PropertyAccessor($this->object, null);
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

    protected function buildProperty($propertyReflector)
    {
        $prop = new ReflectionProperty();
        $accessor = new PropertyAccessor($prop, null);

        $this->accessor->rawAddToValue('properties', $prop);

        $accessor->setRawValue('name', $propertyReflector->getName());
        $accessor->setRawValue('owner', $this->object);
        $accessor->setRawValue('readable', true);
        $accessor->setRawValue('writable', true);
    }

    protected function buildMethod($methodReflector)
    {
        $method = new ReflectionMethod();
        $accessor = new PropertyAccessor($method, null);

        $this->accessor->rawAddToValue('methods', $methodReflector);

        $accessor->setRawValue('name', $methodReflector->getName());
        $accessor->setRawValue('owner', $this->object);
        $accessor->setRawValue('final', true);
    }

    protected function checkIfLocal($reflector)
    {
        return $reflector->class === $this->classname;
    }
}

