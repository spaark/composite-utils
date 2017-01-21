<?php namespace Spaark\Core\Model\Base;

class Wrapper extends Entity
{
    /**
     * The object to wrap
     */
    protected $object;

    /**
     * Routes all method calls to the MySQLi object
     *
     * @param string $func The method
     * @param array $args The arguments
     * @return mixed The return of the method call
     */
    public function __call($func, $args)
    {
        return call_user_func_array(array($this->object, $func), $args);
    }

    /**
     * Routes all member variable gets to the MySQLi object
     *
     * @param string $var The name of the variable to get
     * @return mixed The variable's value
     */
    public function __get($var)
    {
        return parent::__get($var) ?: ($this->object
            ? $this->object->$var
            : NULL);
    }
}
