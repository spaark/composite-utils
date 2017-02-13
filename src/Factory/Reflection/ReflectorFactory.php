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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Factory\BaseFactory;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Model\Reflection\Reflector as SpaarkReflector;
use \Reflector as PHPNativeReflector;

/**
 * Abstract class for specific Reflection factories to extend
 */
abstract class ReflectorFactory extends BaseFactory
{
    const REFLECTION_OBJECT = null;

    /**
     * @var PHPNativeReflector
     */
    protected $reflector;

    /**
     * @var RawPropertyAccessor
     */
    protected $accessor;

    /**
     * @var SpaarkReflector
     */
    protected $object;

    /**
     * Array of acceptable annotations and what methods to call to set
     * them
     *
     * @var array
     */
    protected $acceptedParams = [];

    /**
     * Creates a new ReflectorFactory using the provided native PHP
     * reflector as a basis
     *
     * @param PHPNativeReflector $reflector The reflector used to
     *     parse the item
     */
    public function __construct(PHPNativeReflector $reflector)
    {
        $class = static::REFLECTION_OBJECT;

        $this->object = new $class();
        $this->accessor = new RawPropertyAccessor($this->object);
        $this->reflector = $reflector;
    }

    /**
     * Parses the docblock comment for this item and searches for
     * annotations
     */
    protected function parseDocComment()
    {
        preg_match_all
        (
              '/^'
            .     '[ \t]*\*[ \t]*'
            .     '@([a-zA-Z]+)'
            .     '(.*)'
            . '$/m',
            $this->reflector->getDocComment(),
            $matches
        );

        foreach ($matches[0] as $key => $value)
        {
            $name = strtolower($matches[1][$key]);
            $value = trim($matches[2][$key]);

            if (isset($this->acceptedParams[$name]))
            {
                call_user_func
                (
                    array($this, $this->acceptedParams[$name]),
                    $name, $value
                );
            }
        }
    }

    /**
     * Sets an annotation which has a boolean value
     *
     * @param string $name The name of the annotation
     * @param string $value The value of the annotation
     */
    protected function setBool($name, $value)
    {
        switch(strtolower($value))
        {
            case '':
            case 'true':
                $value = true;
                break;

            case 'false':
                $value = false;
                break;

            default:
                $value = (boolean)$value;
        }

        $this->accessor->setRawValue($name, $value);
    }

    /**
     * Sets an annotation which has a integer value
     *
     * @param string $name The name of the annotation
     * @param string $value The value of the annotation
     */
    protected function setInt($name, $value)
    {
        $this->accessor->setRawValue($name, (int)$value);
    }

    /**
     * Sets an annotation which can have any any value
     *
     * @param string $name The name of the annotation
     * @param string $value The value of the annotation
     */
    protected function setMixed($name, $value)
    {
        $this->accessor->setRawValue($name, $value);
    }
}

