<?php namespace Spaark\Core\Model\Collection;
/**
 * Spaark Framework
 *
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @copyright 2012-2015 Emily Shepherd
 */
defined('SPAARK_PATH') OR die('No direct access');

/**
 * Represents a set of data
 *
 * Any given element can only exist in a set once. These are evaluated
 * by their value, if they are scalar, or their object hash if they are
 * an object.
 */
class Set extends Collection
{
    /**
     * Gets the Scalar value of a value
     *
     * This is its value if it is already scalar, or their object hash
     * otherwise.
     *
     * @param mixed $value The value to convert to scalar
     * @return string The sclar value
     */
    private function getScalar($value)
    {
        return
              (is_object($value) ? spl_object_hash($value)
            : (is_array($value)  ? implode($value)
            : (                    (string)$value)));
    }

    /**
     * Adds an element to the set
     *
     * If the element already exists in the set, no change is made
     *
     * @param mixed $value An item to add
     */
    public function add($value)
    {
        $hash = $this->getScalar($value);

        $this->data[$hash] = $value;
    }

    /**
     * Checks if the given element exists in the set
     *
     * @param mixed $object The object to check
     * @return boolean True if the element exists
     */
    public function contains($object)
    {
        return isset($this->data[$this->getScalar($object)]);
    }

    /**
     * Returns the current key
     *
     * @return mixed The current key
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Checks the given element exists in the set
     *
     * @param mixed $key The item to check
     * @return boolean True if the element exists
     */
    public function offsetExists($key)
    {
        return parent::offsetExists($this->getScalar($key));
    }

    /**
     * Returns the element if the element exists in the set
     *
     * @param mixed $offset The item to check
     * @return mixed The object if found, NULL otherwise
     */
    public function offsetGet($offset)
    {
        return parent::offsetGet($this->getScalar($offset));
    }

    /**
     * Adds a new element to the set, ignoring the offset
     *
     * @param mixed $offset IGNORED
     * @param mixed $value The value to add
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value);
    }

    /**
     * Removes an element from the set
     *
     * If the element does not exist in the set, no change is made
     *
     * @param mixed $offset The element to remove
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($this->getScalar($offset));
    }

    /**
     * Returns a string which identifies the value of this set
     *
     * @return string An identifiable hash
     */
    public function dataHash()
    {
        $keys = array_keys($this->data);
        sort($keys);

        return implode($keys);
    }
}
