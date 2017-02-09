<?php

namespace Spaark\Core\Service;

use Spaark\Core\Model\Reflection\Model as ReflectionModel;
use Spaark\Core\Exception\CannotWritePropertyException;
use Spaark\Core\Exception\CannotReadPropertyException;
use \ReflectionClass;
use \ReflectionProperty;
use \ReflectionException;

/**
 * This class is used to access the properties of a class
 */
class PropertyAccessor
{
    /**
     * The object being accessed
     *
     * @var object
     */
    protected $object;

    /**
     * The reflection entity for the object in question
     *
     * @var ReflectionModel
     */
    protected $reflect;

    /**
     * A PHP Reflector for access
     *
     * @var ReflectionClass
     */
    protected $reflector;

    /**
     * @param object $object The object to access
     * @param ReflectionModel|null $reflect A reflection entity
     */
    public function __construct($object, ?ReflectionModel $reflect)
    {
        $this->object = $object;
        $this->reflect = $reflect;
        $this->reflector = new ReflectionClass($object);
    }

    /**
     * Builds and returns a PropertyAccessor, optionally with a
     * reflection entity
     *
     * If a reflection entity is not provided, one will be built from
     * the object in question
     *
     * @param object $object The object to access
     * @param ReflectionModel|null $reflect The reflection entity
     * @return PropertyAccessor
     */
    public static function factory(
        $object,
        ?ReflectionModel $reflect = null
    )
    {
        if (!$reflect)
        {
            $reflect = ReflectionModel::fromClass($object);
        }

        return new static($object, $reflect);
    }

    /**
     * Access a property and sets it with the given value, irrespective
     * of access permissions
     *
     * @param string $key The property to write to
     * @param mixed $value The value to set
     * @throws CannotWritePropertyException In the event the property
     *     does not exist
     */
    public function setRawValue($key, $value)
    {
        $this->getPropertyOrFail
        (
            $key,
            CannotWritePropertyException::class
        )
        ->setValue($this->object, $value);
    }

    /**
     * Access a property and returns its value, irresepective of access
     * permissions
     *
     * @param string $key The property to read
     * @return mixed The property's value
     * @throws CannotReadPropertyException In the event the property
     *     does not exist
     */
    public function getRawValue($key)
    {
        return
            $this->getPropertyOrFail
            (
                $key,
                CannotReadPropertyException::class
            )
            ->getValue($this->object);
    }

    /**
     * Checks if a property exists and returns its ReflectionProperty
     *
     * @param string $key The property to access
     * @param string $class The name of the exception to throw on error
     * @throws $class If the property does not exist
     * @return ReflectionProperty
     */
    private function getPropertyOrFail($key, $class)
    {
        try
        {
            $prop = $this->reflector->getProperty($key);
            $prop->setAccessible(true);

            return $prop;
        }
        catch (ReflectionException $e)
        {
            throw new $class
            (
                get_class($this->object),
                $key,
                $e
            );
        }
    }
}
