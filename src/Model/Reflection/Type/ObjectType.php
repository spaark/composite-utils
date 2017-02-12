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

class ObjectType extends AbstractType
{
    /**
     * @readable
     * @var string
     */
    protected $classname;

    public function __construct(string $classname)
    {
        $this->classname = $classname;
        $this->initAllReadableTrait();
    }
}
