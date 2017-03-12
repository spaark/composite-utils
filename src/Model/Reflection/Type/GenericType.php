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

namespace Spaark\CompositeUtils\Model\Reflection\Type;

/**
 * Represents a native integer type
 */
class GenericType extends AbstractType
{
    /**
     * @var string
     * @readable
     */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function equals($object) : bool
    {
        return parent::equals($object) && $object->name === $this->name;
    }
}
