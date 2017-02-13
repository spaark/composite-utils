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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Model\Reflection;

/**
 * Represents a use statement
 *
 * @property-read string $classname
 * @property-read string $name
 */
class UseStatement extends Reflector
{
    /**
     * The classname being used
     *
     * @var string
     * @readable
     */
    protected $classname;

    /**
     * The name of this class in the current namespace block
     *
     * @var string
     * @readable
     */
    protected $name;

    /**
     * Creates the UseStatement using the given classname and used as
     * name
     *
     * @param string $classname The classname being used
     * @param string $name The name of this class in the block
     */
    public function __construct(string $classname, string $name)
    {
        $this->classname = $classname;
        $this->name = $name;
    }
}
