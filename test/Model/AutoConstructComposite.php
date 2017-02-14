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

use Spaark\CompositeUtils\Traits\AutoConstructTrait;
use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Spaark\CompositeUtils\Model\Collection\Collection;

class AutoConstructComposite
{
    use AutoConstructTrait;
    use AllReadableTrait;

    /**
     * @var Collection
     * @construct new
     */
    protected $a;

    /**
     * @var TestEntity
     * @construct required
     */
    protected $b;

    /**
     * @var boolean
     * @construct optional
     */
    protected $c;

    /**
     * @var TestEntity
     * @construct optional new
     */
    protected $d;
}
