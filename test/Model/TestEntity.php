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
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;

/**
 * @generic TypeA
 * @generic TypeB string
 */
class TestEntity 
{
    /**
     * @var string
     * @readable true
     * @writable
     * @construct required
     */
    protected $prop1 = 'foo';

    /**
     * @var ?string
     * @readable
     * @writeable false
     * @construct new
     */
    protected $prop2 = '123';

    /**
     * @var HashMap
     * @construct optional
     * @writable 1
     */
    protected $prop3;

    /**
     * @var boolean
     * @construct optional new
     * @readable false
     * @writable false
     */
    protected $prop4;

    /**
     * @var TestEntity
     */
    protected $prop5;

    public function __construct()
    {
        $this->prop3 = new HashMap();
    }

    /**
     * @param TestEntity $b
     */
    public function methodName(HashMap $a, $b) { }
}
