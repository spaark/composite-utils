<?php namespace Spaark\Core\Model\Base;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

use \Spaark\Core\Error\NoSuchMethodException;

// {{{ Constants

    /**
     * Enum for L1 cache
     */
    const L1_CACHE  = 1;

    /**
     * Enum for L2 cache
     */
    const L2_CACHE  = 2;

    /**
     * Enum for L3 cache
     */
    const L3_CACHE  = 3;

    /**
     * Enum for static from method
     */
    const STATIC_F  = 4;

    /**
     * Enum for instanct from method
     */
    const DYN_F     = 5;

    /**
     * Enum for data source
     */
    const SOURCE    = 6;

    // }}}

        ////////////////////////////////////////////////////////

// {{{ Exceptions

    /**
     * Thrown when a non-existant static method is called, that begins
     * with "from"
     */
    class NoSuchFindByException extends NoSuchMethodException
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
     * @deprecated
     */
    class InvalidFindByException extends NoSuchFindByException {}

    /**
     * Thrown when trying to write to a property which is not writable
     */
    class PropertyNotWritableException extends \Exception
    {
        /**
         * Constructs the exception
         *
         * @param string $property The name of the property
         */
        public function __construct($property)
        {
            parent::__construct($property . ' is not writable');
        }
    }

    /**
     * Thrown when trying to read a property which is not readable
     */
    class PropertyNotReadableException extends \Exception
    {
        /**
         * Constructs the exception
         *
         * @param string $property The name of the property
         */
        public function __construct($property)
        {
            parent::__construct($property . ' is not readable');
        }
    }

    // }}}

        ////////////////////////////////////////////////////////

/**
 * Represents a complex model, that contains a series of attributes
 * obtained from a data source, and as such should be cached in local
 * memory
 *
 * Eg:
 * <code><pre>
 *   //Query data source for id 4, create object, cache and return
 *   Entity::fromId(4);
 *
 *   //Notice that a cached object with id=4 already exists, so return
 *   //that instead of querying data source
 *   Entity::fromId(4);
 * </pre></code>
 *
 * It will also cache accross keys:
 * <code><pre>
 *   //Query data source for id 4, create object, cache and return
 *   Entity::fromEmail('email@example.com');
 *   //returns Entity{id: 9, email: 'email@example.com', name: 'Joe'}
 *
 *   //Even though the above Entity was created from the email key, the
 *   //cache will check its id and return that anyway
 *   Entity::fromId(9);
 * </pre></code>
 */
class Entity extends Composite
{
    /**
     * The cache of constructed objects
     */
    public static $_cache = array( );

    protected static $source;

    private static $visited = array( );

    // TODO: Cache is broken
    /**
     * Returns the given class from cache given it's $id = $val
     *
     * @param string $key   The key to check against
     * @param scalar $val   The value to match
     * @return Entity The cached object, or NULL
     */
    public static function getObj($key, $val)
    {
        //var_dump(static::$_cache);
        $class = get_called_class();

        if (isset(static::$_cache[$class]))
        {
            return static::$_cache[$class]->searchFor($key, $val);
        }
    }

    /**
     * Caches the given object
     *
     * @param Entity $obj   The object to cache
     * @param string $id    The key to cache it under
     * @param scalar $val   The value to cache it under
     */
    public static function cache(Entity $obj)
    {
        $class = get_called_class();

        if (strpos($class, 'Spaark\Core\Model\Reflection') !== 0)
        {
            if (!isset(static::$_cache[$class]))
            {
                static::$_cache[$class] = new Entity\EntityCache($class);
            }

            static::$_cache[$class]->cache($obj);
        }
    }

    /**
     * Attempts to return an object
     *
     * Options:
     *   + If it's in cache, return that
     *   + Use parent::build() to attempt auto-factory build
     *   + Attempt to build it by querying a data sorce via
     *     __autoBuild()
     *
     * @param string $id    The key to build by
     * @param array  $args  The arguments to use to build
     * @return static The object, if built correct
     * @throws cannotBuildModelException
     *     If all build / load attempts fail
     */
    public static function from($id, $args)
    {
        $class   = get_called_class();
        $id      = lcfirst($id);
        $obj     = NULL;
        $args    = (array)$args;
        $include = \iget($args, 1) ?: array( );
        $startAt = \iget($args, 2, L1_CACHE);
        $val     =
              (!isset($args[0])     ? NULL
            : (is_array($args[0])   ? implode($args[0])
            : (!is_scalar($args[0]) ? (string)$args[0]
            :                         $args[0])));

        switch ($startAt)
        {
            // Try Local Cache
            case L1_CACHE:
                if ($obj = static::getObj($id, $val))
                {
                    return $obj;
                }

            case L2_CACHE:
                //
            case L3_CACHE:
                //

            // Try static function
            // _from$id()
            case STATIC_F:
                try
                {
                    $func = $class . '::_from' . $id;

                    if ($obj = call_user_func_array($func, $args))
                    {
                        break;
                    }
                }
                catch (NoSuchMethodException $nsme) { }

            // Try instance method
            // $obj->__from$id()
            case DYN_F:
                try
                {
                    if ($obj = parent::from($id, $args))
                    {
                        break;
                    }
                }
                catch (NoSuchFromException $nsfe) { }

            // Try data source
            case SOURCE:
                try
                {
                    $objs = static::findBy($id, $args);

                    if ($objs->count(true) == 1)
                    {
                        $obj = $objs->get(0);
                        break;
                    }
                }
                catch (NoSuchFindByException $nsfbe) { }

            default:
                throw new CannotCreateModelException
                (
                    $class, $id, $val
                );
        }

        static::cache($obj, $id, $val);

        $obj->dirty = false;
        $obj->new   = false;

        return $obj;
    }

    /**
     * Attempts to return an iterable collection of objects
     *
     * @param string $name The findBy string to use
     * @param array $args  The value to look for, in an array
     * @param boolean $count Not used
     * @return Iterable The list of objects
     * @throws NoSuchFindByException if no findBy function / source is
     *     set
     */
    public static function findBy($name, $args, $count = false)
    {
        try
        {
            $ret = static::call($name, $args, 'findBy');
            return $ret[1];
        }
        catch (NoSuchFindByException $nsfbe)
        {
            if (static::$source)
            {
                $source = static::load(static::$source);
                $source = new $source(get_called_class());

                if (strpos($name, 'Latest') === 0)
                {
                    $source->order(substr($name, 6), 'DESC');
                }
                elseif (strpos($name, 'Highest') === 0)
                {
                    $source->order(substr($name, 7), 'DESC');
                }
                elseif (strpos($name, 'Earliest') === 0)
                {
                    $source->order(substr($name, 8), 'ASC');
                }
                elseif (strpos($name, 'Lowest') === 0)
                {
                    $source->order(substr($name, 6), 'ASC');
                }
                else
                {
                    $source->fwhere($name, iget($args, 0, 1));
                }

                return $source;
            }
        }

        throw $nsfbe;
    }

    public static function newSource()
    {
        $class  = get_called_class();
        $config = static::getHelper('config');
        $source = static::load($config->source);

        return new $source($source);
    }

    /**
     * Handles magic static functions - used for fromX() and findByX()
     *
     * @param string $name The called function
     * @param array $args  The arguments used in the method call
     * @return mixed The return from the findBy / from method
     * @throws NoSuchMethodException if the method isn't a findBy / from
     * @see self::from()
     * @see self::findBy()
     */
    public static function __callStatic($name, $args)
    {
        if (substr($name, 0, 4) == 'from')
        {
            return static::from(substr($name, 4), $args);
        }
        elseif (substr($name, 0, 6) == 'findBy')
        {
            return static::findBy(substr($name, 6), $args);
        }
        else
        {
            throw new NoSuchMethodException(get_called_class(), $name);
        }
    }

    /**
     * Returns an instance from the given data, either by finding it
     * already cached, or by creating a new one
     *
     * @param array $data The data to create an object from
     * @param boolean $cache If false, newly created instances won't be
     *     cached
     * @return static The loaded / new object
     */
    public static function instanceFromData($data, $cache = true)
    {
        $obj = static::findFromData($data) ?: static::blankInstance();

        $obj->loadArray($data);

        static::cache($obj);

        return $obj;
    }

    /**
     * Searches the cache for an object comparing the keys in the cache
     * with the given data
     *
     * @param array $data The data to search for
     * @return static The object, if found. NULL, otherwise
     * @see self::instanceFromData()
     */
    private static function findFromData($data)
    {
        $class = get_called_class();

        if (isset(static::$_cache[$class]))
        {
            foreach ($data as $key => $value)
            {
                if ($obj = static::$_cache[$class]->searchFor($key, $value))
                {
                    return $obj;
                }
            }
        }
    }

    public static function flush()
    {

    }

    public static function getInstance($id)
    {
        return static::instanceFromData(array('id' => $id), true);
    }

    /**
     * ID
     *
     * @type int
     * @readable
     */
    protected $id;

    /**
     * If true, this is a new object
     *
     * @readable
     */
    protected $new      = true;

    /**
     * If true, this will attempt to save on destruction
     */
    protected $autoSave = false;

    /**
     * Records which source this object was loaded from
     */
    protected $loadedSource;

    /**
     * Saves this to a data source
     */
    public function save()
    {
        // If this was loaded from somewhere, save it back there.
        // Otherwise, save it to the default location
        $source =
              ($this->loadedSource ? $this->loadedSource
            : (static::$source     ? static::load(static::$source)
            :                        NULL));

        if (!$source) return false;

        $source = new $source(get_called_class());
        $data   = $this->__toArray($source::CAN_SAVE_DIRTY, $source::RELATIONAL);

        if ($this->new)
        {
            $this->id = $source->create($data);
        }
        else
        {
            $source->update($this->id, $data);
        }

        $this->new        = false;
        $this->properties = array_merge($this->properties, $data);
    }

    /**
     * Deletes this entity from the data source
     */
    public function remove()
    {
        if (!$this->new)
        {
            $this->db->delete($this->id);
            $this->new = true;
        }
    }

    /**
     * If autoSave is enabled, this will save the object at destruct
     * time
     */
    public function __destruct()
    {
        if ($this->autoSave)
        {
            $this->save();
        }
    }

    /**
     * Sets autoSave to false to prevent this object from being saved
     * when destroyed
     */
    public function discard()
    {
        $this->autoSave = false;
    }

    public function close()
    {

    }
}