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
 *
 */

use Spaark\CompositeUtils\Traits\AllReadableTrait;

class Reflector
{
    use AllReadableTrait;

    public static function blankInstance()
    {
        return new self();
    }

    private $locked = false;

    public function __set($var, $val)
    {
        if ($this->locked)
        {
            return parent::__set($var, $val);
        }
        else
        {
            $this->$var = $val;
        }
    }

    public function addTo($name, $args)
    {
        $var = lcfirst($name);

        $this->$var->add($args[0]);
    }
}
