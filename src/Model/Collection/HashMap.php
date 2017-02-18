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

use Spaark\CompositeUtils\Service\HashProducer;
use Spaark\CompositeUtils\Service\HashProducerInterface;

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
     * @var HashProducerInterface
     */
    protected static $defaultHashProducer;

    protected static function getHashProducer() : HashProducerInterface
    {
        if (!static::$defaultHashProducer)
        {
            static::$defaultHashProducer = new HashProducer();
        }

        return static::$defaultHashProducer;
    }

    public static function setDefaultHashProducer
    (
        HashProducerInterface $hashProducer
    )
    : HashProducerInterface
    {
        static::$defaultHashProducer = $hashProducer;
    }

    /**
     * @var Pair<KeyType, ValueType>[]
     */
    protected $data = [];

    /**
     * @var HashProducerInterface
     */
    protected $hashProducer;

    public function __construct
    (
        ?HashProducerInterface $hashProducer = null
    )
    {
        $this->hashProducer =
            $hashProducer ?: static::getHashProducer();
    }

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
        return isset($this->data[$this->hashProducer->getHash($key)]);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->data[$this->hashProducer->getHash($key)]->value;
    }

    /**
     * {@inheritDoc}
     */
    public function insert(Pair $pair)
    {
        $this->data[$this->hashProducer->getHash($pair->key)] = $pair;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        unset($this->data[$this->hashProducer->getHash($key)]);
    }

    /**
     * {@inheritDoc}
     */
    public function size() : int
    {
        return count($this->data);
    }
}
