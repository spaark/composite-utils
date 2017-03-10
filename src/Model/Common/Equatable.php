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

namespace Spaark\CompositeUtils\Model\Common;

/**
 * Classes implementing this class can compare themselves to another to
 * determine equality
 *
 * @generic Type
 */
interface Equatable
{
    /**
     * Checks if the given class is equal to this one
     *
     * @param Type $oject
     * @return boolean
     */
    public function equals($object) : bool;
}
