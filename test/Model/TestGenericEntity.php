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

namespace Spaark\CompositeUtils\Test\Model;

use Some\Test\NamespacePath\ClassName;
use Some\Other\Test\ClassName as AliasedClass;
use Spaark\CompositeUtils\Model\Collection\HashMap;

/**
 * @generic TypeA
 */
class TestGenericEntity 
{
    /**
     * @param TypeA $b
     */
    public function methodName(HashMap $a, $b) { }
}
