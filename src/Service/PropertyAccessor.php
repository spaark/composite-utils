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
use Spaark\CompositeUtils\Model\Reflection\Type\ScalarType;
use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Exception\CannotWritePropertyException;
use Spaark\CompositeUtils\Exception\IllegalPropertyTypeException;
use Spaark\CompositeUtils\Exception\MissingRequiredParameterException;
use Spaark\CompositeUtils\Factory\Reflection\TypeParser;
use Spaark\CompositeUtils\Service\TypeComparator;
use Spaark\CompositeUtils\Traits\Generic;

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
            $class = (string)$property->type->classname;
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
        $comparator = new TypeComparator();

        if ($value instanceof Generic)
        {
            $valueType = $value->getObjectType();
        }
        else
        {
            $valueType = (new TypeParser())->parseFromType($value);
        }

        if ($comparator->compatible($property->type, $valueType))
        {
            $this->setRawValue($property->name, $value);
        }
        elseif ($property->type instanceof ScalarType)
        {
            $this->setScalarValue($property, $valueType, $value);
        }
        else
        {
            $this->throwError($property, $valueType);
        }
    }

    /**
     * Attempts to set a property which expects a scalar value
     *
     * @param ReflectionProperty $property The property to set
     * @param ScalarType $valueType The scalar type
     * @param mixed $value The value to set
     */
    private function setScalarValue
    (
        ReflectionProperty $property,
        ScalarType $valueType,
        $value
    )
    {
        $method = '__to' . $valueType;

        if (is_scalar($value))
        {
            $this->setRawValue
            (
                $property->name,
                $property->type->cast($value)
            );
        }
        elseif (is_object($value) && method_exists([$value, $method]))
        {
            $this->setScalarValue
            (
                $property,
                $valueType,
                $value->$method()
            );
        }
        else
        {
            $this->throwError($property, $valueType);
        }
    }

    /**
     * Throws an IlleglPropertyTypeException
     *
     * @param ReflectionProperty $property The property being set
     * @param AbstractType $valueType The value being set
     */
    private function throwError
    (
        ReflectionProperty $property,
        AbstractType $valueType
    )
    {
        throw new IllegalPropertyTypeException
        (
            get_class($this->object),
            $property->name,
            $property->type,
            $valueType
        );
    }
}
