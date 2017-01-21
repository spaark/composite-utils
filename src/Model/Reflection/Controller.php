<?php namespace Spaark\Core\Model\Reflection;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */


// {{{ Exceptions

    /**
     * Thrown when an issue occurs with loading a method
     */
    class MethodLoadException extends \Exception
    {
        public function __construct($method, $args, $mapto, $desc)
        {
            parent::__construct
            (
                  'Tryed to map ' . $method . '(' . $args . ') '
                . 'to ' . $mapto .' but ' . $mapto . ' does not '
                . $desc
            );
        }
    }

    /**
     * Thrown when a method with the wrong number of args is mapped via
     * \@route.
     *
     * For example, the following example will throw the error, as foo has
     * 3 required arguments, so cannot handle home(2):
     * <code>
     *   /**
     *    * \@route foo home(2)
     *    * /
     *   class TestClass
     *   {
     *       function foo($a, $b, $c);
     *   }
     * </code>
     */
    class InvalidArgCountException extends MethodLoadException
    {
        public function __construct($method, $args, $mapto)
        {
            parent::__construct
            (
                $method,
                $args,
                $mapto,
                'support ' . $args . ' args'
            );
        }
    }

    /**
     * Thrown when a method is mapped that doesn't exist
     */
    class MissingMethodException extends MethodLoadException
    {
        public function __construct($method, $args, $mapto)
        {
            parent::__construct
            (
                $method,
                $args,
                $mapto,
                'exist'
            );
        }
    }

    // }}}


        ////////////////////////////////////////////////////////

/**
 * Reflects the controllers
 */
class Controller extends \Spaark\Core\Model\Base\Entity implements \Reflector
{
// {{{ static

    /**
     * Passes through to $this->reflector->export()
     *
     * This is here because this class implements Reflector
     *
     * @see ReflectionClass::export()
     */
    public static function export()
    {
        return $this->reflector->export();
    }

    // }}}

        ////////////////////////////////////////////////////////

// {{{ instance

    /**
     * The ReflectionClass for this ReflectionController
     */
    private $reflector;

    /**
     * Methods
     */
    private $methods = array( );

    /**
     * The routes this controller has
     */
    private $routes = array( );

    /**
     * Reads the Controller
     *
     * @param string $class The classname
     */
    protected function __fromController($class)
    {
        $this->reflector = new \ReflectionClass($class);

        preg_match_all
        (
            '/@route (.*?)\(([0-9])\) (.*?)(\r|\n)/',
            $this->reflector->getDocComment(),
            $arr
        );

        foreach ($arr[0] as $i => $val)
        {
            $method = $arr[1][$i];
            $args   = $arr[2][$i];
            $mapto  = $arr[3][$i];

            $this->addMethodToRoutes($method, $args, $mapto);
        }
    }

    /**
     * Passes through to $this->reflector->__toString()
     *
     * This is here because this class implements Reflector
     *
     * @see ReflectionClass::__toString()
     */
    public function __toString()
    {
        return $this->reflector->__toString();
    }

    /**
     * Returns the ReflectionClass
     *
     * @return ReflectionClass The ReflectionClass
     */
    public function getReflector()
    {
        return $this->reflector;
    }

    public function getMethod($method, $args)
    {
        try
        {
            $this->addMethodToRoutes($method, $args);
        }
        catch (MethodLoadException $e) {}

        return $this->routes[$method][$args];
    }

    private function addMethodToRoutes($method, $args, $mapto = NULL)
    {
        if (!$mapto) $mapto = $method;

        if (!isset($this->routes[$method]))
        {
            $this->routes[$method] = array( );
        }
        if (!isset($this->routes[$method][$args]))
        {
            $this->routes[$method][$args] = array( );
        }

        if (!$this->reflector->hasMethod($mapto))
        {
            throw new MissingMethodException($method, $args, $mapto);
        }

        $methodObj = new Method($this->reflector->getMethod($mapto));

        if
        (
            $args <= $methodObj->getMaxParamCount() &&
            $args >= $methodObj->getMinParamCount()
        )
        {
            $this->routes[$method][$args][] = $methodObj;
        }
        else
        {
            throw new InvalidArgCountException
            (
                $method, $args, $mapto
            );
        }
    }

    // }}}
}

// }}}