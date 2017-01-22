<?php namespace Spaark\Core\DataSource;

use Spaark\Core\Model\Base\Entity;

/**
 * Description of entitycache
 *
 * @author Emily Shepherd
 */
class EntityCache
{
    protected $class;

    protected $cache = array( );

    protected $entries = array( );

    protected $objs = array( );

    protected $ownedRegions = array( );

    public function __construct($class)
    {
    }

    public function searchFor($key, $value)
    {
        if (!isset($this->cache[$key]))
        {
            return NULL;
        }
        elseif (isset($this->cache[$key][$value]))
        {
            return $this->cache[$key][$value];
        }
        else
        {
            foreach ($this->objs as $obj)
            {
                if ($obj->propertyValue($key, false, false) === $value)
                {
                    $this->cache($obj);

                    return $obj;
                }
            }
        }
    }

    public function cache(Entity $object)
    {
        $h = spl_object_hash($object);

        if (isset($this->entries[$h]))
        {
            foreach ($this->entries[$h] as $key => $oldValue)
            {
                $newValue = $object->propertyValue($key, false, false);

                if ($oldValue !== $newValue)
                {
                    unset($this->cache[$key][$oldValue]);

                    $this->cache[$key][$newValue] = $object;
                    $this->entries[$h][$key]      = $newValue;
                }
            }
        }
        else
        {
            $this->entries[$h] = array( );
            $this->objs[]      = $object;

            foreach ($this->cache as $key => $values)
            {
                $value = $object->propertyValue($key, false, false);

                $this->entries[$h][$key]   = $value;
                $this->cache[$key][$value] = $object;
            }
        }
    }
}
