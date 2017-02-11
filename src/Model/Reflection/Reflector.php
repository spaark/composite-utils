<?php namespace Spaark\CompositeUtils\Model\Reflection;
/**
 *
 */


class Reflector
{
    public static function blankInstance()
    {
        return new self();
    }

    private $locked = false;

    public function __construct()
    {
        //
    }

    public function __set($var, $val)
    {
        if ($this->locked)
        {
            return parent::__set($var, $val);
        }
        else
        {
            $this->$var = $val;
        }
    }

    public function addTo($name, $args)
    {
        $var = lcfirst($name);

        $this->$var->add($args[0]);
    }
}
