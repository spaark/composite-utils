<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Service;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Exception\CannotWritePropertyException;
use Spaark\CompositeUtils\Exception\IllegalPropertyTypeException;
use Spaark\CompositeUtils\Exception\MissingRequiredParameterException;

/**
 * This class is used to access properties of a composite and enforce
 * data type requirements
 */
class PropertyAccessor extends RawPropertyAccessor
{
    /**
     * Reflection information about the composite being accessed
     *
     * @var ReflectionComposite
     */
    protected $reflect;

    /**
     * Creates the PropertyAccessor for the given object, using the
     * given reflection information
     *
     * @param object $object The object to access
     * @param ReflectionComposite $reflect Reflection information about
     *     the composite
     */
    public function __construct($object, ReflectionComposite $reflect)
    {
        parent::__construct($object);

        $this->reflect = $reflect;
    }

    /**
     * Initializes the given object with the given parameters, enforcing
     * the constructor requirements and auto building any left overs
     */
    public function constructObject(...$args)
    {
        $i = 0;
        foreach ($this->reflect->requiredProperties as $property)
        {
            if (!isset($args[$i]))
            {
                throw new MissingRequiredParameterException
                (
                    get_class($this->object),
                    $property->name
                );
            }

            $this->setAnyValue($property, $args[$i]);

            $i++;
        }

        $building = false;
        foreach ($this->reflect->optionalProperties as $property)
        {
            if ($building)
            {
                $this->buildProperty($property);
            }
            else
            {
                if (isset($args[$i]))
                {
                    $this->setAnyValue($property, $args[$i]);
                    $i++;
                }
                else
                {
                    $building = true;
                    $this->buildProperty($property);
                }
            }
        }

        foreach ($this->reflect->builtProperties as $property)
        {
            $this->buildProperty($property);
        }
    }

    /**
     * Builds a property automatically
     *
     * @param ReflectionProperty $property The property to build
     */
    protected function buildProperty(ReflectionProperty $property)
    {
        if (!$property->type instanceof ObjectType)
        {
            $this->setAnyValue($property, 0);
        }
        elseif ($property->builtInConstructor)
        {
            $class = $property->type->classname;
            $this->setRawValue($property->name, new $class());
        }
    }

    /**
     * Returns the value of the property
     *
     * @param string $property The name of the property to get
     * @return mixed The value of the property
     */
    public function getValue(string $property)
    {
        return $this->getRawValue($property);
    }

    /**
     * Sets the value of a property, enforcing datatype requirements
     *
     * @param string $property The name of the property to set
     * @param mixed $value The value to set
     */
    public function setValue(string $property, $value)
    {
        if (!$this->reflect->properties->containsKey($property))
        {
            throw new CannotWritePropertyException
            (
                get_class($this->object), $property
            );
        }

        $this->setAnyValue
        (
            $this->reflect->properties[$property],
            $value
        );
    }

    /**
     * Sets the value of a property, enforcing datatype requirements
     *
     * @param ReflectionProperty $property The property to set
     * @param mixed $value The value to set
     */
    protected function setAnyValue(ReflectionProperty $property, $value)
    {
        if (is_null($value))
        {
            $this->setNullValue($property);
        }
        else
        {
            $this->setNonNullValue($property, $value);
        }
    }

    /**
     * Attempts to set a property with a null value
     *
     * @param ReflectionProperty $property The property to set
     */
    private function setNullValue(ReflectionProperty $property)
    {
        if ($property->type->nullable)
        {
            $this->setRawValue($property->name, null);
        }
        else
        {
            $this->throwError($property, 'NonNull', null);
        }
    }

    /**
     * Attempts to set a property with a non null value
     *
     * @param ReflectionProperty $property The property to set
     * @param mixed $value The value to set
     */
    private function setNonNullValue
    (
        ReflectionProperty $property,
        $value
    )
    {
        switch (get_class($property->type))
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
                $this->setCollectionValue($property, $value);
                break;
            case ObjectType::class:
                $this->setObjectValue($property, $value);
                break;
        }
    }

    /**
     * Attempts to set a property which expects a scalar value
     *
     * @param ReflectionProperty $property The property to set
     * @param mixed $value The value to set
     * @param string $name The name of the scalar type
     * @param callable $cast Method to cast a value to the scalar data
     *     type
     */
    private function setScalarValue
    (
        ReflectionProperty $property,
        $value,
        string $name,
        callable $cast
    )
    {
        $method = '__to' . $name;

        if (is_scalar($value))
        {
            $this->setRawValue($property->name, $cast($value));
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

    /**
     * Attempts to set a property which expects an object value
     *
     * @param ReflectionProperty $property The property to set
     * @param mixed $value The value to set
     */
    private function setObjectValue
    (
        ReflectionProperty $property,
        $value
    )
    {
        if (is_a($value, $property->type->classname))
        {
            $this->setRawValue($property->name, $value);
        }
        else
        {
            $this->throwError
            (
                $property,
                $property->type->classname,
                $value
            );
        }
    }

    /**
     * Attempts to set a property which expects a collection value
     *
     * @param ReflectionProperty $property The property to set
     * @param mixed The value to set
     */
    private function setCollectionValue
    (
        ReflectionProperty $property,
        $value
    )
    {
        if (is_a($value, \ArrayAccess::class) || is_array($value))
        {
            $this->setRawValue($property->name, $value);
        }
        else
        {
            $this->throwError($property, 'Collection', $value);
        }
    }

    /**
     * Throws an IlleglPropertyTypeException
     *
     * @param ReflectionProperty $property The property being set
     * @param string $expected The expected datatype
     * @param string $value The value being set
     */
    private function throwError
    (
        ReflectionProperty $property,
        string $expected,
        $value
    )
    {
        throw new IllegalPropertyTypeException
        (
            get_class($this->object),
            $property->name,
            $expected,
            is_object($value) ? get_class($value) : gettype($value)
        );
    }
}
