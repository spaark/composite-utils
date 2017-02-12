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

namespace Spaark\CompositeUtils\Model\Reflection\Type;

use Spaark\CompositeUtils\Model\Base\Composite;
use Spaark\CompositeUtils\Traits\AllReadableTrait;

class AbstractType
{
    use AllReadableTrait;

    /**
     * @readable
     * @var boolean
     */
    protected $nullable = false;
}
