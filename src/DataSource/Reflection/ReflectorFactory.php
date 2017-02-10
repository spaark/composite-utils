<?php

namespace Spaark\Core\DataSource\Reflection;

use Spaark\Core\DataSource\BaseBuilder;
use Spaark\Core\Service\PropertyAccessor;
use Spaark\Core\Model\Reflection\Reflector as SpaarkReflector;
use \Reflector as PHPNativeReflector;

abstract class ReflectorFactory extends BaseBuilder
{
    const REFLECTION_OBJECT = null;

    /**
     * @var PHPNativeReflector
     */
    protected $reflector;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @var SpaarkReflector
     */
    protected $object;

    public function __construct(PHPNativeReflector $reflector)
    {
        $class = static::REFLECTION_OBJECT;

        $this->object = new $class();
        $this->accessor = new PropertyAccessor($this->object, null);
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

