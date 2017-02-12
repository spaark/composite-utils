<?php

namespace Spaark\CompositeUtils\Service;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\FloatType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Exception\CannotWritePropertyException;
use Spaark\CompositeUtils\Exception\IllegalPropertyTypeException;

class PropertyAccessor extends RawPropertyAccessor
{
    /**
     * @var ReflectionComposite
     */
    private $reflect;

    public function __construct($object, ReflectionComposite $reflect)
    {
        parent::__construct($object);

        $this->reflect = $reflect;
    }

    public function getValue($property)
    {
        return $this->getRawValue($property);
    }

    public function setValue($property, $value)
    {
        if (!$this->reflect->properties->contains($property))
        {
            throw new CannotWritePropertyException
            (
                get_class($this->object), $property
            );
        }

        $type = $this->reflect->properties[$property]->type;

        if (is_null($value))
        {
            $this->setNullValue($property, $type);
        }
        else
        {
            $this->setNonNullValue($property, $value, $type);
        }
    }

    private function setNullValue($property, $type)
    {
        if ($type->nullable)
        {
            $this->setRawValue($property, null);
        }
        else
        {
            $this->throwError($property, 'NonNull', null);
        }
    }

    private function setNonNullValue($property, $value, $type)
    {
        switch (get_class($type))
        {
            case MixedType::class:
                $this->setRawValue($property, $value);
                break;
            case IntegerType::class:
                $this->setScalarValue($property, $value, 'Integer',
                    function($value)
                    {
                        return (integer)$value;
                    });
                break;
            case StringType::class:
                $this->setScalarValue($property, $value, 'String',
                    function($value)
                    {
                        return (string)$value;
                    });
                break;
            case BooleanType::class:
                $this->setScalarValue($property, $value, 'Boolean',
                    function($value)
                    {
                        return (boolean)$value;
                    });
                break;
            case CollectionType::class:
                $this->setCollectionValue($property, $value, $type);
                break;
            case ObjectType::class:
                $this->setObjectValue($property, $value, $type);
                break;
        }
    }

    private function setScalarValue($property, $value, $name, $cast)
    {
        $method = '__to' . $name;

        if (is_scalar($value))
        {
            $this->setRawValue($property, $cast($value));
        }
        elseif (is_object($value) && method_exists([$value, $method]))
        {
            $this->setScalarValue
            (
                $property,
                $value->$method(),
                $method,
                $cast
            );
        }
        else
        {
            $this->throwError($property, $name, $value);
        }
    }

    private function setObjectValue($property, $value, $type)
    {
        if (is_a($value, $type->classname))
        {
            $this->setRawValue($property, $value);
        }
        else
        {
            $this->throwError($property, $type->classname, $value);
        }
    }

    private function setCollectionValue($property, $value, $type)
    {
        if (is_a($value, \ArrayAccess::class) || is_array($value))
        {
            $this->setRawValue($property, $value);
        }
        else
        {
            $this->throwError($property, 'Collection', $value);
        }
    }

    private function throwError($property, $expected, $value)
    {
        throw new IllegalPropertyTypeException
        (
            get_class($this->object),
            $property,
            $expected,
            is_object($value) ? get_class($value) : gettype($value)
        );
    }
}
