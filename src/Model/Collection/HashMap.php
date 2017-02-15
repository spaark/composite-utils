<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Model\Collection;

/**
 * Represents a HashMap which contains mapings from one element to
 * another
 *
 * @generic KeyType
 * @generic ValueType
 */
class HashMap extends AbstractMap
{
    /**
     * @var Pair<KeyType, ValueType>[]
     */
    protected $data = [];

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new HashMapIterator($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key) : bool
    {
        return isset($this->data[$this->getScalar($key)]);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->data[$this->getScalar($key)]->value;
    }

    /**
     * {@inheritDoc}
     */
    public function insert(Pair $pair)
    {
        $this->data[$this->getScalar($pair->key)] = $pair;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        unset($this->data[$this->getScalar($key)]);
    }

    /**
     * {@inheritDoc}
     */
    public function size() : int
    {
        return count($this->data);
    }

    /**
     * Returns a good scalar value to use for a native PHP array
     *
     * @param KeyType $value The key to convert to a scalar
     * @return string Scalar value
     */
    private function getScalar($value)
    {
        switch (gettype($value))
        {
            case 'object':
                return spl_object_hash($value);
            case 'array':
                return implode($value);
            default:
                return (string)$value;
        }
    }
}
