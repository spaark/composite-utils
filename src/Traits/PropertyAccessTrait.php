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

namespace Spaark\CompositeUtils\Traits;

use Spaark\CompositeUtils\Service\ConditionalPropertyAccessor;

/**
 * Classes with this trait will allow their properties to be accessed
 * according to their access permissions and datatype restrictions
 */
trait PropertyAccessTrait
{
    use HasReflectorTrait;

    /**
     * The accessor for this class
     *
     * @var ConditionalPropertyAccessor
     */
    protected $accessor;

    /**
     * Returns the ConditionalPropertyAccessor for this object, or
     * constructs one on the fly if one does not yet exist
     *
     * @return ConditionalPropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if (!$this->accessor)
        {
            $this->accessor = new ConditionalPropertyAccessor
            (
                $this,
                self::getReflectionComposite()
            );
        }

        return $this->accessor;
    }

    /**
     * Gets the value of a property, if it is publically readable,
     * using the ConditionalPropertyAccessor
     *
     * @param string $property The property to get
     * @return mixed The property value
     */
    public function __get($property)
    {
        return $this->getPropertyAccessor()->getValue($property);
    }

    /**
     * Sets the value of a property, if it is publically writable, and
     * enforces its datatype permissions using the
     * ConditionalPropertyAccessor
     *
     * @param string $property The property to set
     * @param mixed $value The value to set
     */
    public function __set(string $property, $value)
    {
        $this->getPropertyAccessor()->setValue($property, $value);
    }
}
