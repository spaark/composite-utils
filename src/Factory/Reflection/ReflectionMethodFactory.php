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

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionMethod;
use \ReflectionMethod as PHPNativeReflectionMethod;

/**
 * Builds a ReflectionMethod for a given method and optionally links
 * this to a parent ReflectionComposite
 */
class ReflectionMethodFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionMethod::class;

    /**
     * @var PHPNativeReflectionMethod
     */
    protected $reflector;

    /**
     * @var ReflectionMethod
     */
    protected $object;

    /**
     * Returns a new ReflectionMethodFactory using the given class and
     * method names
     *
     * @param string $class The classname of the method
     * @param string $method The method to reflect
     * @return ReflectionMethodFactory
     */
    public static function fromName(string $class, string $method)
    {
        return new static(new PHPNativeReflectionMethod
        (
            $class, $method
        ));
    }

    /**
     * Builds the ReflectionMethod from the provided parameters,
     * optionally linking to a parent ReflectionComposite
     *
     * @param ReflectionComposite $parent The reflector for the class
     *     this method belongs to
     * @return ReflectionMethod
     */
    public function build(?ReflectionComposite $parent = null)
    {
        $this->accessor->setRawValue('owner', $parent);
        $this->accessor->setRawValue
        (
            'name',
            $this->reflector->getName()
        );

        return $this->object;
    }
}

