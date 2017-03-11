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

namespace Spaark\CompositeUtils\Model;

use Spaark\CompositeUtils\Traits\AllReadableTrait;

/**
 * Models a classname which has both a namespace and a classname
 */
class ClassName
{
    use AllReadableTrait;

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $classname;

    /**
     * Constructs the ClassName by taking a fully qualified classname
     * and splitting it into its namespace and classname sections
     *
     * @param string $classname
     */
    public function __construct(string $classname)
    {
        preg_match('/(.+)\\\\([a-zA-Z0-9]+)/', $classname, $matches);

        if (!$matches)
        {
            $this->classname = $classname;
        }
        else
        {
            $this->classname = $matches[2];
            $this->namespace = $matches[1];
        }
    }

    /**
     * Returns the fully qualified classname
     *
     * @return string
     */
    public function __toString()
    {
        return
              ($this->namespace ? $this->namespace . '\\' : '')
            . $this->classname;
    }
}
