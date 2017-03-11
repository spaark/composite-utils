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

use Spaark\CompositeUtils\Model\Collection\ArrayList;
use Spaark\CompositeUtils\Model\ClassName;

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
     * @var ClassName
     */
    protected $classname;

    /**
     * Generic types for this object
     *
     * @readable
     * @var ArrayList
     */
    protected $generics;

    /**
     * Creates this ObjectType with the given classname
     *
     * @param mixed $class The name of the class this must be an
     *     instance of
     */
    public function __construct($classname)
    {
        $this->generics = new ArrayList();
        $this->classname = $classname instanceof ClassName
            ? $classname
            : new ClassName($classname);
    }

    /**
     * {@inheritDoc}
     */
    public function compatible(AbstractType $type) : bool
    {
        return $this->compare($type) >= 0;
    }

    /**
     * Compares the given type to this
     *
     * @param AbstractType $type
     * @return int
     */
    public function compare($type) : int
    {
        if
        (
            $type instanceof ObjectType &&
            $type->classname === $this->classname &&
            $type->generics->size() <= $this->generics->size()
        )
        {
            foreach ($type->generics as $i => $generic)
            {
                if (!$this->generics[$i]->compatible($generic))
                {
                    return -1;
                }
            }

            return $this->generics->size() - $type->generics->size();
        }

        return -1;
    }

    /**
     * {@inheritDoc}
     */
    public function equals($object) : bool
    {
        return $this->compare($type) === 0;
    }
}
