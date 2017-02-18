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

use Spaark\CompositeUtils\Exception\CannotWritePropertyException;
use Spaark\CompositeUtils\Exception\CannotReadPropertyException;
use \ReflectionClass;
use \ReflectionProperty;
use \ReflectionException;

/**
 * This class is used to access the properties of a class
 */
class RawPropertyAccessor
{
    /**
     * The object being accessed
     *
     * @var object
     */
    protected $object;

    /**
     * A PHP Reflector for access
     *
     * @var ReflectionClass
     */
    protected $reflector;

    /**
     * Creates a RawPropertyAccessor for the given object
     *
     * @param object $object The object to access
     */
    public function __construct($object)
    {
        $this->object = $object;
        $this->reflector = new ReflectionClass($object);
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
     * Adds a value to a property, irrespective of access permissions
     *
     * @param string $key The property to write to
     * @param mixed $value The value to add
     * @deprecated
     * @throws CannotWritePropertyException In the event the property
     *    does not exist
     */ 
    public function rawAddToValue($key, $value)
    {
        $property = $this->getPropertyOrFail
        (
            $key,
            CannotWritePropertyException::class
        );
        $property->getValue($this->object)->add($value);
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
