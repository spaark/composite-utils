<?php

namespace Spaark\Core\DataSource\Reflection;

use Spaark\Core\DataSource\BaseBuilder;
use Spaark\Core\Model\Reflection\Model;
use Spaark\Core\Model\Reflection\Property;
use Spaark\Core\Model\Reflection\Method;
use Spaark\Core\Model\Reflection\Parameter;

class ReflectorFactory extends BaseBuilder
{
    public static function buildFromClass($classname)
    {
        $reflector = new \ReflectionClass($classname);
        $object = new Model();

        foreach ($reflector->getProperties() as $property)
        {
            static::buildProperty($property, $object);
        }

        foreach ($reflector->getMethods() as $method)
        {
            static::buildMethod($method, $object);
        }

        return $object;
    }

    public static function buildProperty($property, $parent)
    {
        $object = new Property();
        $parent->addToProperties($object);
        $object->name = $property->getName();
        $object->owner = $parent;
        $object->readable = true;
        $object->writable = true;
    }

    public static function buildMethod($method, $parent)
    {
        $object = new Method();
        $parent->addToMethods($method);
        $object->owner = $parent;
        $object->name = $method->getName();
        $object->final = $method->isFinal();
    }
}

