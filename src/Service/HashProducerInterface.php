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

namespace Spaark\CompositeUtils\Service;

/**
 */
interface HashProducerInterface
{
    /**
     * Returns a good scalar value to use for a native PHP array
     *
     * @param KeyType $value The key to convert to a scalar
     * @return string Scalar value
     */
    public function getHash($value) : string;
}
