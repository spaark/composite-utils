<?php

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\ReflectionParameter;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use \ReflectionProperty as PHPNativeReflectionProperty;

class ReflectionPropertyFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionProperty::class;

    protected $acceptedParams =
    [
        'readable' => 'setBool',
        'writable' => 'setBool',
        'var' => 'setType'
    ];

    public static function fromName($class, $property)
    {
        return new static(new PHPNativeReflectionProperty
        (
            $class, $property
        ));
    }

    public function build(ReflectionComposite $parent, $default)
    {
        $this->accessor->setRawValue('owner', $parent);
        $this->accessor->setRawValue('defaultValue', $default);
        $this->accessor->setRawValue
        (
            'name',
            $this->reflector->getName()
        );

        $this->parseDocComment();

        return $this->object;
    }

    protected function setType($name, $value)
    {
        if ($value{0} !== '?')
        {
            $nullable = false;
        }
        else
        {
            $nullable = true;
            $value = substr($value, 1);
        }

        if (substr($value, -2) !== '[]')
        {
            $collection = false;
        }
        else
        {
            $collection = true;
            $value = substr($value, 0, -2);
        }

        switch ($value)
        {
            case 'string':
                $class = new StringType();
                break;
            case 'int':
            case 'integer':
                $class = new IntegerType();
                break;
            case 'bool':
            case 'boolean':
                $class = new BooleanType();
                break;
            case 'mixed':
            case '':
                $class = new MixedType();
                break;
            case 'null':
                $class = new NullType();
                break;
            default:
                $class = new ObjectType($value);
        }

        if ($nullable)
        {
            (new RawPropertyAccessor($class))
                ->setRawValue('nullable', $value);
        }

        if ($collection)
        {
            $class = new CollectionType($class);
        }

        $this->accessor->setRawValue('type', $class);
    }
}

