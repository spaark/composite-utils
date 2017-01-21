<?php namespace Spaark\Core\Model\Base;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

use \Spaark\Core\Error\NoSuchMethodException;
use \Spaark\Core\Config\Config;


        ////////////////////////////////////////////////////////


// {{{ exceptions

/**
 * Thrown when a non-existant static method is called, that begins with
 * "from"
 */
class NoSuchFromException extends NoSuchMethodException
{
    private $obj;

    public function __construct($method, $obj)
    {
        parent::__construct($obj, $method);

        $this->obj = $obj;
    }

    public function getObj()
    {
        return $this->obj;
    }
}

/**
 * Thrown when a model cannot be created
 *
 * This may happen when an invalid / non-existant id is given. Eg:
 *   User::fromEmail('not-real@example.com');
 *
 * That would normally return an instance of User, but as that email
 * address doesn't exist, an error is thrown
 */
class CannotCreateModelException extends \Exception
{
    private $obj;

    public function __construct($model, $from, $val)
    {
        $modelName = is_object($model) ? get_class($model) : $model;
        $val       = is_array($val)    ? implode($val)     : $val;

        parent::__construct
        (
              'Failed to create ' . $modelName . ' from '
            . $from . ': ' . $val
        );

        $this->obj = $model;
    }

    public function getObj()
    {
        return $this->obj;
    }
}

/**
 * Thrown when a model cannot be created
 *
 * This may happen when an invalid / non-existant id is given. Eg:
 *   User::fromEmail('not-real@example.com');
 *
 * That would normally return an instance of User, but as that email
 * address doesn't exist, an error is thrown
 */
class CannotCreateCollectionException extends \Exception
{
    private $obj;

    public function __construct($model, $from, $val)
    {
        $modelName = is_object($model) ? get_class($model) : $model;

        parent::__construct
        (
              'Failed to create ' . $modelName . ' from '
            . $from . ': ' . $val
        );

        $this->obj = $model;
    }

    public function getObj()
    {
        return $this->obj;
    }
}

// }}}


        ////////////////////////////////////////////////////////

/**
 * Performs application logic for a Controller
 */
abstract class Model extends \Spaark\Core\Base\Object
{
    const INPUT_TYPE     = 'text';

    const FROM           = 'Model';

    const DB_HELPER      = 'Database\MySQLi';

    const REFLECT_HELPER = 'Reflection\Model';

// {{{ static

    /**
     * Returns this Model's name
     *
     * Normally this is just class name, but can be overridden by the
     * static::NAME constant.
     *
     * This can be useful if you want a helper class to act like
     * another, eg:
     * <code>
     * class UserHelper
     * {
     *     const NAME = 'User';
     *
     *     public function foo()
     *     {
     *         //Automatically get's everything from the 'User' table
     *         $this->db->select();
     *     }
     * }
     * </code>
     *
     * @return string The name of this class
     */
    public static function modelName()
    {
        $class = get_called_class();

        if (defined($class . '::NAME'))
        {
            return constant($class . '::NAME');
        }
        else
        {
            return substr($class, strrpos($class, '\\') + 1);
        }
    }

    /**
     * Creates an instance of the class, without calling the constructor
     * @return object The created instance
     */
    public static function blankInstance()
    {
        $reflect      = static::getHelper('reflect');
        $obj          = $reflect->newInstanceWithoutConstructor();
        //$obj->reflect = $reflect;

        return $obj;
    }

    /**
     * Called to test if the given string is valid to be turned into
     * this Model
     *
     * For example:
     *   Email::validate('email@example.com'); === true
     *   Email::validate('not-an-email'); === false
     *
     * @param  string $variable The string to validate
     * @return boolean          True if valid
     */
    public static function validate($variable)
    {
        return true;
    }

    /**
     * Handles static method calls, by attempting to load use an
     * auto-factory method
     *
     * @param string $name The method called
     * @param array  $args The arguments
     * @return static The created instance of this Model
     * @see build()
     * @throws NoSuchMethodException
     *      If the called method doesn't start with "from"
     * @throws NoSuchFromException
     *     If the auto-factory method doesn't exist
     * @throws CannotCreateModelException
     *     If the auto-factory method fails to create the object, from
     *     the given arguments
     */
    public static function __callStatic($name, $args)
    {
        if (substr($name, 0, 4) != 'from')
        {
            throw new NoSuchMethodException(get_called_class(), $name);
        }
        else
        {
            return static::from(substr($name, 4), $args);
        }
    }

    /**
     * Attempts to run an auto-factory method
     *
     * @param string $id    The auto-factory id to use
     * @param array  $args  The arguments for the auto-factory method
     * @return $class The created instance of this Model
     * @throws NoSuchFromException
     *     If the auto-factory method doesn't exist
     * @throws CannotCreateModelException
     *     If the auto-factory method fails to create the object, from
     *     the given arguments
     */
    public static function from($id, $args)
    {
        $ret = self::call($id, $args, 'from', 'NoSuchFromException');

        return $ret[1] ?: $ret[0];
    }

    protected static function call($id, $args, $type)
    {
        $class = get_called_class();
        $cb    = array($class, '__' . $type . $id);
        $throw = '\Spaark\Core\Model\Base\NoSuch' . $type . 'Exception';

        if (method_exists($cb[0], $cb[1]))
        {
            $obj                 = $class::blankInstance();
            $obj->{lcfirst($id)} = isset($args[0]) ? $args[0] : true;
            $cb[0]               = $obj;

            $ret                 = call_user_func_array($cb, $args);

            return array($obj, $ret);
        }
        else
        {
            throw new $throw($id, $class);
        }
    }

    public static function load($name, $localScope = NULL)
    {
        return \Spaark\Core\ClassLoader::loadModel($name, $localScope);

        //Local Scope
        if ($localScope)
        {
            $fullName = '\\' . $localScope. '\\' . $name;

            if (class_exists($fullName))
            {
                return $fullName;
            }
        }

        //App Model Scope
        $fullName = Config::getConf('namespace') . 'Model\\' . $name;
        if (class_exists($fullName))
        {
            return $fullName;
        }

        //Spaark Model Scope
        $fullName = '\\Spaark\\Core\\Model\\' . $name;
        if (class_exists($fullName))
        {
            return $fullName;
        }
    }

    /**
     * Auto-factory creator for fromModel calls
     *
     * @param string $model The class name
     */
    protected static function ___fromModel($model)
    {
        return static::fromParent($model);
    }

    /**
     * Auto-factory creator for fromController calls
     *
     * @param string $model The class name
     */
    protected static function ___fromController($controller)
    {
        return static::fromParent($controller);
    }

    // }}}


        ////////////////////////////////////////////////////////

// {{{ object

    /**
     * The Model to auto-instantiate when $this->json is used
     */
    protected $jsonClass    = 'JSON';

    /**
     * The value of this model (if applicable)
     */
    protected $value;

    /**
     * If an argument is passed, __default($arg) will be called
     *
     * @param mixed $val Optional argument
     * @see __default()
     */
    /*final*/ public function __construct($val = NULL)
    {
        if ($val)
        {
            $this->__default($val);
        }
    }

    /**
     * Default constructor action if a value is present
     *
     * @param mixed $val The the argument
     */
    protected function __default($val)
    {
        if (is_callable(array($this, '__fromId')))
        {
            $this->__fromId($val);
        }
        else
        {
            $this->value = $val;
        }
    }

    /**
     * Creates model from a GET value
     *
     * @param string $id The GET value to use
     * @throws CannotCreateModelException If given GET value is not set
     */
    protected function __fromGet($id)
    {
        $this->__fromGlobal('_GET',  $id);
    }

    /**
     * Creates model from a POST value
     *
     * @param string $id The POST value to use
     * @throws CannotCreateModelException If given POST value is not set
     */
    protected function __fromPost($id)
    {
        $this->__fromGlobal('_POST', $id);
    }

    /**
     * Creates model from a GLOBAL array's value
     *
     * This intended to be used for _POST and _GET and, as such, will
     * assume the given global array is set and is an array.
     *
     * @param string $global The name of the global to use
     * @param string $id     The POST value to use
     * @throws CannotCreateModelException
     *     If given global array's value is not set
     */
    public function __fromGlobal($global, $id)
    {
        if (!isset($_GLOBALS[$global][$id]))
        {
            throw new CannotCreateModelException(get_class(), $global, $id);
        }

        $this->__default($_GLOBALS[$global][$id]);
    }

    /**
     * Handles the mgaic getting of variables
     *
     * Options:
     *   + If a class is defined by $this->{$var . 'Class'}, that will
     *     be instantiate, set to $this->$var, then returned
     *   + If the method {'get' . $var}() exists, it will be called, and
     *     its result returned
     *   + Otherwise, NULL
     *
     * @param string $var The magic variable name
     * @return mixed The object
     */
    public function __get($var)
    {
        if ($var == 'reflect')
        {
            return parent::__get($var);
        }
        elseif ($var{0} == '_')
        {
            //
        }
        elseif ($getter = $this->reflect->getterMethod($var))
        {
            if ($getter->save)
            {
                return $this->$var = $this->$var();
            }
            else
            {
                return $this->$var();
            }
        }
        elseif ($this->reflect->hasPublicMethod('get' . $var))
        {
            return $this->{'get' . $var}();
        }
        else
        {
            return parent::__get($var);
        }
    }

    /**
     * Returns this Model's name
     *
     * Normally this is just class name, but can be overridden by the
     * static::NAME constant.
     *
     * @return string The name of this class
     * @see Model::modelName()
     */
    public function getModelName()
    {
        return static::modelName();
    }

    // }}}
}
