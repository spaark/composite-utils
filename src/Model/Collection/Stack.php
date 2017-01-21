<?php namespace Spaark\Core\Model\Collection;
/**
 * Spaark Framework
 *
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @copyright 2012-2015 Emily Shepherd
 */
defined('SPAARK_PATH') OR die('No direct access');


/**
 * Represents a stack; a First in Last out data structure
 */
class Stack extends Collection
{
    /**
     * Adds a new element to the top of the stack
     *
     * @param mixed $data The item to add
     */
    public function push($data)
    {
        array_push($this->data, $data);
    }

    /**
     * Removes the top element from the stack and returns it
     *
     * @return mixed The top element
     */
    public function pop()
    {
        return array_pop($this->data);
    }

    /**
     * Removes the given number of elements from the top of the stack
     * and returns them as an array
     *
     * Values lower than 1 will result in NULL being returned
     *
     * @param int $i The number of elements to pop
     * @return array|NULL The items from the top of the stack
     */
    public function popMultiple($i = 1)
    {
        if ($i < 1) return NULL;

        $ret = array_slice($this->data, -1 * $i);
        $this->data = array_slice($this->data, 0, -1 * $i);
        return $ret;
    }

    /**
     * Returns the top element from the stack without removing it
     *
     * @return mixed The element at the top of the stack
     */
    public function peek()
    {
        return end($this->data);
    }
}