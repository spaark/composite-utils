<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and licence information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Test\Model;

use Spaark\CompositeUtils\Traits\AllReadableTrait;

class AllReadableComposite
{
    use AllReadableTrait;

    /**
     * @var string
     */
    protected $a = '123';

    /**
     * @var int
     */
    protected $b = 123;

    /**
     * @var boolean
     */
    protected $c = true;
}
