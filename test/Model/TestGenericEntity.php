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

use Spaark\CompositeUtils\Model\Collection\ArrayList;

/**
 * @generic TypeA
 * @generic TypeB
 */
class TestGenericEntity 
{
    /**
     * @param ArrayList<TypeB> $a
     * @param TypeA $b
     */
    public function methodName($a, $b) { }
}
