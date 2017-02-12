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

namespace Spaark\CompositeUtils\Test\Model;

use Some\Test\NamespacePath\ClassName;
use Some\Other\Test\ClassName as AliasedClass;
use Spaark\CompositeUtils\Model\Collection\Collection;

class TestEntity 
{
    /**
     * @var string
     * @readable
     * @writable
     * @construct required
     */
    protected $prop1 = 'foo';

    /**
     * @var ?string
     * @readable
     * @construct new
     */
    protected $prop2 = '123';

    /**
     * @var Collection
     * @construct optional
     * @writable
     */
    protected $prop3;

    /**
     * @var boolean
     * @construct optional new
     */
    protected $prop4;

    public function __construct()
    {
        $this->prop3 = new Collection();
    }
}
