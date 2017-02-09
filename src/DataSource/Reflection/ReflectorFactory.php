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
}

