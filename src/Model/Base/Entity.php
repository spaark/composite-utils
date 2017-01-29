<?php namespace Spaark\Core\Model\Base;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

use \Spaark\Core\Exception\NoSuchMethodException;
use \Spaark\Core\Exception\CannotCreateModelException;

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
        $class = static::DEFAULT_BUILDER;

        if (substr($name, 0, 4) == 'from')
        {
            return $class::from(substr($name, 4), $args);
        }
        elseif (substr($name, 0, 6) == 'findBy')
        {
            return $class::findBy(substr($name, 6), $args);
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
