<?php namespace Spaark\Core\Model\Collection;
/**
 * Spaark Framework
 *
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @copyright 2012-2015 Emily Shepherd
 */
defined('SPAARK_PATH') OR die('No direct access');


/**
 * Represents a HashMap which contains mapings from one element to
 * another
 *
 * This is similar to PHP's existing array system, which supports
 * key-value pairs (<code>array('key' =&gt; 'value'));</code>) with the
 * added benefit that key values can be objects.
 *
 */
class HashMap extends Collection
{
    protected $keys = array( );

    private function getScalar($value)
    {
        return
              (is_object($value) ? spl_object_hash($value)
            : (is_array($value)  ? implode($value)
            : (                    (string)$value)));
    }

    public function add($key, $value)
    {
        $hash = $this->getScalar($key);

        $this->keys[$hash] = $key;
        $this->data[$hash] = $value;
    }

    public function key()
    {
        return $this->keys[parent::key()];
    }

    public function offsetExists($key)
    {
        return parent::offsetExists($this->getScalar($key));
    }

    public function offsetGet($offset)
    {
        return parent::offsetGet($this->getScalar($offset));
    }

    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $hash = $this->getScalar($offset);

        unset($this->data[$offset]);
        unset($this->keys[$offset]);
    }
}