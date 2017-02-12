<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and licence information, please view the
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
     * @var array
     */
    protected $acceptedParams = [];

    public function __construct(PHPNativeReflector $reflector)
    {
        $class = static::REFLECTION_OBJECT;

        $this->object = new $class();
        $this->accessor = new RawPropertyAccessor($this->object);
        $this->reflector = $reflector;
    }

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

    protected function setInt($name, $value)
    {
        $this->accessor->setRawValue($name, (int)$value);
    }

    protected function setMixed($name, $value)
    {
        $this->accessor->setRawValue($name, $value);
    }
}

