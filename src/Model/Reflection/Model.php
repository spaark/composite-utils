<?php namespace Spaark\Core\Model\Reflection;

use Spaark\Core\Model\Collection\HashMap;


/**
 * Reflects upon model classes and deals with their getter methods and
 * properties
 */
class Model extends Reflector
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
