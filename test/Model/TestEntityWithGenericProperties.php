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

use Spaark\CompositeUtils\Traits\PropertyAccessTrait;

/**
 * Do something
 */
class TestEntityWithGenericProperties
{
    use PropertyAccessTrait;

    /**
     * @var TestGenericEntity<int, string>
     * @readable
     * @writable
     */
    protected $property;
}
