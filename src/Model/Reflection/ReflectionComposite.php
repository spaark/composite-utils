<?php namespace Spaark\CompositeUtils\Model\Reflection;

use Spaark\CompositeUtils\Model\Collection\HashMap;


/**
 * Reflects upon model classes and deals with their getter methods and
 * properties
 */
class ReflectionComposite extends Reflector
{
    /**
     * @var HashMap
     */
    protected $properties;

    /**
     * @var HashMap
     */
    protected $methods;

    public function __construct()
    {
        $this->properties = new HashMap();
        $this->methods = new HashMap();
    }
}
