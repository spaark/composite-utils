<?php namespace Spaark\Core\Model\Reflection;
/**
 *
 */

use \Spaark\Core\Model\Base;

class Reflector extends \Spaark\Core\Model\Base\Wrapper
{
// {{{ static

    /**
     * This class will be instanciated to $this->object
     *
     * It should be one of PHP's inbuilt reflection classes
     */
    const WRAPPER_NAME = 'ReflectionClass';

    /**
     * List of accepted parameters in a doc comment in the form:
     *   name => function_to_call
     */
    protected $acceptedParams = array( );

    /**
     * Checks that a reference is formatted correctly, then calls
     * fromRef() again
     *
     * This is used so you can use multiple forms of a reference whilst
     * still getting the same cached object. For example, the following
     * will all return the same object:
     *   "\My\Class::method"
     *   array("\My\Class", "method")
     *   array(instanceof("\My\Class"), "method")
     *   array(instanceofreflectorfor("\My\Class"), "method")
     *
     * @param mixed $ref A reference to get a reflector for
     * @return Reflector A reflector for the given reference
     */
    protected static function _fromRef($ref)
    {
        if (static::isRefCorrect($ref)) return;

        if (is_array($ref))
        {
            static::checkCount($ref);

            return static::fromRef(array
            (
                                 static::findName($ref[0]),
                isset($ref[1]) ? static::findName($ref[1]) : ''
            ));
        }
        elseif (is_string($ref))
        {
            $ref2 = explode('::', ltrim($ref, '\\'));

            static::checkCount($ref2, $ref);
            return static::fromRef($ref2);
        }
        elseif (is_object($ref))
        {
            return static::fromRef(array(static::findName($ref)));
        }
        else
        {
            throw new Base\CannotCreateModelException
            (
                get_called_class(),
                'ref',
                $ref
            );
        }
    }

    /**
     * Finds the name of a class, from a reference
     *
     * This function uses the following logic:
     *   + If the given reference is a string, this is assumed to be the
     *     name of the class.
     *   + If the given reference is an instance of a reflector, the
     *     name of the class it is reflecting on is the name of the
     *     class
     *   + If the given reference is an instance of another class, its
     *     class name is used
     *
     * @param mixed $ref A string / object for a class
     * @return string The name of the class
     */
    private static function findName($ref)
    {
        if (is_string($ref))
        {
            return ltrim($ref, '\\');
        }
        elseif (is_a($ref, '\Reflector') || is_a($ref, __CLASS__))
        {
            return ltrim($ref->getName(), '\\');
        }
        elseif (is_object($ref))
        {
            return ltrim(get_class($ref), '\\');
        }
    }

    /**
     * Returns true if a reference is in the standard format
     *
     * The standard format is defined as an array containing two strings
     *
     * @param mixed $ref The reference to check
     * @return bool True if the given reference is in the correct format
     */
    private static function isRefCorrect($ref)
    {
        if
        (
            is_array($ref)     && count($ref) == 2   &&
            is_string($ref[0]) && is_string($ref[1]) &&
            $ref[0]{0} != '\\'
        )
        {
            return true;
        }
    }

    /**
     * Ensures that a reference array has either 1 or 2 entries.
     *
     * @throws CannotCreateModelException if there aren't 1 or 2
     */
    private static function checkCount($ref, $original = NULL)
    {
        if (count($ref) != 1 && count($ref) != 2)
        {
            throw new Base\CannotCreateModelException
            (
                get_called_class(),
                'ref',
                $original ?: $ref
            );
        }
    }

    /**
     * Returns a new Reflector Instance
     *
     * When blankInstance is called, this normally uses a reflector to create
     * a new instance without invoking the constructor. But this would lead
     * to an infinate loop in this case, as this is the reflector, so we
     * override it to do nothing.
     *
     * @return static
     */
    public static function blankInstance()
    {
        return new static();
    }

    // }}}

        ////////////////////////////////////////////////////////

// {{{ instance

    /**
     * Does nothing
     *
     * @see self::blankInstance()
     */
    public function __construct()
    {

    }

    /**
     * Creates an instance of the Reflection Class as specified by
     * WRAPPER_NAME and starts parsing the doctype
     *
     * @param array $cb A valid reference to reflect on
     */
    protected function __fromRef($cb)
    {
        $class        = static::WRAPPER_NAME;
        $this->object = new $class($cb[0], $cb[1]);

        $this->parse();
    }

    /**
     * Parses the doctype and calls subclass methods to handle each
     * param
     */
    public function parse()
    {
        preg_match_all
        (
              '/^'
            .     '[ \t]*\*[ \t]*'
            .     '@([a-zA-Z0-9]+)'
            .     '(.*)'
            . '$/m',
            $this->object->getDocComment(),
            $matches
        );

        foreach ($matches[0] as $key => $value)
        {
            $name  = strtolower($matches[1][$key]);
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
     * Allows properties defined in $this->acceptedParams to be read
     *
     * Normally, a readonly property should define @readable, however
     * this is not possible here, as this is the code that makes
     * \@readable work!
     *
     * @param scalar $var The property to get
     * @return mixed The value of the property if defined
     * @see parent::__get()
     */
    public function __get($var)
    {
        if (in_array($var, array_keys($this->acceptedParams)))
        {
            return $this->$var;
        }
        else
        {
            return Base\Entity::__get($var);
        }
    }

    /**
     * Sets the given property with the given value
     *
     * $this->$name = $value
     *
     * @param string $name The property to set
     * @param string $value The value to set
     */
    protected function mixed($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Sets the given property with the given boolean value
     *
     * The value is parsed such that the string "false" and "0" also
     * count as false
     *
     * @param string $name The property to set
     * @param string $value The value to set
     */
    protected function bool($name, $value)
    {
        $value = strtolower($value);

        if ($value == '')
        {
            $this->$name = TRUE;
        }
        elseif ($value === 'true')
        {
            $this->$name = TRUE;
        }
        elseif ($value === 'false')
        {
            $this->$name = FALSE;
        }
        else
        {
            $this->$name = (boolean)$value;
        }
    }

    // }}}
}
