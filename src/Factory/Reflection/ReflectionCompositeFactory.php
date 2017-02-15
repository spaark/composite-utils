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

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Factory\BaseFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use \ReflectionClass as PHPNativeReflectionClass;
use \ReflectionProperty as PHPNativeReflectionProperty;
use \ReflectionMethod as PHPNativeReflectionMethod;
use \Reflector;

/**
 * Builds a ReflectionComposite for a given class
 */
class ReflectionCompositeFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionComposite::class;

    /**
     * @var PHPNativeReflector
     */
    protected $reflector;

    /**
     * @var ReflectionComposite
     */
    protected $object;

    /**
     * Creates a new ReflectionCompositeFactory from the given
     * classname
     *
     * @param string $classname The class to build a reflect upon
     * @return ReflectionCompositeFactory
     */
    public static function fromClassName(string $classname)
    {
        return new static(new PHPNativeReflectionClass($classname));
    }

    /**
     * Builds the ReflectionComposite from the provided parameters
     *
     * @return ReflectionComposite
     */
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

    /**
     * Uses a ReflectionPropertyFactory to build a ReflectionProperty,
     * and adds that to this ReflectionComposite
     *
     * @param PHPNativeReflectionProperty
     */
    protected function buildProperty
    (
        PHPNativeReflectionProperty $reflect
    )
    {
        $properties = $this->accessor->getRawValue('properties');

        $properties[$reflect->getName()] = 
            (new ReflectionPropertyFactory($reflect))
                ->build
                (
                    $this->object,
                    $this->reflector
                        ->getDefaultProperties()[$reflect->getName()]
                );
    }

    /**
     * Uses a ReflectionMethodFactory to build a ReflectionMethod, and
     * adds that to this ReflectionComposite
     *
     * @param PHPNativeReflectionMethod
     */
    protected function buildMethod(PHPNativeReflectionMethod $reflect)
    {
        $methods = $this->accessor->getRawValue('methods');
        $methods[$reflect->getName()] =
            (new ReflectionMethodFactory($reflect))
                ->build($this->object);
    }

    /**
     * Checks if a property is defined in the class
     *
     * @param Reflector $reflector
     * @return boolean
     */
    protected function checkIfLocal(Reflector $reflector)
    {
        return $reflector->class === $this->reflector->getName();
    }
}

