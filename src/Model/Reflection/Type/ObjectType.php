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

namespace Spaark\CompositeUtils\Model\Reflection\Type;

/**
 * Represetns a data type which must be an instance of an object
 *
 * @property-read string $classname
 */
class ObjectType extends AbstractType
{
    /**
     * The name of the class this must be an instance of
     *
     * @readable
     * @var string
     */
    protected $classname;

    /**
     * Creates this ObjectType with the given classname
     *
     * @param string $class The name of the class this must be an
     *     instance of
     */
    public function __construct(string $classname)
    {
        $this->classname = $classname;
    }
}
