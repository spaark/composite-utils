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

use Some\Test\NamespacePath\ClassName;
use Some\Other\Test\ClassName as AliasedClass;
use Spaark\CompositeUtils\Model\Collection\Collection;

class TestEntity 
{
    /**
     * @var string
     * @readable
     * @writable
     */
    protected $id = 'foo';

    /**
     * @var ?string
     */
    protected $property = '123';

    /**
     * @var Collection
     */
    protected $arrayProperty;

    public function __construct()
    {
        $this->arrayProperty = new Collection();
    }
}
